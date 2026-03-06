<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdditionalGuaranteeRequest;
use App\Http\Requests\StoreMainGuaranteeRequest;
use App\Http\Requests\UpdateAdditionalGuaranteeRequest;
use App\Http\Requests\UpdateMainGuaranteeRequest;
use App\Models\Embassy;
use App\Models\GuaranteeCase;
use App\Models\Patient;
use App\Models\PatientAdditionalDetail;
use App\Models\PatientAdditionalHeader;
use App\Models\PatientAdditionalType;
use App\Models\PatientMainGuarantee;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientGuaranteeController extends Controller
{
    public function __construct(protected PatientService $patientService) {}

    // Main Guarantee Management
    public function createMain($hn): View|RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $embassies = Embassy::all();
        $guaranteeCases = GuaranteeCase::all();

        return view('patients.guarantees.main_add', compact('patient', 'embassies', 'guaranteeCases'));
    }

    public function storeMain(StoreMainGuaranteeRequest $request, string $hn): RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        try {
            $uploadedFiles = [];
            if ($request->hasFile('file')) {
                $filename = $this->patientService->uploadFile($hn, $request->file('file'), 'main_guarantee');
                $uploadedFiles[] = $filename;
            }

            foreach ($request->guarantee_cases as $case) {
                PatientMainGuarantee::create([
                    'hn' => $hn,
                    'embassy' => $request->embassy,
                    'embassy_ref' => $request->embassy_ref,
                    'number' => $request->number,
                    'mb' => $request->mb,
                    'issue_date' => $request->issue_date,
                    'cover_start_date' => $request->cover_start_date,
                    'cover_end_date' => $request->cover_end_date,
                    'case' => $case,
                    'file' => $uploadedFiles,
                ]);
            }

            return redirect()->route('patients.view', $hn)->with('success', 'Main guarantee added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add main guarantee: '.$e->getMessage())->withInput();
        }
    }

    public function editMain($hn, $id): View|RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $guarantee = PatientMainGuarantee::find($id);
        if (! $guarantee || $guarantee->hn != $hn) {
            return redirect()->route('patients.view', $hn)->with('error', 'Guarantee not found');
        }

        $relatedGuarantees = PatientMainGuarantee::where('hn', $hn)
            ->where('embassy', $guarantee->embassy)
            ->where('embassy_ref', $guarantee->embassy_ref)
            ->where('issue_date', $guarantee->issue_date)
            ->where('cover_start_date', $guarantee->cover_start_date)
            ->where('cover_end_date', $guarantee->cover_end_date)
            ->get();

        $selectedCases = $relatedGuarantees->pluck('case')->toArray();
        $embassies = Embassy::all();
        $guaranteeCases = GuaranteeCase::all();

        return view('patients.guarantees.main_edit', compact('patient', 'guarantee', 'embassies', 'guaranteeCases', 'selectedCases', 'relatedGuarantees'));
    }

    public function updateMain(UpdateMainGuaranteeRequest $request, $hn, $id): RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $guarantee = PatientMainGuarantee::find($id);
        if (! $guarantee || $guarantee->hn != $hn) {
            return redirect()->route('patients.view', $hn)->with('error', 'Guarantee not found');
        }

        try {
            $relatedGuarantees = PatientMainGuarantee::where('hn', $hn)
                ->where('embassy', $guarantee->embassy)
                ->where('embassy_ref', $guarantee->embassy_ref)
                ->where('issue_date', $guarantee->issue_date)
                ->where('cover_start_date', $guarantee->cover_start_date)
                ->where('cover_end_date', $guarantee->cover_end_date)
                ->get();

            $uploadedFiles = $guarantee->file;
            if ($request->hasFile('file')) {
                $filename = $this->patientService->uploadFile($hn, $request->file('file'), 'main_guarantee');
                $uploadedFiles[] = $filename;
            }

            foreach ($relatedGuarantees as $relatedGuarantee) {
                $relatedGuarantee->delete();
            }

            foreach ($request->guarantee_cases as $case) {
                PatientMainGuarantee::create([
                    'hn' => $hn,
                    'embassy' => $request->embassy,
                    'embassy_ref' => $request->embassy_ref,
                    'number' => $request->number,
                    'mb' => $request->mb,
                    'issue_date' => $request->issue_date,
                    'cover_start_date' => $request->cover_start_date,
                    'cover_end_date' => $request->cover_end_date,
                    'case' => $case,
                    'file' => $uploadedFiles,
                ]);
            }

            return redirect()->route('patients.view', $hn)->with('success', 'Main guarantee updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update main guarantee: '.$e->getMessage())->withInput();
        }
    }

    public function extendMain(Request $request, $hn, $id): RedirectResponse
    {
        $request->validate(['cover_end_date' => 'required|date']);

        $guarantee = PatientMainGuarantee::where('hn', $hn)->find($id);
        if (! $guarantee) {
            return redirect()->route('patients.view', $hn)->with('error', 'Guarantee not found');
        }

        try {
            $files = $guarantee->file ?? [];
            if ($request->hasFile('file')) {
                $filename = $this->patientService->uploadFile($hn, $request->file('file'), 'main_guarantee_extension');
                $files[] = $filename;
            }

            $guarantee->update([
                'extension' => true,
                'extension_cover_end_date' => $request->cover_end_date,
                'file' => $files,
            ]);

            $this->patientService->logAction($hn, 'extended main guarantee');

            return redirect()->route('patients.view', $hn)->with('success', 'Main guarantee extended successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to extend main guarantee: '.$e->getMessage())->withInput();
        }
    }

    public function destroyMain($hn, $id): RedirectResponse
    {
        $guarantee = PatientMainGuarantee::where('hn', $hn)->find($id);
        if (! $guarantee) {
            return redirect()->route('patients.view', $hn)->with('error', 'Guarantee not found');
        }

        try {
            foreach ($guarantee->file as $file) {
                $count = PatientMainGuarantee::where('hn', $hn)->where('file', 'like', '%'.$file.'%')->count();
                if ($count == 1) {
                    $this->patientService->deleteFile($hn, $file);
                }
            }
            $guarantee->delete();
            $this->patientService->logAction($hn, 'deleted main guarantee');

            return redirect()->route('patients.view', $hn)->with('success', 'Main guarantee deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('patients.view', $hn)->with('error', 'Failed to delete main guarantee: '.$e->getMessage());
        }
    }

    public function deleteMainFile(Request $request, $hn, $id): JsonResponse
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
            $files = $guarantee->file;
            $fileIndex = array_search($filename, $files);
            if ($fileIndex === false) {
                return response()->json(['success' => false, 'message' => 'File not found in guarantee'], 404);
            }

            unset($files[$fileIndex]);
            $files = array_values($files);

            $mainGuarantees = PatientMainGuarantee::where('hn', $hn)->where('file', 'like', '%'.$filename.'%')->get();
            foreach ($mainGuarantees as $mainGuarantee) {
                $mainGuarantee->file = $files;
                $mainGuarantee->save();
            }

            $this->patientService->deleteFile($hn, $filename);
            $this->patientService->logAction($hn, 'deleted file from main guarantee: '.$filename);

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete file: '.$e->getMessage()], 500);
        }
    }

    // Additional Guarantee Management
    public function createAdditional($hn): View|RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $embassies = Embassy::all();
        $additionalTypes = PatientAdditionalType::all();
        $additionalCases = GuaranteeCase::all();

        return view('patients.guarantees.addtional_add', compact('patient', 'embassies', 'additionalTypes', 'additionalCases'));
    }

    public function storeAdditional(StoreAdditionalGuaranteeRequest $request, string $hn): RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        try {
            $uploadedFiles = [];
            if ($request->hasFile('file')) {
                $filename = $this->patientService->uploadFile($hn, $request->file('file'), 'additional_guarantee');
                $uploadedFiles[] = $filename;
            }

            $header = PatientAdditionalHeader::create([
                'hn' => $hn,
                'type' => $request->type,
                'embassy_ref' => $request->embassy_ref,
                'mb' => $request->mb,
                'issue_date' => $request->issue_date,
                'cover_start_date' => $request->cover_start_date,
                'cover_end_date' => $request->cover_end_date,
                'total_price' => $request->total_price ? str_replace(',', '', $request->total_price) : null,
                'file' => $uploadedFiles,
            ]);

            foreach ($request->details as $detail) {
                $specificDate = null;
                $startDate = null;
                $endDate = null;

                if (! empty($detail['date_start']) && ! empty($detail['date_end'])) {
                    $startDate = $detail['date_start'];
                    $endDate = $detail['date_end'];
                } elseif (! empty($detail['specific_dates']) && is_array($detail['specific_dates'])) {
                    $specificDate = array_filter($detail['specific_dates']);
                }

                PatientAdditionalDetail::create([
                    'guarantee_header_id' => $header->id,
                    'case' => $detail['additional_case'],
                    'specific_date' => $specificDate,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'details' => $detail['detail'] ?? null,
                    'definition' => $detail['definition'] ?? null,
                    'amount' => $detail['amount'] ?? null,
                    'price' => $detail['price'] ? str_replace(',', '', $detail['price']) : null,
                ]);
            }

            return redirect()->route('patients.view', $hn)->with('success', 'Additional guarantee added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add additional guarantee: '.$e->getMessage())->withInput();
        }
    }

    public function createAdditionalDetail($hn, $id): View|RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $guarantee = PatientAdditionalHeader::find($id);
        if (! $guarantee) {
            return redirect()->back()->with('error', 'Additional guarantee header not found');
        }

        $embassies = Embassy::all();
        $additionalTypes = PatientAdditionalType::all();
        $additionalCases = GuaranteeCase::all();

        return view('patients.guarantees.addtional_detail_add', compact('patient', 'guarantee', 'embassies', 'additionalTypes', 'additionalCases'));
    }

    public function storeAdditionalDetail(Request $request, $hn, $id): RedirectResponse
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
            $specificDate = null;
            $startDate = null;
            $endDate = null;

            if (! empty($detail['date_start']) && ! empty($detail['date_end'])) {
                $startDate = $detail['date_start'];
                $endDate = $detail['date_end'];
            } elseif (! empty($detail['specific_dates']) && is_array($detail['specific_dates'])) {
                $specificDate = array_filter($detail['specific_dates']);
            }

            PatientAdditionalDetail::create([
                'guarantee_header_id' => $header->id,
                'case' => $detail['case_id'],
                'specific_date' => $specificDate,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'details' => $detail['detail'] ?? null,
                'definition' => $detail['definition'] ?? null,
                'amount' => $detail['amount'] ?? null,
                'price' => $detail['price'] ? str_replace(',', '', $detail['price']) : null,
            ]);
        }

        return redirect()->route('patients.view', $patient->hn)->with('success', 'Guarantee detail added successfully');
    }

    public function editAdditionalDetail($hn, $id): View|RedirectResponse
    {
        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $detail = PatientAdditionalDetail::find($id);
        if (! $detail) {
            return redirect()->back()->with('error', 'Additional guarantee detail not found');
        }

        $embassies = Embassy::all();
        $additionalTypes = PatientAdditionalType::all();
        $additionalCases = GuaranteeCase::all();
        $header = $detail->header;

        return view('patients.guarantees.addtional_edit', compact('patient', 'detail', 'header', 'embassies', 'additionalTypes', 'additionalCases'));
    }

    public function updateAdditionalDetail(UpdateAdditionalGuaranteeRequest $request, $hn, $id): RedirectResponse
    {
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
            $uploadedFiles = $header->file ?? [];

            if ($request->has('files_to_remove')) {
                $filesToRemove = json_decode($request->files_to_remove, true);
                if (is_array($filesToRemove)) {
                    $uploadedFiles = array_filter($uploadedFiles, function ($file) use ($filesToRemove) {
                        return ! in_array($file, $filesToRemove);
                    });
                    $uploadedFiles = array_values($uploadedFiles);

                    foreach ($filesToRemove as $fileToRemove) {
                        $this->patientService->deleteFile($hn, $fileToRemove);
                    }
                }
            }

            if ($request->hasFile('file')) {
                $filename = $this->patientService->uploadFile($hn, $request->file('file'), 'additional_guarantee');
                $uploadedFiles[] = $filename;
            }

            $header->update([
                'type' => $request->type,
                'embassy_ref' => $request->embassy_ref,
                'mb' => $request->mb,
                'issue_date' => $request->issue_date,
                'cover_start_date' => $request->cover_start_date,
                'cover_end_date' => $request->cover_end_date,
                'total_price' => $request->total_price ? str_replace(',', '', $request->total_price) : null,
                'file' => $uploadedFiles,
            ]);

            $specificDate = null;
            $startDate = null;
            $endDate = null;

            if (! empty($request->date_range_start) && ! empty($request->date_range_end)) {
                $startDate = $request->date_range_start;
                $endDate = $request->date_range_end;
            } elseif (! empty($request->specific_dates) && is_array($request->specific_dates)) {
                $specificDate = array_filter($request->specific_dates);
            }

            $detail->update([
                'case' => $request->additional_case,
                'specific_date' => $specificDate,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'use_date' => $request->use_date,
                'details' => $request->detail,
                'definition' => $request->definition,
                'amount' => $request->amount,
                'price' => $request->price ? str_replace(',', '', $request->price) : null,
            ]);

            return redirect()->route('patients.view', $hn)->with('success', 'Additional guarantee updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update additional guarantee: '.$e->getMessage())->withInput();
        }
    }

    public function destroyAdditionalDetail($hn, $id): RedirectResponse
    {
        $detail = PatientAdditionalDetail::find($id);
        if (! $detail) {
            return redirect()->back()->with('error', 'Additional guarantee not found');
        }

        try {
            $headerId = $detail->guarantee_header_id;
            $detail->delete();

            $header = PatientAdditionalHeader::find($headerId);
            if ($header->details->count() == 0) {
                foreach ($header->file as $file) {
                    $this->patientService->deleteFile($hn, $file);
                }
                $header->delete();
            }

            return redirect()->back()->with('success', 'Additional guarantee detail deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete additional guarantee detail: '.$e->getMessage());
        }
    }

    public function setAdditionalUseDate(Request $request, $hn, $id): RedirectResponse
    {
        $request->validate(['use_date' => 'required|date']);

        $patient = Patient::find($hn);
        if (! $patient) {
            return redirect()->route('patients.index')->with('error', 'Patient not found');
        }

        $detail = PatientAdditionalDetail::find($id);
        if (! $detail || ! $detail->header || $detail->header->hn != $hn) {
            return redirect()->route('patients.view', $hn)->with('error', 'Additional guarantee detail not found');
        }

        try {
            $detail->update(['use_date' => $request->use_date]);
            $this->patientService->logAction($hn, 'set additional detail use date');

            return redirect()->route('patients.view', $hn)->with('success', 'Use date set successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to set use date: '.$e->getMessage())->withInput();
        }
    }
}
