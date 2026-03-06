<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Models\Patient;
use App\Models\PatientNote;
use App\Services\PatientService;
use Illuminate\Http\RedirectResponse;

class PatientNoteController extends Controller
{
    public function __construct(protected PatientService $patientService) {}

    public function store(StoreNoteRequest $request, string $hn): RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $userName = auth()->user()->name;
        $noteWithUser = $request->note.' - '.$userName;

        PatientNote::create([
            'hn' => $hn,
            'note' => $noteWithUser,
        ]);

        $this->patientService->logAction($hn, 'added note');

        return redirect()->route('patients.view', $hn)->with('success', 'Note added successfully');
    }

    public function destroy(string $hn, int $id): RedirectResponse
    {
        $note = PatientNote::where('hn', $hn)->find($id);

        if (! $note) {
            return redirect()->route('patients.view', $hn)->with('error', 'Note not found');
        }

        try {
            $note->delete();
            $this->patientService->logAction($hn, 'deleted note');

            return redirect()->route('patients.view', $hn)->with('success', 'Note deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.view', $hn)->with('error', 'Failed to delete note: '.$e->getMessage());
        }
    }
}
