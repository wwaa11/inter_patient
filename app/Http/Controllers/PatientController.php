<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\PatientMedicalReport;
use App\Models\PatientNote;
use App\Models\PatientPassport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

    private function logAction($hn, $action)
    {
        PatientLog::create([
            'hn'        => $hn,
            'action'    => $action . ' (' . auth()->user()->name . ')',
            'action_by' => auth()->user()->userid,
        ]);
    }

    public function index()
    {
        $patients = Patient::with(['notes', 'passports', 'medicalReports'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        $nationalities = Patient::distinct('nationality')->pluck('nationality');

        return view('patients.index', compact('patients', 'nationalities'));
    }

    public function create()
    {

        return view('patients.create');
    }

    public function getPatientInfo(Request $request)
    {
        $hn = $request->hn;

        try {
            $response = Http::withHeaders(['key' => env('API_SSB_KEY')])
                ->timeout(5)
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

            // Log the action
            $this->logAction($patient->hn, 'Patient created');

            return redirect()->route('patients.show', $patient->hn)
                ->with('success', 'Patient created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create patient: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($hn)
    {
        $patient = Patient::with(['notes', 'passports', 'medicalReports'])->find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')
                ->with('error', 'Patient not found');
        }

        // Calculate passport status and get latest passport
        $latestPassport      = null;
        $passportsWithStatus = collect();

        if ($patient->passports->count() > 0) {
            // Sort passports by issue date descending
            $sortedPassports = $patient->passports->sortByDesc('issue_date');
            $latestPassport  = $sortedPassports->first();

            // Add status to each passport
            $passportsWithStatus = $sortedPassports->map(function ($passport) {
                $expiryDate      = \Carbon\Carbon::parse($passport->expiry_date);
                $now             = \Carbon\Carbon::now();
                $daysUntilExpiry = $now->diffInDays($expiryDate, false);

                if ($daysUntilExpiry < 0) {
                    $passport->status       = 'expired';
                    $passport->status_class = 'bg-red-100 text-red-800';
                    $passport->status_text  = 'Expired';
                } elseif ($daysUntilExpiry <= 90) {
                    $passport->status       = 'expiring_soon';
                    $passport->status_class = 'bg-yellow-100 text-yellow-800';
                    $passport->status_text  = 'Expiring Soon';
                } else {
                    $passport->status       = 'valid';
                    $passport->status_class = 'bg-green-100 text-green-800';
                    $passport->status_text  = 'Valid';
                }

                return $passport;
            });

            // Add status to latest passport
            if ($latestPassport) {
                $expiryDate      = \Carbon\Carbon::parse($latestPassport->expiry_date);
                $now             = \Carbon\Carbon::now();
                $daysUntilExpiry = $now->diffInDays($expiryDate, false);

                if ($daysUntilExpiry < 0) {
                    $latestPassport->status       = 'expired';
                    $latestPassport->status_class = 'bg-red-100 text-red-800';
                    $latestPassport->status_text  = 'Expired';
                } elseif ($daysUntilExpiry <= 90) {
                    $latestPassport->status       = 'expiring_soon';
                    $latestPassport->status_class = 'bg-yellow-100 text-yellow-800';
                    $latestPassport->status_text  = 'Expiring Soon';
                } else {
                    $latestPassport->status       = 'valid';
                    $latestPassport->status_class = 'bg-green-100 text-green-800';
                    $latestPassport->status_text  = 'Valid';
                }
            }
        }

        return view('patients.show', compact('patient', 'latestPassport', 'passportsWithStatus'));
    }

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
            mkdir($directory, 0755, true);
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

        // Log the action
        $this->logAction($hn, 'Passport added');

        return redirect()->route('patients.show', $hn)->with('success', 'Passport added successfully');
    }

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
            mkdir($directory, 0755, true);
        }

        // Handle file upload
        $file     = $request->file('file');
        $filename = 'medical_' . time() . '_' . $file->getClientOriginalName();
        $file->move($directory, $filename);

        // Store medical record
        PatientMedicalReport::create([
            'hn'   => $hn,
            'file' => $filename,
            'date' => $request->date,
        ]);

        // Log the action
        $this->logAction($hn, 'Medical record added');

        return redirect()->route('patients.show', $hn)->with('success', 'Medical record added successfully');
    }

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

        // Log the action
        $this->logAction($hn, 'Note added');

        return redirect()->route('patients.show', $hn)->with('success', 'Note added successfully');
    }

    public function destroyNote($hn, $id)
    {
        try {
            $note = PatientNote::where('hn', $hn)->where('id', $id)->firstOrFail();
            $note->delete();

            // Log the action
            $this->logAction($hn, 'Note deleted');

            return redirect()->route('patients.show', $hn)
                ->with('success', 'Note deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.show', $hn)
                ->with('error', 'Failed to delete note: ' . $e->getMessage());
        }
    }

    public function destroyPassport($hn, $id)
    {
        try {
            $passport = PatientPassport::where('hn', $hn)->where('id', $id)->firstOrFail();

            // Delete the file if it exists
            if ($passport->file_path && Storage::exists($passport->file_path)) {
                Storage::delete($passport->file_path);
            }

            $passport->delete();

            // Log the action
            $this->logAction($hn, 'Passport deleted');

            return redirect()->route('patients.show', $hn)
                ->with('success', 'Passport deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.show', $hn)
                ->with('error', 'Failed to delete passport: ' . $e->getMessage());
        }
    }

    public function destroyMedicalReport($hn, $id)
    {
        try {
            $record = PatientMedicalReport::where('hn', $hn)->where('id', $id)->firstOrFail();

            // Delete the file if it exists
            if ($record->file_path && Storage::exists($record->file_path)) {
                Storage::delete($record->file_path);
            }

            $record->delete();

            // Log the action
            $this->logAction($hn, 'Medical record deleted');

            return redirect()->route('patients.show', $hn)
                ->with('success', 'Medical record deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.show', $hn)
                ->with('error', 'Failed to delete medical record: ' . $e->getMessage());
        }
    }

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

            // Log the action
            $this->logAction($patient->hn, 'Patient updated');

            return redirect()->route('patients.show', $patient->hn)
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
            // Log the action before deletion
            $this->logAction($patient->hn, 'Patient deleted');

            $patient->delete();
            $patient->patientNotes()->delete();
            $patient->patientPassports()->delete();
            $patient->patientMedicalReports()->delete();

            return redirect()->route('patients.index')
                ->with('success', 'Patient deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')
                ->with('error', 'Failed to delete patient: ' . $e->getMessage());
        }
    }
}
