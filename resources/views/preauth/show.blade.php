@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('preauth.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white">Pre Authorization</h1>
                        <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">HN {{ $preauth->hn }} — {{ $preauth->patient_name ?? '—' }}</p>
                    </div>
                    @if (auth()->user()->isAdmin())
                        <div class="ml-auto flex flex-wrap items-center gap-3">
                            <button type="button" onclick="toggleEditMode()" id="edit-toggle-btn" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-blue-700">
                                <i class="fa-solid fa-pen-to-square mr-2"></i>
                                Quick Edit
                            </button>
                            <a href="{{ route('admissions.create', ['from_preauth' => 1, 'preauth_id' => $preauth->id]) }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-emerald-700">
                                <i class="fa-solid fa-bed-pulse mr-2"></i>
                                Create Admission
                            </a>
                            <form action="{{ route('preauth.destroy', $preauth) }}" method="POST" class="inline delete-preauth-form">
                                @csrf
                                <button type="button" class="delete-preauth-btn inline-flex items-center rounded-lg border border-red-200 bg-white px-4 py-2 font-medium text-red-700 shadow-sm hover:bg-red-50 dark:border-red-800 dark:bg-slate-700 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fa-solid fa-trash mr-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            @if (session("success") || session("error"))
                <script>
                    window.onload = function() {
                        Swal.fire({
                            icon: '{{ session("success") ? "success" : "error" }}',
                            title: '{{ session("success") ?: session("error") }}',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    };
                </script>
            @endif

            <div id="view-mode-section">
                <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <h2 class="mb-6 text-xl font-semibold text-slate-900 dark:text-white">Case Information</h2>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">HN</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->hn }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Patient Name</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->patient_name ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Service Type</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->serviceType?->name ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Date of Service</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->date_of_service?->format('Y-m-d') ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Notifier</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->notifier?->name ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Requested Date</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->requested_date?->format('Y-m-d H:i') ?? '—' }}</dd></div>
                        <div class="sm:col-span-2"><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Operations/Procedures</dt><dd class="mt-1 whitespace-pre-wrap text-slate-900 dark:text-white">{{ $preauth->operations_procedures ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Handling Staffs</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->handlingStaffs->pluck('name')->join(', ') ?: '—' }}</dd></div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Send Out Date</dt>
                            <dd class="mt-1">
                                @if($preauth->send_out_date)
                                    <span class="text-slate-900 dark:text-white">{{ $preauth->send_out_date->format('Y-m-d') }}</span>
                                    <span class="ml-2 text-sm text-slate-500 dark:text-slate-400">({{ $preauth->send_out_date->diffForHumans() }})</span>
                                    @if($preauth->send_out_date->isPast())
                                        <span class="ml-2 inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Overdue</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </dd>
                        </div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Case Status</dt><dd class="mt-1"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200">{{ $preauth->case_status ?? '—' }}</span></dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">Coverage Decision</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->coverage_decision ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">GOP Receiving Date</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->gop_receiving_date?->format('Y-m-d') ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">GOP Reference Number</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->gop_reference_number ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase text-slate-500 dark:text-slate-400">GOP Translate By</dt><dd class="mt-1 text-slate-900 dark:text-white">{{ $preauth->gopTranslateByUser?->name ?? '—' }}</dd></div>
                    </dl>
                </div>

                <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <h2 class="mb-6 text-xl font-semibold text-slate-900 dark:text-white">Payor / Provider</h2>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                        @if($preauth->provider->logo)
                            <img src="{{ asset('provider-logos/' . $preauth->provider->logo) }}" alt="{{ $preauth->provider->name }}" class="h-20 w-auto shrink-0 object-contain rounded-lg border border-slate-200 dark:border-slate-600">
                        @else
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 dark:border-slate-600 dark:bg-slate-700">
                                <i class="fa-solid fa-building text-slate-400 text-2xl"></i>
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-slate-900 dark:text-white">{{ $preauth->provider->name }}</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Type: {{ $preauth->provider->type }}</p>
                            @if($preauth->provider->detail)
                                <div class="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                    {{ $preauth->provider->detail }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                <div id="edit-mode-section" class="hidden mb-8 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700 flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Quick Edit Case</h2>
                        <button type="button" onclick="toggleEditMode()" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('preauth.update', $preauth) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" id="preauth-form">
                        @csrf
                        <input type="hidden" name="redirect_to" value="preauth.show">
                        
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="service_type_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Service Type</label>
                                <select name="service_type_id" id="service_type_id" required
                                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    <option value="">Select service type</option>
                                    @foreach($serviceTypes as $st)
                                        <option value="{{ $st->id }}" {{ old('service_type_id', $preauth->service_type_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <label for="payor_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Payor</label>
                                <div class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                    <input type="text" id="payor_search" placeholder="Search and click to select..." autocomplete="off"
                                        class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                    <div id="payor_results" class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden"></div>
                                </div>
                                <div id="payor_selected" class="mt-2 flex flex-wrap gap-2">
                                    @php $oldPayor = old('provider_id', $preauth->provider_id); @endphp
                                    @if($oldPayor && $providers->firstWhere('id', $oldPayor))
                                        @php $p = $providers->firstWhere('id', $oldPayor); @endphp
                                        <span class="payor-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                            {{ $p->name }}
                                            <button type="button" class="payor-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>
                                            <input type="hidden" name="provider_id" value="{{ $p->id }}">
                                        </span>
                                    @else
                                        <input type="hidden" name="provider_id" value="" id="payor_empty_input">
                                    @endif
                                </div>
                                <script type="application/json" id="payor_data">{!! json_encode($providers->map(fn($p) => ['id' => $p->id, 'name' => $p->name])->values()) !!}</script>
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="hn" class="block text-sm font-medium text-slate-700 dark:text-slate-300">HN</label>
                                <input type="text" name="hn" id="hn" value="{{ old('hn', $preauth->hn) }}" required
                                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm"
                                    data-get-info-url="{{ route('patients.getInfo') }}">
                            </div>
                            <div>
                                <label for="patient_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Patient Name</label>
                                <div class="relative mt-1">
                                    <input type="text" name="patient_name" id="patient_name" value="{{ old('patient_name', $preauth->patient_name) }}" readonly
                                        class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 pr-10 text-slate-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 sm:text-sm"
                                        placeholder="Enter HN to load">
                                    <span id="hn_loading" class="pointer-events-none absolute right-3 top-1/2 hidden -translate-y-1/2" aria-hidden="true">
                                        <i class="fa-solid fa-spinner fa-spin text-slate-400"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="date_of_service" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Date of Service</label>
                                <input type="date" name="date_of_service" id="date_of_service" value="{{ old('date_of_service', $preauth->date_of_service?->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                            </div>
                            <div>
                                <label for="requested_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Requested Date</label>
                                <input type="datetime-local" name="requested_date" id="requested_date" value="{{ old('requested_date', $preauth->requested_date?->format('Y-m-d\TH:i')) }}"
                                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="operations_procedures" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Operations/Procedures</label>
                            <textarea name="operations_procedures" id="operations_procedures" rows="3"
                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('operations_procedures', $preauth->operations_procedures) }}</textarea>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div class="relative">
                                <label for="notifier_search" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notifier</label>
                                <div class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                    <input type="text" id="notifier_search" placeholder="Search and click to select..." autocomplete="off"
                                        class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                    <div id="notifier_results" class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden"></div>
                                </div>
                                <div id="notifier_selected" class="mt-2 flex flex-wrap gap-2">
                                    @php $oldNotifier = old('notifier_id', $preauth->notifier_id); @endphp
                                    @if($oldNotifier && $notifiers->firstWhere('id', $oldNotifier))
                                        @php $n = $notifiers->firstWhere('id', $oldNotifier); @endphp
                                        <span class="notifier-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                            {{ $n->name }}
                                            <button type="button" class="notifier-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>
                                            <input type="hidden" name="notifier_id" value="{{ $n->id }}">
                                        </span>
                                    @else
                                        <input type="hidden" name="notifier_id" value="" id="notifier_empty_input">
                                    @endif
                                </div>
                                <script type="application/json" id="notifier_data">{!! json_encode($notifiers->map(fn($n) => ['id' => $n->id, 'name' => $n->name])->values()) !!}</script>
                            </div>
                            <div>
                                <label for="gop_translate_by" class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP Translate By</label>
                                <select name="gop_translate_by" id="gop_translate_by" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    <option value="">—</option>
                                    @foreach($adminUsers as $u)
                                        <option value="{{ $u->id }}" {{ old('gop_translate_by', $preauth->gop_translate_by) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Handling Staffs</label>
                            <div class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                <input type="text" id="handling_staff_search" placeholder="Search and click to add..." autocomplete="off"
                                    class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                <div id="handling_staff_results" class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden"></div>
                            </div>
                            @php $selectedStaffIds = old('handling_staffs', $preauth->handlingStaffs->pluck('id')->toArray()); @endphp
                            <div id="handling_staff_selected" class="mt-2 flex flex-wrap gap-2">
                                @foreach($adminUsers as $u)
                                    @if(in_array($u->id, $selectedStaffIds))
                                        <span class="handling-staff-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                            {{ $u->name }}
                                            <button type="button" class="handling-staff-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>
                                            <input type="hidden" name="handling_staffs[]" value="{{ $u->id }}">
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            <script type="application/json" id="handling_staffs_data">{!! json_encode($adminUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'userid' => $u->userid])->values()) !!}</script>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="case_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Case Status</label>
                                <select name="case_status" id="case_status" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    @foreach(\App\Models\PreAuthorization::caseStatusOptions() as $opt)
                                        <option value="{{ $opt }}" {{ old('case_status', $preauth->case_status) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="coverage_decision" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Coverage Decision</label>
                                <select name="coverage_decision" id="coverage_decision" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    <option value="">—</option>
                                    @foreach(\App\Models\PreAuthorization::coverageDecisionOptions() as $opt)
                                        <option value="{{ $opt }}" {{ old('coverage_decision', $preauth->coverage_decision) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-3">
                            <div>
                                <label for="gop_receiving_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP Receiving Date</label>
                                <input type="date" name="gop_receiving_date" id="gop_receiving_date" value="{{ old('gop_receiving_date', $preauth->gop_receiving_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                            </div>
                            <div>
                                <label for="gop_reference_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP Reference Number</label>
                                <input type="text" name="gop_reference_number" id="gop_reference_number" value="{{ old('gop_reference_number', $preauth->gop_reference_number) }}" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                            </div>
                            <div>
                                <label for="send_out_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Send Out Date</label>
                                <input type="date" name="send_out_date" id="send_out_date" value="{{ old('send_out_date', $preauth->send_out_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <button type="button" onclick="toggleEditMode()" class="px-4 py-2 font-medium text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">Cancel</button>
                            <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2 font-medium text-white shadow-sm hover:bg-emerald-700">Save changes</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <h2 class="mb-6 text-xl font-semibold text-slate-900 dark:text-white">GOP Attachments</h2>
                @if($preauth->attachments->isEmpty())
                    <p class="text-slate-500 dark:text-slate-400">No attachments.</p>
                @else
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                        @foreach($preauth->attachments as $att)
                            @php
                                $isPublicPath = str_starts_with($att->path ?? '', 'hn/');
                                $viewUrl = $isPublicPath ? asset($att->path) : route('preauth.attachments.view', [$preauth, $att]);
                                $downloadUrl = $isPublicPath ? asset($att->path) : route('preauth.attachments.download', [$preauth, $att]);
                                $ext = strtolower(pathinfo($att->original_name, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'], true);
                                $isPdf = $ext === 'pdf';
                            @endphp
                            <div class="group flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-slate-50 dark:border-slate-600 dark:bg-slate-900">
                                <button type="button" class="relative block aspect-[4/3] w-full shrink-0 overflow-hidden bg-slate-200 dark:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" onclick="viewPreauthFile('{{ e($viewUrl) }}', {{ json_encode($att->original_name) }})">
                                    @if($isImage)
                                        <img src="{{ $viewUrl }}" alt="{{ $att->original_name }}" class="h-full w-full object-cover transition group-hover:scale-105">
                                    @elseif($isPdf)
                                        <iframe src="{{ $viewUrl }}#toolbar=0&navpanes=0&scrollbar=0" class="h-full w-full border-0" title="{{ $att->original_name }}"></iframe>
                                    @else
                                        <div class="flex h-full w-full items-center justify-center">
                                            <i class="fa-solid fa-file text-4xl text-slate-400 dark:text-slate-500"></i>
                                        </div>
                                    @endif
                                    <span class="absolute inset-0 flex items-center justify-center rounded-lg bg-black/0 text-white opacity-0 transition group-hover:bg-black/30 group-hover:opacity-100">
                                        <i class="fa-solid fa-eye text-2xl"></i>
                                    </span>
                                </button>
                                <div class="flex min-w-0 flex-1 flex-col gap-2 p-2">
                                    <p class="truncate text-xs font-medium text-slate-900 dark:text-white" title="{{ $att->original_name }}">{{ $att->original_name }}</p>
                                    <div class="flex flex-wrap items-center gap-1">
                                        <button type="button" class="rounded bg-blue-600 px-2 py-1 text-xs font-medium text-white hover:bg-blue-700" onclick="viewPreauthFile('{{ e($viewUrl) }}', {{ json_encode($att->original_name) }})">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <a href="{{ $downloadUrl }}" class="rounded bg-slate-200 px-2 py-1 text-xs font-medium text-slate-700 hover:bg-slate-300 dark:bg-slate-600 dark:text-slate-200 dark:hover:bg-slate-500" target="_blank" rel="noopener" @if($isPublicPath) download="{{ $att->original_name }}" @endif>
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                        @if (auth()->user()->isAdmin())
                                            <form action="{{ route('preauth.attachments.destroy', [$preauth, $att]) }}" method="POST" class="inline delete-attachment-form">
                                                @csrf
                                                <button type="button" class="delete-attachment-btn rounded bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if (auth()->user()->isAdmin())
                    <form action="{{ route('preauth.attachments.store', $preauth) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Add attachment (PDF or image)</label>
                        <div id="preauth-gop-drop-zone" class="mt-1 flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 py-8 transition dark:border-slate-600 dark:bg-slate-800/50 hover:border-emerald-400 hover:bg-emerald-50/50 dark:hover:border-emerald-500 dark:hover:bg-emerald-900/20">
                            <input type="file" name="gop_attachments[]" id="preauth_gop_attachments" multiple accept=".pdf,image/jpeg,image/jpg,image/png,image/gif" class="hidden">
                            <i class="fa-solid fa-cloud-arrow-up mb-2 text-3xl text-slate-400 dark:text-slate-500"></i>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Drop files here or click to upload</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">PDF, JPEG, PNG, GIF. Max 10MB per file.</p>
                        </div>
                        <ul id="preauth-gop-file-list" class="mt-3 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4" aria-live="polite"></ul>
                        <div class="mt-3">
                            <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-emerald-700">Upload</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- File Viewer Modal -->
    <div class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm" id="preauthFileModal">
        <div class="relative flex h-full w-full flex-col rounded-lg bg-white shadow-2xl dark:bg-slate-900 mx-auto my-auto max-w-4xl max-h-[90vh]">
            <div class="flex items-center justify-between border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 p-6 shadow-sm dark:border-slate-700 dark:from-slate-800 dark:to-slate-700">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg">
                        <i class="fa-solid fa-file-lines text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">File Viewer</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">GOP attachment</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 transition-all duration-200 hover:scale-105 hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-slate-600 dark:text-slate-200 dark:hover:bg-slate-500" onclick="downloadPreauthFile()">
                        <i class="fa-solid fa-download mr-2"></i>Download
                    </button>
                    <button type="button" class="inline-flex items-center rounded-xl bg-red-100 px-4 py-2.5 text-sm font-medium text-red-700 transition-all duration-200 hover:scale-105 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50" onclick="closePreauthFileModal()">
                        <i class="fa-solid fa-times mr-2"></i>Close
                    </button>
                </div>
            </div>
            <div class="flex-1 overflow-hidden bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
                <input id="preauthFileUrl" type="hidden" value="">
                <div class="flex h-full w-full items-center justify-center p-8" id="preauthFileContent"></div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        function toggleEditMode() {
            const viewSection = document.getElementById('view-mode-section');
            const editSection = document.getElementById('edit-mode-section');
            const toggleBtn = document.getElementById('edit-toggle-btn');
            
            if (viewSection.classList.contains('hidden')) {
                viewSection.classList.remove('hidden');
                editSection.classList.add('hidden');
                toggleBtn.innerHTML = '<i class="fa-solid fa-pen-to-square mr-2"></i>Quick Edit';
                toggleBtn.classList.remove('bg-slate-600', 'hover:bg-slate-700');
                toggleBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                viewSection.classList.add('hidden');
                editSection.classList.remove('hidden');
                toggleBtn.innerHTML = '<i class="fa-solid fa-eye mr-2"></i>View Mode';
                toggleBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                toggleBtn.classList.add('bg-slate-600', 'hover:bg-slate-700');
            }
        }

        $(document).ready(function() {
            var escapeHtml = function(s) { return (s + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); };
            $('.delete-attachment-btn').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Remove attachment?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove'
                }).then(function(result) {
                    if (result.isConfirmed) form.submit();
                });
            });

            $('.delete-preauth-btn').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete this pre-authorization?',
                    text: "The case status will be set to 'Deleted' and hidden.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Initialize searchable dropdowns for Preauth Quick Edit
            if (document.getElementById('edit-mode-section')) {
                var hnInput = $('#edit-mode-section #hn');
                var nameInput = $('#edit-mode-section #patient_name');
                var url = hnInput.data('get-info-url');
                var csrf = $('meta[name="csrf-token"]').attr('content');

                hnInput.on('blur', function() {
                    var hn = $(this).val().trim();
                    if (!hn) {
                        nameInput.val('');
                        $('#hn_loading').addClass('hidden');
                        return;
                    }
                    $('#hn_loading').removeClass('hidden');
                    axios.post(url, { hn: hn }, {
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                    })
                    .then(function(res) {
                        if (res.data.status === 'success' && res.data.data && res.data.data.name) {
                            nameInput.val(res.data.data.name);
                        } else {
                            nameInput.val('');
                        }
                    })
                    .catch(function() { nameInput.val(''); })
                    .finally(function() { $('#hn_loading').addClass('hidden'); });
                });

                initSearchAdd({
                    dataElId: 'payor_data',
                    searchInputId: 'payor_search',
                    resultsDivId: 'payor_results',
                    selectedDivId: 'payor_selected',
                    nameAttr: 'provider_id',
                    chipClass: 'payor-chip',
                    removeBtnClass: 'payor-remove'
                });

                initSearchAdd({
                    dataElId: 'notifier_data',
                    searchInputId: 'notifier_search',
                    resultsDivId: 'notifier_results',
                    selectedDivId: 'notifier_selected',
                    nameAttr: 'notifier_id',
                    chipClass: 'notifier-chip',
                    removeBtnClass: 'notifier-remove'
                });

                initSearchAdd({
                    dataElId: 'handling_staffs_data',
                    searchInputId: 'handling_staff_search',
                    resultsDivId: 'handling_staff_results',
                    selectedDivId: 'handling_staff_selected',
                    nameAttr: 'handling_staffs[]',
                    chipClass: 'handling-staff-chip',
                    removeBtnClass: 'handling-staff-remove',
                    searchByUserid: true,
                    showUserid: true
                });
            }

            (function() {
                var input = document.getElementById('preauth_gop_attachments');
                var listEl = document.getElementById('preauth-gop-file-list');
                var dropZone = document.getElementById('preauth-gop-drop-zone');
                if (!input || !listEl || !dropZone) return;
                var gopFileList = [];
                function isImage(file) {
                    var ext = (file.name.split('.').pop() || '').toLowerCase();
                    return ['jpg','jpeg','png','gif','bmp','webp'].indexOf(ext) !== -1;
                }
                function isPdf(file) {
                    return (file.name.split('.').pop() || '').toLowerCase() === 'pdf';
                }
                function syncInput() {
                    var dt = new DataTransfer();
                    for (var i = 0; i < gopFileList.length; i++) dt.items.add(gopFileList[i]);
                    input.files = dt.files;
                }
                function renderFileList() {
                    listEl.querySelectorAll('img[src^="blob:"]').forEach(function(img) { URL.revokeObjectURL(img.src); });
                    var html = '';
                    for (var i = 0; i < gopFileList.length; i++) {
                        var file = gopFileList[i];
                        var preview = '';
                        if (isImage(file)) {
                            var url = URL.createObjectURL(file);
                            preview = '<img src="' + url + '" alt="" class="h-16 w-full object-cover rounded-t-lg">';
                        } else if (isPdf(file)) {
                            preview = '<div class="flex h-16 w-full items-center justify-center rounded-t-lg bg-slate-200 dark:bg-slate-700"><i class="fa-solid fa-file-pdf text-2xl text-red-600"></i></div>';
                        } else {
                            preview = '<div class="flex h-16 w-full items-center justify-center rounded-t-lg bg-slate-200 dark:bg-slate-700"><i class="fa-solid fa-file text-2xl text-slate-400"></i></div>';
                        }
                        html += '<li class="gop-file-item rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800" data-idx="' + i + '">' +
                            '<div class="relative">' + preview +
                            '<button type="button" class="gop-file-remove absolute right-1 top-1 rounded-full bg-red-500 p-1 text-white hover:bg-red-600" aria-label="Remove"><i class="fa-solid fa-times text-xs"></i></button></div>' +
                            '<p class="truncate px-2 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300" title="' + escapeHtml(file.name) + '">' + escapeHtml(file.name) + '</p></li>';
                    }
                    listEl.innerHTML = html;
                }
                function addFiles(files) {
                    for (var i = 0; i < files.length; i++) {
                        if (files[i].size <= 1024 * 10240) gopFileList.push(files[i]);
                    }
                    syncInput();
                    renderFileList();
                }
                dropZone.addEventListener('click', function() { input.click(); });
                dropZone.addEventListener('dragover', function(e) { e.preventDefault(); dropZone.classList.add('border-emerald-500'); });
                dropZone.addEventListener('dragleave', function() { dropZone.classList.remove('border-emerald-500'); });
                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropZone.classList.remove('border-emerald-500');
                    if (e.dataTransfer.files.length) addFiles(e.dataTransfer.files);
                });
                input.addEventListener('change', function() {
                    if (this.files.length) addFiles(this.files);
                });
                $(listEl).on('click', '.gop-file-remove', function(e) {
                    e.preventDefault();
                    var idx = parseInt($(this).closest('.gop-file-item').data('idx'), 10);
                    gopFileList.splice(idx, 1);
                    syncInput();
                    renderFileList();
                });
            })();
        });

        function viewPreauthFile(fileUrl, filename) {
            const fileContent = document.getElementById('preauthFileContent');
            const modal = document.getElementById('preauthFileModal');
            fileContent.innerHTML = '<div class="flex flex-col items-center justify-center h-full text-slate-500 dark:text-slate-400"><i class="fa-solid fa-spinner fa-spin text-3xl mb-4"></i><p class="text-lg">Loading file...</p></div>';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            $('#preauthFileUrl').val(fileUrl);
            var ext = filename.split('.').pop().toLowerCase();
            if (['pdf'].includes(ext)) {
                fileContent.innerHTML = '<iframe src="' + fileUrl + '" class="w-full h-full border-0 rounded-lg shadow-lg" style="min-height: calc(100vh - 120px);"></iframe>';
            } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                fileContent.innerHTML = '<div class="w-full h-full flex items-center justify-center"><img src="' + fileUrl + '" class="max-w-full max-h-full object-contain rounded-lg shadow-lg" alt="' + filename + '" style="max-height: calc(100vh - 120px);"></div>';
            } else {
                fileContent.innerHTML = '<div class="flex flex-col items-center justify-center h-full text-center"><div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg max-w-md"><i class="fa-solid fa-file text-6xl text-slate-400 mb-6"></i><h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">' + filename + '</h3><p class="text-slate-600 dark:text-slate-300 mb-6">This file type cannot be previewed in the browser.</p><a href="' + fileUrl + '" target="_blank" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"><i class="fa-solid fa-download mr-2"></i>Download File</a></div></div>';
            }
        }
        function downloadPreauthFile() {
            var url = $('#preauthFileUrl').val();
            if (url) window.open(url, '_blank');
        }
        function closePreauthFileModal() {
            document.getElementById('preauthFileModal').classList.add('hidden');
            document.getElementById('preauthFileModal').classList.remove('flex');
            document.getElementById('preauthFileContent').innerHTML = '';
        }
    </script>
@endpush
