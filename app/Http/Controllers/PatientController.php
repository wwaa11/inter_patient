<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function __construct(protected PatientService $patientService) {}

    /**
     * Display a listing of patients.
     */
    public function index(Request $request): View
    {
        $patients = Patient::query()
            ->when($request->nationality, fn ($q) => $q->where('nationality', $request->nationality))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->gender, fn ($q) => $q->where('gender', $request->gender))
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('hn', 'like', '%'.$request->search.'%')
                        ->orWhere('name', 'like', '%'.$request->search.'%')
                        ->orWhere('qid', 'like', '%'.$request->search.'%');
                });
            })
            ->orderBy('hn', 'asc')
            ->paginate(50);

        $nationalities = Patient::distinct('nationality')->pluck('nationality');

        return view('patients.index', compact('patients', 'nationalities', 'request'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create(): View
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(StorePatientRequest $request): RedirectResponse
    {
        try {
            $patient = Patient::create($request->validated());
            $this->patientService->logAction($patient->hn, 'created');

            return redirect()->route('patients.view', $patient->hn)
                ->with('success', 'Patient created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create patient: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified patient.
     */
    public function view(string $hn): View|RedirectResponse
    {
        $patient = Patient::with(['notes', 'passports', 'medicalReports', 'guaranteeMains'])->find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        // Calculate status for guarantees
        $patient->guaranteeMains->each(fn ($g) => $this->patientService->calculateGuaranteeStatus($g));

        // Calculate status for passports
        $latestPassport = $patient->passports->first();
        if ($latestPassport) {
            $this->patientService->calculatePassportStatus($latestPassport);
        }

        $passportsWithStatus = $patient->passports->map(function ($p) {
            $this->patientService->calculatePassportStatus($p);

            return $p;
        });

        return view('patients.view', compact('patient', 'latestPassport', 'passportsWithStatus'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(string $hn): View|RedirectResponse
    {
        $patient = Patient::find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(UpdatePatientRequest $request, string $hn): RedirectResponse
    {
        $patient = Patient::find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        try {
            $patient->update($request->validated());
            $this->patientService->logAction($hn, 'updated');

            return redirect()->route('patients.view', $patient->hn)
                ->with('success', 'Patient updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update patient: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(string $hn): RedirectResponse
    {
        $patient = Patient::find($hn);

        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        try {
            $this->patientService->logAction($hn, 'deleted');

            // Delete related files before deleting records
            foreach ($patient->passports as $passport) {
                $this->patientService->deleteFile($hn, $passport->file);
            }
            foreach ($patient->medicalReports as $report) {
                $this->patientService->deleteFile($hn, $report->file);
            }
            foreach ($patient->guaranteeMains as $guarantee) {
                foreach ($guarantee->file as $file) {
                    $this->patientService->deleteFile($hn, $file);
                }
            }
            foreach ($patient->guaranteeAdditionals as $header) {
                foreach ($header->file as $file) {
                    $this->patientService->deleteFile($hn, $file);
                }
            }

            $patient->delete();
            // Note: Cascade deletion should ideally be handled at database level or with model observers
            // but keeping it explicit here for now to match original logic.
            $patient->passports()->delete();
            $patient->notes()->delete();
            $patient->medicalReports()->delete();
            $patient->guaranteeMains()->delete();
            $patient->guaranteeAdditionals()->delete();

            return redirect()->route('patients.index')->with('success', 'Patient deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')->with('error', 'Failed to delete patient: '.$e->getMessage());
        }
    }
}
