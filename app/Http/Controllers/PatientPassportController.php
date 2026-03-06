<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePassportRequest;
use App\Models\Patient;
use App\Models\PatientPassport;
use App\Services\PatientService;
use Illuminate\Http\RedirectResponse;

class PatientPassportController extends Controller
{
    public function __construct(protected PatientService $patientService) {}

    public function store(StorePassportRequest $request, string $hn): RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $filename = $this->patientService->uploadFile($hn, $request->file('file'), 'passport');

        PatientPassport::create([
            'hn' => $hn,
            'file' => $filename,
            'number' => $request->number,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
        ]);

        $this->patientService->logAction($hn, 'added passport');

        return redirect()->route('patients.view', $hn)->with('success', 'Passport added successfully');
    }

    public function destroy(string $hn, int $id): RedirectResponse
    {
        $passport = PatientPassport::find($id);

        if (! $passport) {
            return redirect()->route('patients.view', $hn)->with('error', 'Passport not found');
        }

        try {
            $this->patientService->deleteFile($hn, $passport->file);
            $passport->delete();
            $this->patientService->logAction($hn, 'deleted passport');

            return redirect()->route('patients.view', $hn)->with('success', 'Passport deleted successfully');
        } catch (\Exception $e) {
            $this->patientService->logAction($hn, 'failed to delete passport: '.$e->getMessage());

            return redirect()->route('patients.view', $hn)->with('error', 'Failed to delete passport: '.$e->getMessage());
        }
    }
}
