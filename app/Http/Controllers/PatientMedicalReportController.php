<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicalReportRequest;
use App\Models\Patient;
use App\Models\PatientMedicalReport;
use App\Services\PatientService;
use Illuminate\Http\RedirectResponse;

class PatientMedicalReportController extends Controller
{
    public function __construct(protected PatientService $patientService) {}

    public function store(StoreMedicalReportRequest $request, string $hn): RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $filename = $this->patientService->uploadFile($hn, $request->file('file'), 'medical');

        PatientMedicalReport::create([
            'hn' => $hn,
            'file' => $filename,
            'date' => $request->date,
        ]);

        $this->patientService->logAction($hn, 'added medical report');

        return redirect()->route('patients.view', $hn)->with('success', 'Medical report added successfully');
    }

    public function destroy(string $hn, int $id): RedirectResponse
    {
        $report = PatientMedicalReport::where('hn', $hn)->find($id);

        if (! $report) {
            return redirect()->route('patients.view', $hn)->with('error', 'Medical report not found');
        }

        try {
            $this->patientService->deleteFile($hn, $report->file);
            $report->delete();
            $this->patientService->logAction($hn, 'deleted medical report');

            return redirect()->route('patients.view', $hn)->with('success', 'Medical report deleted successfully');
        } catch (\Exception $e) {
            $this->patientService->logAction($hn, 'failed to delete medical report: '.$e->getMessage());

            return redirect()->route('patients.view', $hn)->with('error', 'Failed to delete medical report: '.$e->getMessage());
        }
    }
}
