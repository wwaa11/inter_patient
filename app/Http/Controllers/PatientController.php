<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['notes', 'passports', 'medicalRecords'])
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
                $firstName   = ($patientData['name']['first_en'] ?? $patientData['name']['first_th']) . ' ';
                $lastName    = ($patientData['name']['last_en'] ?? $patientData['name']['last_th']);
                $patientData = [
                    'name'        => $firstName . $lastName,
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
        $patient = Patient::with(['notes', 'passports', 'medicalRecords'])->find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')
                ->with('error', 'Patient not found');
        }

        return view('patients.show', compact('patient'));
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
            $patient->delete();

            return redirect()->route('patients.index')
                ->with('success', 'Patient deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')
                ->with('error', 'Failed to delete patient: ' . $e->getMessage());
        }
    }
}
