<?php
namespace App\Http\Controllers;

use App\Models\Embassy;
use App\Models\GuaranteeCase;
use App\Models\Patient;
use App\Models\PatientAdditionalDetail;
use App\Models\PatientAdditionalHeader;
use App\Models\PatientAdditionalType;
use App\Models\PatientLog;
use App\Models\PatientMainGuarantee;
use App\Models\PatientMedicalReport;
use App\Models\PatientNote;
use App\Models\PatientPassport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    // Utility Functions
    // Core Patient CRUD Operations
    public function index(Request $request)
    {
        $patients = Patient::where(function ($query) use ($request) {
            if ($request->nationality != null) {
                $query->where('nationality', $request->nationality);
            }
            if ($request->type != null) {
                $query->where('type', $request->type);
            }
            if ($request->gender != null) {
                $query->where('gender', $request->gender);
            }

        })
            ->where(function ($query) use ($request) {
                if ($request->search != null) {
                    $query->where('hn', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('qid', 'like', '%' . $request->search . '%');
                }
            })
            ->orderBy('hn', 'asc')
            ->paginate(50);

        $nationalities = Patient::distinct('nationality')->pluck('nationality');

        return view('patients.index', compact('patients', 'nationalities', 'request'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hn'          => 'required|string|unique:patients,hn|max:20',
            'name'        => 'required|string|max:255',
            'gender'      => 'required|string|in:Male,Female,Other',
            'birthday'    => 'required|date',
            'qid'         => 'required|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'sex'         => 'nullable|string|max:10',
            'type'        => 'nullable|string|max:50',
            'location'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $patient = Patient::create($request->all());

            $this->logAction($patient->hn, 'created');

            return redirect()->route('patients.view', $patient->hn)
                ->with('success', 'Patient created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create patient: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function view($hn)
    {
        $patient = Patient::with(['notes', 'passports', 'medicalReports', 'guaranteeMains'])->find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')
                ->with('error', 'Patient not found');
        }

        // Add status to main guarantees
        $patient->guaranteeMains->each(function ($guarantee) {
            $endDate         = \Carbon\Carbon::parse($guarantee->extension_cover_end_date ?? $guarantee->cover_end_date);
            $now             = \Carbon\Carbon::now();
            $daysUntilExpiry = $now->diff($endDate, false);

            if ($daysUntilExpiry->days > 0 && $daysUntilExpiry->invert == 0) {
                $guarantee->status_class = 'bg-green-100 text-green-800';
                $guarantee->status_text  = 'Valid';
            } elseif ($daysUntilExpiry->days == 0 && $daysUntilExpiry->invert == 1) {
                $guarantee->status_class = 'bg-green-100 text-green-800';
                $guarantee->status_text  = 'Valid';
            } else {
                $guarantee->status_class = 'bg-red-100 text-red-800';
                $guarantee->status_text  = 'Invalid';
            }
        });

        // Calculate passport status and get latest passport
        $latestPassport      = null;
        $passportsWithStatus = collect();

        if ($patient->passports->count() > 0) {
            // Sort passports by issue date descending
            $sortedPassports = $patient->passports;
            $latestPassport  = $sortedPassports->first();

            // Add status to each passport
            $passportsWithStatus = $sortedPassports->map(function ($passport) {
                $expiryDate      = \Carbon\Carbon::parse($passport->expiry_date);
                $now             = \Carbon\Carbon::now();
                $daysUntilExpiry = $now->diff($expiryDate, false);

                if ($daysUntilExpiry->days > 0 && $daysUntilExpiry->invert == 0) {
                    $passport->status       = 'valid';
                    $passport->status_class = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                    $passport->status_text  = 'Valid';
                } elseif ($daysUntilExpiry->days == 0 && $daysUntilExpiry->invert == 1) {
                    $passport->status       = 'expiring_soon';
                    $passport->status_class = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                    $passport->status_text  = 'Expiring Soon';
                } else if ($daysUntilExpiry->days <= 90 && $daysUntilExpiry->invert == 1) {
                    $passport->status       = 'expiring_soon';
                    $passport->status_class = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                    $passport->status_text  = 'Expiring Soon';
                } else {
                    $passport->status       = 'expired';
                    $passport->status_class = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                    $passport->status_text  = 'Expired';
                }

                return $passport;
            });

            // Add status to latest passport
            if ($latestPassport) {
                $expiryDate      = \Carbon\Carbon::parse($latestPassport->expiry_date);
                $now             = \Carbon\Carbon::now();
                $daysUntilExpiry = $now->diffInDays($expiryDate, false);

                $daysUntilExpiry = $now->diff($expiryDate, false);

                if ($daysUntilExpiry->days > 0 && $daysUntilExpiry->invert == 0) {
                    $latestPassport->status       = 'valid';
                    $latestPassport->status_class = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                    $latestPassport->status_text  = 'Valid';
                } elseif ($daysUntilExpiry->days == 0 && $daysUntilExpiry->invert == 1) {
                    $latestPassport->status       = 'expiring_soon';
                    $latestPassport->status_class = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                    $latestPassport->status_text  = 'Expiring Soon';
                } else if ($daysUntilExpiry->days <= 90 && $daysUntilExpiry->invert == 1) {
                    $latestPassport->status       = 'expiring_soon';
                    $latestPassport->status_class = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                    $latestPassport->status_text  = 'Expiring Soon';
                } else {
                    $latestPassport->status       = 'expired';
                    $latestPassport->status_class = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                    $latestPassport->status_text  = 'Expired';
                }
            }
        }

        return view('patients.view', compact('patient', 'latestPassport', 'passportsWithStatus'));
    }

    public function edit($hn)
    {
        $patient = Patient::find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')
                ->with('error', 'Patient not found');
        }

        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, $hn)
    {
        $patient = Patient::find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')
                ->with('error', 'Patient not found');
        }

        $validator = Validator::make($request->all(), [
            'hn'          => 'required|string|max:20|unique:patients,hn,' . $hn . ',hn',
            'name'        => 'required|string|max:255',
            'gender'      => 'required|string|in:Male,Female,Other',
            'birthday'    => 'required|date',
            'qid'         => 'required|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'sex'         => 'nullable|string|max:10',
            'type'        => 'nullable|string|max:50',
            'location'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $patient->update($request->all());
            $this->logAction($hn, 'updated');

            return redirect()->route('patients.view', $patient->hn)
                ->with('success', 'Patient updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update patient: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($hn)
    {
        $patient = Patient::find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')
                ->with('error', 'Patient not found');
        }

        try {
            $this->logAction($hn, 'deleted');

            $patient->delete();
            $patient->passports()->delete();
            $patient->notes()->delete();
            $patient->medicalReports()->delete();
            $patient->guaranteeMains()->delete();
            $patient->guaranteeAdditionals()->delete();

            return redirect()->route('patients.index')
                ->with('success', 'Patient deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')
                ->with('error', 'Failed to delete patient: ' . $e->getMessage());
        }
    }

    // External API Integration
    public function getPatientInfo(Request $request)
    {
        $hn = $request->hn;

        try {
            $response = Http::withHeaders(['key' => env('API_SSB_KEY')])
                ->timeout(30)
                ->post('http://172.20.1.22/w_phr/api/patient/info', [
                    'hn' => $hn,
                ]);

            if (! $response->successful()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to get patient info from external API',
                ], 500);
            }

            $patientData = $response->json();
            if ($patientData['status'] == 'success') {
                $patientData = $patientData['patient'];
                $firstName   = ($patientData['name']['first_en'] != '') ? $patientData['name']['first_en'] : $patientData['name']['first_th'];
                $lastName    = ($patientData['name']['last_en'] != '') ? $patientData['name']['last_en'] : $patientData['name']['last_th'];
                $patientData = [
                    'name'        => $firstName . ' ' . $lastName,
                    'gender'      => ($patientData['gender'] == 'ชาย') ? 'Male' : 'Female',
                    'birthday'    => date('Y-m-d', strtotime($patientData['brithdate'])),
                    'nationality' => ($patientData['national'] == 'ไทย') ? 'Thai' : $patientData['national'],
                ];

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Patient data retrieved successfully',
                    'data'    => $patientData,
                ]);
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Patient not found',
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    // File Management
    public function viewFile($hn, $filename)
    {
        $filePath = public_path('hn/' . $hn . '/' . $filename);

        if (! file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $mimeType = mime_content_type($filePath);
        return response()->file($filePath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    // Passport Management
    public function storePassport(Request $request, $hn)
    {
        $validator = Validator::make($request->all(), [
            'file'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'number'      => 'nullable|string|max:255',
            'issue_date'  => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        // Create directory if it doesn't exist
        $directory = public_path('hn/' . $hn);
        if (! file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Handle file upload
        $file     = $request->file('file');
        $filename = 'passport_' . time() . '_' . $file->getClientOriginalName();
        $file->move($directory, $filename);

        // Store passport record
        PatientPassport::create([
            'hn'          => $hn,
            'file'        => $filename,
            'number'      => $request->number,
            'issue_date'  => $request->issue_date,
            'expiry_date' => $request->expiry_date,
        ]);
        $this->logAction($hn, 'added passport');

        return redirect()->route('patients.view', $hn)->with('success', 'Passport added successfully');
    }

    public function destroyPassport($hn, $id)
    {
        $passport = PatientPassport::find($id);

        if (! $passport) {
            return redirect()->route('patients.view', $hn)
                ->with('error', 'Passport not found');
        }

        try {
            // Delete the file if it exists
            $filePath = public_path('hn/' . $hn . '/' . $passport->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $passport->delete();
            $this->logAction($hn, 'deleted passport');

            return redirect()->route('patients.view', $hn)
                ->with('success', 'Passport deleted successfully');
        } catch (\Exception $e) {
            $this->logAction($hn, 'failed to delete passport: ' . $e->getMessage());

            return redirect()->route('patients.view', $hn)
                ->with('error', 'Failed to delete passport: ' . $e->getMessage());
        }
    }

    // Medical Report Management
    public function storeMedicalReport(Request $request, $hn)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        // Create directory if it doesn't exist
        $directory = public_path('hn/' . $hn);
        if (! file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Handle file upload
        $file     = $request->file('file');
        $filename = 'medical_' . time() . '_' . $file->getClientOriginalName();
        $file->move($directory, $filename);

        // Store medical report
        PatientMedicalReport::create([
            'hn'   => $hn,
            'file' => $filename,
            'date' => $request->date,
        ]);
        $this->logAction($hn, 'added medical report');

        return redirect()->route('patients.view', $hn)->with('success', 'Medical report added successfully');
    }

    public function destroyMedicalReport($hn, $id)
    {
        $report = PatientMedicalReport::where('hn', $hn)->find($id);

        if (! $report) {
            return redirect()->route('patients.view', $hn)
                ->with('error', 'Medical report not found');
        }

        try {
            // Delete the file if it exists
            $filePath = public_path('hn/' . $hn . '/' . $report->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $report->delete();
            $this->logAction($hn, 'deleted medical report');

            return redirect()->route('patients.view', $hn)
                ->with('success', 'Medical report deleted successfully');
        } catch (\Exception $e) {

            $this->logAction($hn, 'failed to delete medical report: ' . $e->getMessage());
            return redirect()->route('patients.view', $hn)
                ->with('error', 'Failed to delete medical report: ' . $e->getMessage());
        }
    }

    // Note Management
    public function storeNote(Request $request, $hn)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $userName     = auth()->user()->name;
        $noteWithUser = $request->note . ' - ' . $userName;

        // Store note
        PatientNote::create([
            'hn'   => $hn,
            'note' => $noteWithUser,
        ]);
        $this->logAction($hn, 'added note');

        return redirect()->route('patients.view', $hn)->with('success', 'Note added successfully');
    }

    public function destroyNote($hn, $id)
    {
        $note = PatientNote::where('hn', $hn)->find($id);

        if (! $note) {
            return redirect()->route('patients.view', $hn)
                ->with('error', 'Note not found');
        }

        try {
            $note->delete();
            $this->logAction($hn, 'deleted note');

            return redirect()->route('patients.view', $hn)
                ->with('success', 'Note deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.view', $hn)
                ->with('error', 'Failed to delete note: ' . $e->getMessage());
        }
    }

    // Guarantee Main Management
    public function createMainGuarantee($hn)
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $embassies      = Embassy::all();
        $guaranteeCases = GuaranteeCase::all();

        return view('patients.guarantees.main_add', compact('patient', 'embassies', 'guaranteeCases'));
    }

    public function editMainGuarantee($hn, $id)
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $guarantee = PatientMainGuarantee::find($id);
        if (! $guarantee || $guarantee->hn != $hn) {
            return redirect()->route('patients.view', $hn)->with('error', 'Guarantee not found');
        }

        // Get all guarantees with the same embassy, dates, and file to group related cases
        $relatedGuarantees = PatientMainGuarantee::where('hn', $hn)
            ->where('embassy', $guarantee->embassy)
            ->where('embassy_ref', $guarantee->embassy_ref)
            ->where('issue_date', $guarantee->issue_date)
            ->where('cover_start_date', $guarantee->cover_start_date)
            ->where('cover_end_date', $guarantee->cover_end_date)
            ->get();

        $selectedCases = $relatedGuarantees->pluck('case')->toArray();

        $embassies      = Embassy::all();
        $guaranteeCases = GuaranteeCase::all();

        return view('patients.guarantees.main_edit', compact('patient', 'guarantee', 'embassies', 'guaranteeCases', 'selectedCases', 'relatedGuarantees'));
    }

    public function updateMainGuarantee(Request $request, $hn, $id)
    {
        $validator = Validator::make($request->all(), [
            'embassy'          => 'required|string|max:255',
            'embassy_ref'      => 'nullable|string|max:255',
            'number'           => 'nullable|string|max:255',
            'mb'               => 'nullable|string|max:255',
            'issue_date'       => 'required|date',
            'cover_start_date' => 'required|date',
            'cover_end_date'   => 'required|date',
            'guarantee_cases'  => 'required|array|min:1',
            'file'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $guarantee = PatientMainGuarantee::find($id);
        if (! $guarantee || $guarantee->hn != $hn) {
            return redirect()->route('patients.view', $hn)->with('error', 'Guarantee not found');
        }

        try {
            // Get all related guarantees (same group)
            $relatedGuarantees = PatientMainGuarantee::where('hn', $hn)
                ->where('embassy', $guarantee->embassy)
                ->where('embassy_ref', $guarantee->embassy_ref)
                ->where('issue_date', $guarantee->issue_date)
                ->where('cover_start_date', $guarantee->cover_start_date)
                ->where('cover_end_date', $guarantee->cover_end_date)
                ->get();

            $uploadedFiles = $guarantee->file; // Keep existing files
            if ($request->hasFile('file')) {
                $directory = public_path('hn/' . $hn);
                if (! file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $filename = 'main_guarantee_' . time() . '_' . $request->file('file')->getClientOriginalName();
                $request->file('file')->move($directory, $filename);

                $uploadedFiles[] = $filename;
            }

            // Delete all related guarantees
            foreach ($relatedGuarantees as $relatedGuarantee) {
                $relatedGuarantee->delete();
            }

            // Create new guarantee records for each selected case
            foreach ($request->guarantee_cases as $case) {
                PatientMainGuarantee::create([
                    'hn'               => $hn,
                    'embassy'          => $request->embassy,
                    'embassy_ref'      => $request->embassy_ref,
                    'number'           => $request->number,
                    'mb'               => $request->mb,
                    'issue_date'       => $request->issue_date,
                    'cover_start_date' => $request->cover_start_date,
                    'cover_end_date'   => $request->cover_end_date,
                    'case'             => $case,
                    'file'             => $uploadedFiles,
                ]);
            }

            return redirect()->route('patients.view', $hn)->with('success', 'Main guarantee updated successfully');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Failed to update main guarantee: ' . $e->getMessage())->withInput();
        }
    }

    public function extendMainGuarantee(Request $request, $hn, $id)
    {
        $validator = Validator::make($request->all(), [
            'cover_end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $guarantee = PatientMainGuarantee::where('hn', $hn)->find($id);
        if (! $guarantee) {
            return redirect()->route('patients.view', $hn)->with('error', 'Guarantee not found');
        }

        try {
            // Handle file upload
            $directory = public_path('hn/' . $hn);
            if (! file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $files = $guarantee->file ?? [];
            if ($request->hasFile('file')) {
                $file     = $request->file('file');
                $filename = 'main_guarantee_' . time() . '_extension_' . $request->file('file')->getClientOriginalName();

                $file->move($directory, $filename);
                $files[] = $filename;
            }

            // Update guarantee
            $guarantee->update([
                'extension'                => true,
                'extension_cover_end_date' => $request->cover_end_date,
                'file'                     => $files,
            ]);

            $this->logAction($hn, 'extended main guarantee');

            return redirect()->route('patients.view', $hn)
                ->with('success', 'Main guarantee extended successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to extend main guarantee: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeMainGuarantee(Request $request, $hn)
    {
        $validator = Validator::make($request->all(), [
            'embassy'          => 'required|string|max:255',
            'embassy_ref'      => 'required|string|max:255',
            'number'           => 'nullable|string|max:255',
            'mb'               => 'nullable|string|max:255',
            'issue_date'       => 'required|date',
            'cover_start_date' => 'required|date',
            'cover_end_date'   => 'required|date',
            'guarantee_cases'  => 'required|array|min:1',
            'file.*'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        try {
            // Handle file uploads
            $uploadedFiles = [];
            if ($request->hasFile('file')) {
                $directory = public_path('hn/' . $hn);
                if (! file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $filename = 'main_guarantee_' . time() . '_' . $request->file('file')->getClientOriginalName();
                $request->file('file')->move($directory, $filename);
                $uploadedFiles[] = $filename;
            }

            // Create main guarantee record
            foreach ($request->guarantee_cases as $case) {
                PatientMainGuarantee::create([
                    'hn'               => $hn,
                    'embassy'          => $request->embassy,
                    'embassy_ref'      => $request->embassy_ref,
                    'number'           => $request->number,
                    'mb'               => $request->mb,
                    'issue_date'       => $request->issue_date,
                    'cover_start_date' => $request->cover_start_date,
                    'cover_end_date'   => $request->cover_end_date,
                    'case'             => $case,
                    'file'             => $uploadedFiles,
                ]);
            }

            return redirect()->route('patients.view', $hn)->with('success', 'Main guarantee added successfully');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to add main guarantee: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyMainGuarantee($hn, $id)
    {
        $guarantee = PatientMainGuarantee::where('hn', $hn)->find($id);

        if (! $guarantee) {
            return redirect()->route('patients.view', $hn)
                ->with('error', 'Guarantee not found');
        }

        try {
            foreach ($guarantee->file as $file) {
                $samefile_guarantee = PatientMainGuarantee::where('hn', $hn)->where('file', 'like', '%' . $file . '%')->get();
                if ($samefile_guarantee->count() == 1) {
                    $path = public_path('hn/' . $hn . '/' . $file);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                    $this->logAction($hn, 'deleted main guarantee');
                }
            }
            $guarantee->delete();
            $this->logAction($hn, 'deleted main guarantee');

            return redirect()->route('patients.view', $hn)
                ->with('success', 'Main guarantee deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.view', $hn)
                ->with('error', 'Failed to delete main guarantee: ' . $e->getMessage());
        }
    }

    public function deleteMainGuaranteeFile(Request $request, $hn, $id)
    {
        $guarantee = PatientMainGuarantee::where('hn', $hn)->find($id);

        if (! $guarantee) {
            return response()->json(['success' => false, 'message' => 'Guarantee not found'], 404);
        }

        $filename = $request->input('filename');
        if (! $filename) {
            return response()->json(['success' => false, 'message' => 'Filename is required'], 400);
        }

        try {
            // Get current files array
            $files = $guarantee->file;

            // Check if file exists in the guarantee
            $fileIndex = array_search($filename, $files);
            if ($fileIndex === false) {

                return response()->json(['success' => false, 'message' => 'File not found in guarantee'], 404);
            }

            unset($files[$fileIndex]);
            $files = array_values($files);

            // Get all main guarantee that have the same file
            $mainGuarantees = PatientMainGuarantee::where('hn', $hn)
                ->where('file', 'like', '%' . $filename . '%')
                ->get();

            foreach ($mainGuarantees as $mainGuarantee) {
                $mainGuarantee->file = $files;
                $mainGuarantee->save();
            }

            // Remove physical file
            $filePath = public_path('hn/' . $hn . '/' . $filename);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $this->logAction($hn, 'deleted file from main guarantee: ' . $filename);

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete file: ' . $e->getMessage()], 500);
        }
    }

    // Guarantee Additional Management
    public function createAdditionalGuarantee($hn)
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $embassies       = Embassy::all();
        $additionalTypes = PatientAdditionalType::all();
        $additionalCases = GuaranteeCase::all();

        return view('patients.guarantees.addtional_add', compact('patient', 'embassies', 'additionalTypes', 'additionalCases'));
    }

    public function storeGuaranteeAdditional(Request $request, $hn)
    {
        $validator = Validator::make($request->all(), [
            'type'                       => 'required|string|max:255',
            'embassy_ref'                => 'nullable|string|max:255',
            'mb'                         => 'nullable|string|max:255',
            'issue_date'                 => 'required|date',
            'cover_start_date'           => 'nullable|date',
            'cover_end_date'             => 'nullable|date|after_or_equal:cover_start_date',
            'total_price'                => 'nullable|numeric|min:0',
            'details'                    => 'required|array|min:1',
            'details.*.additional_case'  => 'nullable|string',
            'details.*.specific_dates'   => 'nullable|array',
            'details.*.specific_dates.*' => 'nullable|date',
            'details.*.date_range_start' => 'nullable|date',
            'details.*.date_range_end'   => 'nullable|date|after_or_equal:details.*.date_range_start',
            'details.*.detail'           => 'required|string',
            'details.*.definition'       => 'nullable|string',
            'details.*.amount'           => 'nullable|string',
            'details.*.price'            => 'nullable|numeric|min:0',
            'file.*'                     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        try {
            // Handle file uploads
            $uploadedFiles = [];
            if ($request->hasFile('file')) {
                $directory = public_path('hn/' . $hn);
                if (! file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $filename = 'additional_guarantee_' . time() . '_' . $request->file('file')->getClientOriginalName();
                $request->file('file')->move($directory, $filename);
                $uploadedFiles[] = $filename;
            }

            // Create additional guarantee header
            $header = PatientAdditionalHeader::create([
                'hn'               => $hn,
                'type'             => $request->type,
                'embassy_ref'      => $request->embassy_ref,
                'mb'               => $request->mb,
                'issue_date'       => $request->issue_date,
                'cover_start_date' => $request->cover_start_date,
                'cover_end_date'   => $request->cover_end_date,
                'total_price'      => $request->total_price ? str_replace(',', '', $request->total_price) : null,
                'file'             => $uploadedFiles,
            ]);

            // Create additional guarantee details
            foreach ($request->details as $detail) {
                // Handle date storage - prioritize date range over multiple dates
                $specificDate = null;
                $startDate    = null;
                $endDate      = null;

                if (! empty($detail['date_start']) && ! empty($detail['date_end'])) {
                    // Date range mode
                    $startDate = $detail['date_start'];
                    $endDate   = $detail['date_end'];
                } elseif (! empty($detail['specific_dates']) && is_array($detail['specific_dates'])) {
                    $specificDate = array_filter($detail['specific_dates']);
                }
                PatientAdditionalDetail::create([
                    'guarantee_header_id' => $header->id,
                    'case'                => $detail['additional_case'],
                    'specific_date'       => $specificDate,
                    'start_date'          => $startDate,
                    'end_date'            => $endDate,
                    'details'             => $detail['detail'] ?? null,
                    'definition'          => $detail['definition'] ?? null,
                    'amount'              => $detail['amount'] ?? null,
                    'price'               => $detail['price'] ? str_replace(',', '', $detail['price']) : null,
                ]);
            }

            return redirect()->route('patients.view', $hn)->with('success', 'Additional guarantee added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add additional guarantee: ' . $e->getMessage())->withInput();
        }
    }

    public function createGuaranteeDetail($hn, $id)
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $guarantee = PatientAdditionalHeader::find($id);
        if (! $guarantee) {
            return redirect()->back()->with('error', 'Additional guarantee header not found');
        }

        $embassies       = Embassy::all();
        $additionalTypes = PatientAdditionalType::all();
        $additionalCases = GuaranteeCase::all();

        return view('patients.guarantees.addtional_detail_add', compact('patient', 'guarantee', 'embassies', 'additionalTypes', 'additionalCases'));
    }

    public function storeGuaranteeDetail(Request $request, $hn, $id)
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $header = PatientAdditionalHeader::find($id);
        if (! $header) {
            return redirect()->back()->with('error', 'Additional guarantee header not found');
        }

        foreach ($request->details as $detail) {
            // Handle date storage - prioritize date range over multiple dates
            $specificDate = null;
            $startDate    = null;
            $endDate      = null;

            if (! empty($detail['date_start']) && ! empty($detail['date_end'])) {
                // Date range mode
                $startDate = $detail['date_start'];
                $endDate   = $detail['date_end'];
            } elseif (! empty($detail['specific_dates']) && is_array($detail['specific_dates'])) {
                $specificDate = array_filter($detail['specific_dates']);
            }
            PatientAdditionalDetail::create([
                'guarantee_header_id' => $header->id,
                'case'                => $detail['case_id'],
                'specific_date'       => $specificDate,
                'start_date'          => $startDate,
                'end_date'            => $endDate,
                'details'             => $detail['detail'] ?? null,
                'definition'          => $detail['definition'] ?? null,
                'amount'              => $detail['amount'] ?? null,
                'price'               => $detail['price'] ? str_replace(',', '', $detail['price']) : null,
            ]);
        }

        return redirect()->route('patients.view', $patient->hn)->with('success', 'Guarantee detail added successfully');
    }

    public function editGuaranteeAdditionalDetail($hn, $id)
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $detail = PatientAdditionalDetail::find($id);
        if (! $detail) {
            return redirect()->back()->with('error', 'Additional guarantee detail not found');
        }

        $embassies       = Embassy::all();
        $additionalTypes = PatientAdditionalType::all();
        $additionalCases = GuaranteeCase::all();
        $header          = $detail->header;

        return view('patients.guarantees.addtional_edit', compact('patient', 'detail', 'header', 'embassies', 'additionalTypes', 'additionalCases'));
    }

    public function updateGuaranteeAdditionalDetail(Request $request, $hn, $id)
    {
        $validator = Validator::make($request->all(), [
            'type'             => 'required|string|max:255',
            'embassy_ref'      => 'nullable|string|max:255',
            'mb'               => 'nullable|string|max:255',
            'issue_date'       => 'required|date',
            'cover_start_date' => 'nullable|date',
            'cover_end_date'   => 'nullable|date',
            'total_price'      => 'nullable|numeric|min:0',
            'additional_case'  => 'nullable|string',
            'specific_dates'   => 'nullable|array',
            'specific_dates.*' => 'nullable|date',
            'date_range_start' => 'nullable|date',
            'date_range_end'   => 'nullable|date',
            'detail'           => 'required|string',
            'definition'       => 'nullable|string',
            'amount'           => 'nullable|string',
            'price'            => 'nullable|numeric|min:0',
            'file'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files_to_remove'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $detail = PatientAdditionalDetail::find($id);
        if (! $detail) {
            return redirect()->back()->with('error', 'Additional guarantee detail not found');
        }

        try {
            $header = $detail->header;

            // Handle file uploads and removals
            $uploadedFiles = $header->file ?? [];

            // Handle file removal
            if ($request->has('files_to_remove')) {
                $filesToRemove = json_decode($request->files_to_remove, true);
                if (is_array($filesToRemove)) {
                    $uploadedFiles = array_filter($uploadedFiles, function ($file) use ($filesToRemove) {
                        return ! in_array($file, $filesToRemove);
                    });
                    // Re-index the array to avoid gaps
                    $uploadedFiles = array_values($uploadedFiles);

                    // Optionally delete physical files from server
                    foreach ($filesToRemove as $fileToRemove) {
                        $filePath = public_path('hn/' . $hn . '/' . $fileToRemove);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }

            // Handle new file upload
            if ($request->hasFile('file')) {
                $directory = public_path('hn/' . $hn);
                if (! file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $filename = 'additional_guarantee_' . time() . '_' . $request->file('file')->getClientOriginalName();
                $request->file('file')->move($directory, $filename);
                $uploadedFiles[] = $filename;
            }

            // Update header
            $header->update([
                'type'             => $request->type,
                'embassy_ref'      => $request->embassy_ref,
                'mb'               => $request->mb,
                'issue_date'       => $request->issue_date,
                'cover_start_date' => $request->cover_start_date,
                'cover_end_date'   => $request->cover_end_date,
                'total_price'      => $request->total_price ? str_replace(',', '', $request->total_price) : null,
                'file'             => $uploadedFiles,
            ]);

            // Handle date storage - prioritize date range over multiple dates
            $specificDate = null;
            $startDate    = null;
            $endDate      = null;

            if (! empty($request->date_range_start) && ! empty($request->date_range_end)) {
                // Date range mode
                $startDate = $request->date_range_start;
                $endDate   = $request->date_range_end;
            } elseif (! empty($request->specific_dates) && is_array($request->specific_dates)) {
                $specificDate = array_filter($request->specific_dates);
            }

            // Update detail
            $detail->update([
                'case'          => $request->additional_case,
                'specific_date' => $specificDate,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'use_date'      => $request->use_date,
                'details'       => $request->detail,
                'definition'    => $request->definition,
                'amount'        => $request->amount,
                'price'         => $request->price ? str_replace(',', '', $request->price) : null,
            ]);

            return redirect()->route('patients.view', $hn)->with('success', 'Additional guarantee updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update additional guarantee: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyGuaranteeAdditionalDetail($hn, $id)
    {
        $detail = PatientAdditionalDetail::find($id);
        if (! $detail) {
            return redirect()->back()->with('error', 'Additional guarantee not found');
        }

        try {
            $detail->delete();

            $findHeader = PatientAdditionalHeader::find($detail->guarantee_header_id);
            if ($findHeader->details->count() == 0) {
                foreach ($findHeader->file as $file) {
                    $filePath = public_path('hn/' . $hn . '/' . $file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                $findHeader->delete();
            }

            return redirect()->back()->with('success', 'Additional guarantee detail deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete additional guarantee detail: ' . $e->getMessage());
        }
    }

    public function setGuaranteeDetailUseDate(Request $request, $hn, $id)
    {
        $validator = Validator::make($request->all(), [
            'use_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $detail = PatientAdditionalDetail::find($id);
        if (! $detail || ! $detail->header || $detail->header->hn != $hn) {
            return redirect()->route('patients.view', $hn)->with('error', 'Additional guarantee detail not found');
        }

        try {
            $detail->update([
                'use_date' => $request->use_date,
            ]);

            if (method_exists($this, 'logAction')) {
                $this->logAction($hn, 'set additional detail use date');
            }

            return redirect()->route('patients.view', $hn)->with('success', 'Use date set successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to set use date: ' . $e->getMessage())->withInput();
        }
    }

    // Utility Functions
    private function logAction($hn, $action)
    {
        PatientLog::create([
            'hn'        => $hn,
            'action'    => $action . ' (' . auth()->user()->name . ')',
            'action_by' => auth()->user()->userid,
        ]);
    }

}
