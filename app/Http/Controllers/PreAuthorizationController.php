<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyInternalNoteRequest;
use App\Http\Requests\StorePreAuthorizationNoteRequest;
use App\Http\Requests\StorePreAuthorizationRequest;
use App\Http\Requests\UpdatePreAuthorizationRequest;
use App\Models\Notifier;
use App\Models\PreAuthorization;
use App\Models\PreAuthorizationAttachment;
use App\Models\PreAuthorizationNote;
use App\Models\Provider;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PreAuthorizationController extends Controller
{
    private function getAttachmentFullPath(PreAuthorizationAttachment $attachment): string
    {
        if (str_starts_with($attachment->path, 'hn/')) {
            return public_path(str_replace('/', DIRECTORY_SEPARATOR, $attachment->path));
        }

        return Storage::disk('local')->path($attachment->path);
    }

    private function storeGopAttachments(Request $request, PreAuthorization $preauth): void
    {
        if (! $request->hasFile('gop_attachments')) {
            return;
        }

        $dir = public_path('hn/'.$preauth->hn.'/preauth/'.$preauth->id);
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        foreach ($request->file('gop_attachments') as $file) {
            $originalName = $file->getClientOriginalName();
            $filename = time().'_'.uniqid().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $relativePath = 'hn/'.$preauth->hn.'/preauth/'.$preauth->id.'/'.$filename;
            $file->move($dir, $filename);
            $preauth->attachments()->create([
                'path' => $relativePath,
                'original_name' => $originalName,
            ]);
        }
    }

    public function index(Request $request): View
    {
        $query = PreAuthorization::query()
            ->with(['serviceType', 'provider', 'notifier', 'handlingStaffs']);

        $serviceTypes = ServiceType::query()->orderBy('name')->get();
        $adminUsers = User::query()->where('role', 'admin')->orderBy('name')->get();

        if ($request->filled('hn')) {
            $query->where('hn', 'like', '%'.$request->hn.'%');
            $query->orWhere('patient_name', 'like', '%'.$request->hn.'%');
        }
        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }
        if ($request->filled('case_status')) {
            $query->where('case_status', $request->case_status);
        }
        if ($request->filled('handling_staff_id')) {
            $query->whereHas('handlingStaffs', function ($query) use ($request) {
                $query->where('users.id', $request->handling_staff_id);
            });
        }

        $preAuthorizations = $query
            ->orderByRaw("CASE WHEN case_status = '".PreAuthorization::CASE_STATUS_DELETED."' THEN 1 ELSE 0 END")
            ->orderByRaw("CASE WHEN case_status = '".PreAuthorization::CASE_STATUS_IN_PROGRESS."' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN case_status = '".PreAuthorization::CASE_STATUS_COMPLETE."' THEN 1 ELSE 0 END")
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('preauth.index', compact('preAuthorizations', 'serviceTypes', 'adminUsers'));
    }

    public function show(PreAuthorization $preauth): View
    {
        $preauth->load([
            'serviceType', 'provider', 'notifier', 'handlingStaffs',
            'gopTranslateByUser', 'attachments', 'notes',
        ]);
        $serviceTypes = ServiceType::query()->orderBy('name')->get();
        $providers = Provider::query()->orderBy('name')->get();
        $notifiers = Notifier::query()->orderBy('name')->get();
        $adminUsers = User::query()->where('role', 'admin')->orderBy('name')->get();

        return view('preauth.show', compact('preauth', 'serviceTypes', 'providers', 'notifiers', 'adminUsers'));
    }

    public function storeNote(StorePreAuthorizationNoteRequest $request, PreAuthorization $preauth): RedirectResponse
    {
        $preauth->notes()->create([
            'note' => $request->validated('note'),
            'created_by' => $request->user()->name,
        ]);

        return redirect()->route('preauth.show', $preauth)
            ->with('success', 'Note added.');
    }

    public function destroyNote(DestroyInternalNoteRequest $request, PreAuthorization $preauth, PreAuthorizationNote $note): RedirectResponse
    {
        if ((int) $note->pre_authorization_id !== (int) $preauth->id) {
            abort(404);
        }

        $note->delete();

        return redirect()->route('preauth.show', $preauth)
            ->with('success', 'Note removed.');
    }

    public function create(): View
    {
        $serviceTypes = ServiceType::query()->orderBy('name')->get();
        $providers = Provider::query()->orderBy('name')->get();
        $notifiers = Notifier::query()->orderBy('name')->get();
        $adminUsers = User::query()->where('role', 'admin')->orderBy('name')->get();

        return view('preauth.create', compact('serviceTypes', 'providers', 'notifiers', 'adminUsers'));
    }

    public function store(StorePreAuthorizationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $handlingStaffs = $validated['handling_staffs'] ?? [];
        unset($validated['handling_staffs'], $validated['gop_attachments']);

        $preAuth = PreAuthorization::create($validated);
        $preAuth->handlingStaffs()->sync($handlingStaffs);
        $this->storeGopAttachments($request, $preAuth);

        return redirect()->route('preauth.index')
            ->with('success', 'Pre-authorization created successfully.');
    }

    public function update(UpdatePreAuthorizationRequest $request, PreAuthorization $preauth): RedirectResponse
    {
        $validated = $request->validated();
        $handlingStaffs = $validated['handling_staffs'] ?? [];
        unset($validated['handling_staffs'], $validated['gop_attachments']);

        $preauth->update($validated);
        $preauth->handlingStaffs()->sync($handlingStaffs);
        $this->storeGopAttachments($request, $preauth);

        $redirectTo = $request->get('redirect_to', 'preauth.index');
        if ($redirectTo === 'preauth.show') {
            return redirect()->route('preauth.show', $preauth)
                ->with('success', 'Pre-authorization updated successfully.');
        }

        return redirect()->route('preauth.index')
            ->with('success', 'Pre-authorization updated successfully.');
    }

    public function destroy(PreAuthorization $preauth): RedirectResponse
    {
        $preauth->update(['case_status' => PreAuthorization::CASE_STATUS_DELETED]);
        $preauth->delete();

        return redirect()->route('preauth.index')
            ->with('success', 'Pre-authorization deleted successfully (Soft Deleted).');
    }

    public function storeAttachment(Request $request, PreAuthorization $preauth): RedirectResponse
    {
        $request->validate([
            'gop_attachments' => ['required', 'array', 'min:1'],
            'gop_attachments.*' => ['file', 'mimes:pdf,jpeg,jpg,png,gif', 'max:10240'],
        ]);

        $this->storeGopAttachments($request, $preauth);

        return redirect()->route('preauth.show', $preauth)
            ->with('success', 'GOP attachment(s) added.');
    }

    public function destroyAttachment(PreAuthorization $preauth, string $attachment): RedirectResponse
    {
        $attachmentModel = $preauth->attachments()->findOrFail($attachment);

        try {
            if (str_starts_with($attachmentModel->path, 'hn/')) {
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
            // Ignore file deletion errors; still remove the record below
        }
        $attachmentModel->delete();

        return redirect()->route('preauth.show', $preauth)
            ->with('success', 'Attachment removed.');
    }

    public function downloadAttachment(PreAuthorization $preauth, PreAuthorizationAttachment $attachment)
    {
        if ($attachment->pre_authorization_id !== $preauth->id) {
            abort(404);
        }
        $fullPath = $this->getAttachmentFullPath($attachment);
        if (! File::exists($fullPath)) {
            abort(404);
        }

        return response()->download($fullPath, $attachment->original_name);
    }

    public function viewAttachment(PreAuthorization $preauth, PreAuthorizationAttachment $attachment): BinaryFileResponse
    {
        if ($attachment->pre_authorization_id !== $preauth->id) {
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
