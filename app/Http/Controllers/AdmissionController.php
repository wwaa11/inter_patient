<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdmissionRequest;
use App\Http\Requests\UpdateAdmissionRequest;
use App\Models\Admission;
use App\Models\AdmissionAttachment;
use App\Models\PreAuthorization;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdmissionController extends Controller
{
    private function getAttachmentFullPath(AdmissionAttachment $attachment): string
    {
        if (str_starts_with($attachment->path ?? '', 'hn/')) {
            return public_path(str_replace('/', DIRECTORY_SEPARATOR, $attachment->path));
        }

        return Storage::disk('local')->path($attachment->path);
    }

    private function storeGopAttachments(Request $request, Admission $admission): void
    {
        if (! $request->hasFile('gop_attachments')) {
            return;
        }
        $dir = public_path('hn/'.$admission->hn.'/admission/'.$admission->id);
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        foreach ($request->file('gop_attachments') as $file) {
            $originalName = $file->getClientOriginalName();
            $filename = time().'_'.uniqid().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $relativePath = 'hn/'.$admission->hn.'/admission/'.$admission->id.'/'.$filename;
            $file->move($dir, $filename);
            $admission->attachments()->create([
                'path' => $relativePath,
                'original_name' => $originalName,
            ]);
        }
    }

    public function index(Request $request): View
    {
        $query = Admission::query()
            ->with(['preAuthorization', 'contactProviders', 'handlingUsers', 'gopTranslators']);

        if ($request->filled('hn')) {
            $query->where('hn', 'like', '%'.$request->hn.'%');
        }
        if ($request->filled('case_status')) {
            $query->where('case_status', $request->case_status);
        }

        $admissions = $query->latest('admission_date')->latest()->paginate(10);

        return view('admissions.index', compact('admissions'));
    }

    public function create(Request $request): View
    {
        $providers = Provider::query()->orderBy('name')->get();
        $adminUsers = User::query()->where('role', 'admin')->orderBy('name')->get();
        $preAuthList = PreAuthorization::query()
            ->with('provider')
            ->orderBy('hn')
            ->get()
            ->map(fn (PreAuthorization $p) => [
                'id' => $p->id,
                'label' => $p->hn.' - '.$p->patient_name.($p->provider ? ' ('.$p->provider->name.')' : ''),
                'hn' => $p->hn,
                'patient_name' => $p->patient_name,
                'provider_id' => $p->provider_id,
                'provider_name' => $p->provider->name ?? '',
            ])
            ->values();

        $preauth = null;
        if ($request->filled('from_preauth') && $request->from_preauth) {
            $preauthId = $request->get('preauth_id');
            if ($preauthId) {
                $preauth = PreAuthorization::with('provider')->find($preauthId);
            }
        }

        return view('admissions.create', compact('providers', 'adminUsers', 'preAuthList', 'preauth'));
    }

    public function store(StoreAdmissionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $contactProviders = $validated['contact_providers'] ?? [];
        $handlingUsers = $validated['handling_users'] ?? [];
        $gopTranslators = $validated['gop_translators'] ?? [];
        unset($validated['contact_providers'], $validated['handling_users'], $validated['gop_translators'], $validated['gop_attachments']);

        $admission = Admission::create($validated);
        $admission->contactProviders()->sync($contactProviders);
        $admission->handlingUsers()->sync($handlingUsers);
        $admission->gopTranslators()->sync($gopTranslators);
        $this->storeGopAttachments($request, $admission);

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Admission created successfully.');
    }

    public function show(Admission $admission): View
    {
        $admission->load([
            'preAuthorization.provider', 'contactProviders', 'handlingUsers', 'gopTranslators', 'attachments',
        ]);
        $providers = Provider::query()->orderBy('name')->get();
        $adminUsers = User::query()->where('role', 'admin')->orderBy('name')->get();
        $preAuthList = PreAuthorization::query()
            ->with('provider')
            ->orderBy('hn')
            ->get()
            ->map(fn (PreAuthorization $p) => [
                'id' => $p->id,
                'label' => $p->hn.' - '.$p->patient_name.($p->provider ? ' ('.$p->provider->name.')' : ''),
                'hn' => $p->hn,
                'patient_name' => $p->patient_name,
                'provider_id' => $p->provider_id,
                'provider_name' => $p->provider->name ?? '',
                'diagnosis' => '', // Diagnosis isn't in PreAuthorization, but we keep the key for completeness or logic
                'procedure' => $p->operations_procedures,
                'gop_status' => $p->coverage_decision,
                'init_gop_date' => $p->gop_receiving_date?->format('Y-m-d'),
                'gop_ref' => $p->gop_reference_number,
            ])
            ->values();

        return view('admissions.show', compact('admission', 'providers', 'adminUsers', 'preAuthList'));
    }

    public function update(UpdateAdmissionRequest $request, Admission $admission): RedirectResponse
    {
        $validated = $request->validated();
        $contactProviders = $validated['contact_providers'] ?? [];
        $handlingUsers = $validated['handling_users'] ?? [];
        $gopTranslators = $validated['gop_translators'] ?? [];
        unset($validated['contact_providers'], $validated['handling_users'], $validated['gop_translators'], $validated['gop_attachments']);

        $admission->update($validated);
        $admission->contactProviders()->sync($contactProviders);
        $admission->handlingUsers()->sync($handlingUsers);
        $admission->gopTranslators()->sync($gopTranslators);
        $this->storeGopAttachments($request, $admission);

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Admission updated successfully.');
    }

    public function destroy(Admission $admission): RedirectResponse
    {
        $admission->update(['case_status' => Admission::CASE_STATUS_DELETED]);
        $admission->delete();

        return redirect()->route('admissions.index')
            ->with('success', 'Admission deleted successfully (Soft Deleted).');
    }

    public function storeAttachment(Request $request, Admission $admission): RedirectResponse
    {
        $request->validate([
            'gop_attachments' => ['required', 'array', 'min:1'],
            'gop_attachments.*' => ['file', 'mimes:pdf,jpeg,jpg,png,gif', 'max:10240'],
        ]);

        $this->storeGopAttachments($request, $admission);

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'GOP attachment(s) added.');
    }

    public function destroyAttachment(Admission $admission, string $attachment): RedirectResponse
    {
        $attachmentModel = $admission->attachments()->findOrFail($attachment);

        try {
            if (str_starts_with($attachmentModel->path ?? '', 'hn/')) {
                $fullPath = $this->getAttachmentFullPath($attachmentModel);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            } else {
                if (Storage::disk('local')->exists($attachmentModel->path)) {
                    Storage::disk('local')->delete($attachmentModel->path);
                }
            }
        } catch (\Throwable) {
            //
        }
        $attachmentModel->delete();

        return redirect()->route('admissions.show', $admission)
            ->with('success', 'Attachment removed.');
    }

    public function downloadAttachment(Admission $admission, AdmissionAttachment $attachment)
    {
        if ((int) $attachment->admission_id !== (int) $admission->id) {
            abort(404);
        }
        $fullPath = $this->getAttachmentFullPath($attachment);
        if (! File::exists($fullPath)) {
            abort(404);
        }

        return response()->download($fullPath, $attachment->original_name);
    }

    public function viewAttachment(Admission $admission, AdmissionAttachment $attachment): BinaryFileResponse
    {
        if ((int) $attachment->admission_id !== (int) $admission->id) {
            abort(404);
        }
        $fullPath = $this->getAttachmentFullPath($attachment);
        if (! File::exists($fullPath)) {
            abort(404);
        }
        $mimeType = mime_content_type($fullPath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.addslashes($attachment->original_name).'"',
        ]);
    }
}
