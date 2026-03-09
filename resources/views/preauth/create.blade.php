@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('preauth.index') }}"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">New Pre Authorization</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Create a new pre-authorization case</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-8 rounded-lg bg-red-50 p-4 dark:bg-red-900/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors
                                with your
                                submission</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <form action="{{ route('preauth.store') }}" method="POST" enctype="multipart/form-data" class="p-8"
                    id="preauth-form">
                    @csrf
                    <div class="space-y-8">
                        {{-- Information Section --}}
                        <div>
                            <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-slate-900 dark:text-white">
                                Information
                            </h3>
                            <div class="grid gap-6">
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="hn"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">HN</label>
                                        <input type="text" name="hn" id="hn" value="{{ old('hn') }}"
                                            required
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm"
                                            data-get-info-url="{{ route('patients.getInfo') }}">
                                        @error('hn')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="patient_name"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Patient
                                            Name</label>
                                        <div class="relative mt-1">
                                            <input type="text" name="patient_name" id="patient_name"
                                                value="{{ old('patient_name') }}" readonly
                                                class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 pr-10 text-slate-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 sm:text-sm"
                                                placeholder="Enter HN to load">
                                            <span id="hn_loading"
                                                class="pointer-events-none absolute right-3 top-1/2 hidden -translate-y-1/2"
                                                aria-hidden="true">
                                                <i class="fa-solid fa-spinner fa-spin text-slate-400"></i>
                                            </span>
                                        </div>
                                        @error('patient_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="service_type_id"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Service
                                            Type</label>
                                        <select name="service_type_id" id="service_type_id" required
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            <option value="">Select service type</option>
                                            @foreach ($serviceTypes as $st)
                                                <option value="{{ $st->id }}"
                                                    {{ old('service_type_id') == $st->id ? 'selected' : '' }}>
                                                    {{ $st->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('service_type_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="date_of_service"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Date
                                            of
                                            Service</label>
                                        <input type="date" name="date_of_service" id="date_of_service"
                                            value="{{ old('date_of_service') }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('date_of_service')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="requested_date"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Requested
                                            Date</label>
                                        <input type="datetime-local" name="requested_date" id="requested_date"
                                            value="{{ old('requested_date') }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('requested_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="send_out_date"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Send
                                            Out
                                            Date</label>
                                        <input type="date" name="send_out_date" id="send_out_date"
                                            value="{{ old('send_out_date') }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('send_out_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="relative">
                                        <label for="notifier_search"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Notifier</label>
                                        <div
                                            class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                            <input type="text" id="notifier_search"
                                                placeholder="Search and click to select..." autocomplete="off"
                                                class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                            <div id="notifier_results"
                                                class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden">
                                            </div>
                                        </div>
                                        <div id="notifier_selected" class="mt-2 flex flex-wrap gap-2">
                                            @php $oldNotifier = old('notifier_id'); @endphp
                                            @if ($oldNotifier && $notifiers->firstWhere('id', $oldNotifier))
                                                @php $n = $notifiers->firstWhere('id', $oldNotifier); @endphp
                                                <span
                                                    class="notifier-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                                    {{ $n->name }}
                                                    <button type="button"
                                                        class="notifier-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800"
                                                        aria-label="Remove">&times;</button>
                                                    <input type="hidden" name="notifier_id"
                                                        value="{{ $n->id }}">
                                                </span>
                                            @endif
                                        </div>
                                        @error('notifier_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <script type="application/json" id="notifier_data">{!! json_encode($notifiers->map(fn($n) => ['id' => $n->id, 'name' => $n->name])->values()) !!}</script>
                                    </div>

                                    @if ($adminUsers->isNotEmpty())
                                        <div class="relative">
                                            <label for="handling_staff_search"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Handling
                                                Staffs</label>
                                            <div
                                                class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                                <input type="text" id="handling_staff_search"
                                                    placeholder="Search and click to add..." autocomplete="off"
                                                    class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                                <div id="handling_staff_results"
                                                    class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden">
                                                </div>
                                            </div>
                                            <div id="handling_staff_selected" class="mt-2 flex flex-wrap gap-2">
                                                @foreach ($adminUsers as $u)
                                                    @if (in_array($u->id, old('handling_staffs', [])))
                                                        <span
                                                            class="handling-staff-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                                            {{ $u->name }}
                                                            <button type="button"
                                                                class="handling-staff-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800"
                                                                aria-label="Remove">&times;</button>
                                                            <input type="hidden" name="handling_staffs[]"
                                                                value="{{ $u->id }}">
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                            @error('handling_staffs')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <script type="application/json" id="handling_staffs_data">{!! json_encode($adminUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'userid' => $u->userid])->values()) !!}</script>
                                        </div>
                                    @endif
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="case_status"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Case
                                            Status</label>
                                        <select name="case_status" id="case_status" required
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            @foreach (\App\Models\PreAuthorization::caseStatusOptions() as $opt)
                                                <option value="{{ $opt }}"
                                                    {{ old('case_status', 'Data Entered') === $opt ? 'selected' : '' }}>
                                                    {{ $opt }}</option>
                                            @endforeach
                                        </select>
                                        @error('case_status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Provider Section --}}
                        <div>
                            <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-slate-900 dark:text-white">
                                Provider
                            </h3>
                            <div class="grid gap-6">
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="relative">
                                        <label for="payor_search"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Provider</label>
                                        <div
                                            class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                            <input type="text" id="payor_search"
                                                placeholder="Search and click to select..." autocomplete="off"
                                                class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                            <div id="payor_results"
                                                class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden">
                                            </div>
                                        </div>
                                        <div id="payor_selected" class="mt-2 flex flex-wrap gap-2">
                                            @php $oldPayor = old('provider_id'); @endphp
                                            @if ($oldPayor && $providers->firstWhere('id', $oldPayor))
                                                @php $p = $providers->firstWhere('id', $oldPayor); @endphp
                                                <span
                                                    class="payor-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                                    {{ $p->name }}
                                                    <button type="button"
                                                        class="payor-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800"
                                                        aria-label="Remove">&times;</button>
                                                    <input type="hidden" name="provider_id"
                                                        value="{{ $p->id }}">
                                                </span>
                                            @endif
                                        </div>
                                        @error('provider_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <script type="application/json" id="payor_data">{!! json_encode($providers->map(fn($p) => ['id' => $p->id, 'name' => $p->name])->values()) !!}</script>
                                    </div>
                                    <div>
                                        <label for="coverage_decision"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Coverage
                                            Decision</label>
                                        <select name="coverage_decision" id="coverage_decision"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            <option value="">—</option>
                                            @foreach (\App\Models\PreAuthorization::coverageDecisionOptions() as $opt)
                                                <option value="{{ $opt }}"
                                                    {{ old('coverage_decision') === $opt ? 'selected' : '' }}>
                                                    {{ $opt }}</option>
                                            @endforeach
                                        </select>
                                        @error('coverage_decision')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="operations_procedures"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300">Operations/Procedures</label>
                                    <textarea name="operations_procedures" id="operations_procedures" rows="4"
                                        class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('operations_procedures') }}</textarea>
                                    @error('operations_procedures')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid gap-6 sm:grid-cols-3">
                                    <div>
                                        <label for="gop_reference_number"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP
                                            Reference Number</label>
                                        <input type="text" name="gop_reference_number" id="gop_reference_number"
                                            value="{{ old('gop_reference_number') }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('gop_reference_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="gop_receiving_date"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP
                                            Receiving Date</label>
                                        <input type="date" name="gop_receiving_date" id="gop_receiving_date"
                                            value="{{ old('gop_receiving_date') }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('gop_receiving_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="gop_translate_by"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP
                                            Translate By</label>
                                        <select name="gop_translate_by" id="gop_translate_by"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            <option value="">—</option>
                                            @foreach ($adminUsers as $u)
                                                <option value="{{ $u->id }}"
                                                    {{ old('gop_translate_by') == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP
                                        Attachments (PDF or image, multiple)</label>
                                    <div id="gop-drop-zone"
                                        class="mt-1 flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 py-8 transition dark:border-slate-600 dark:bg-slate-800/50 hover:border-emerald-400 hover:bg-emerald-50/50 dark:hover:border-emerald-500 dark:hover:bg-emerald-900/20">
                                        <input type="file" name="gop_attachments[]" id="gop_attachments" multiple
                                            accept=".pdf,image/jpeg,image/jpg,image/png,image/gif" class="hidden">
                                        <i
                                            class="fa-solid fa-cloud-arrow-up mb-2 text-3xl text-slate-400 dark:text-slate-500"></i>
                                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Drop
                                            files here
                                            or click to upload</p>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">PDF, JPEG,
                                            PNG, GIF. Max
                                            10MB per file.</p>
                                    </div>
                                    <ul id="gop-file-list"
                                        class="mt-3 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"
                                        aria-live="polite"></ul>
                                    @error('gop_attachments')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('preauth.index') }}"
                            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2 font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var hnInput = $('#hn');
            var nameInput = $('#patient_name');
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
                axios.post(url, {
                        hn: hn
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) {
                        if (res.data.status === 'success' && res.data.data && res.data.data.name) {
                            nameInput.val(res.data.data.name);
                        } else {
                            nameInput.val('');
                        }
                    })
                    .catch(function() {
                        nameInput.val('');
                    })
                    .finally(function() {
                        $('#hn_loading').addClass('hidden');
                    });
            });

            // Initialize searchable dropdowns
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

            (function() {
                var gopFileList = [];
                var input = document.getElementById('gop_attachments');
                var listEl = document.getElementById('gop-file-list');
                var dropZone = document.getElementById('gop-drop-zone');
                var escapeHtml = function(s) {
                    return (s + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');
                };
                if (!input || !listEl || !dropZone) return;

                function isImage(file) {
                    var ext = (file.name.split('.').pop() || '').toLowerCase();
                    return ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].indexOf(ext) !== -1;
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
                    listEl.querySelectorAll('img[src^="blob:"]').forEach(function(img) {
                        URL.revokeObjectURL(img.src);
                    });
                    var html = '';
                    for (var i = 0; i < gopFileList.length; i++) {
                        var file = gopFileList[i];
                        var preview = '';
                        if (isImage(file)) {
                            preview = '<img src="' + URL.createObjectURL(file) +
                                '" alt="" class="h-16 w-full object-cover rounded-t-lg">';
                        } else if (isPdf(file)) {
                            preview =
                                '<div class="flex h-16 w-full items-center justify-center rounded-t-lg bg-slate-200 dark:bg-slate-700"><i class="fa-solid fa-file-pdf text-2xl text-red-600"></i></div>';
                        } else {
                            preview =
                                '<div class="flex h-16 w-full items-center justify-center rounded-t-lg bg-slate-200 dark:bg-slate-700"><i class="fa-solid fa-file text-2xl text-slate-400"></i></div>';
                        }
                        html +=
                            '<li class="gop-file-item rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800" data-idx="' +
                            i + '">' +
                            '<div class="relative">' + preview +
                            '<button type="button" class="gop-file-remove absolute right-1 top-1 rounded-full bg-red-500 p-1 text-white hover:bg-red-600" aria-label="Remove"><i class="fa-solid fa-times text-xs"></i></button></div>' +
                            '<p class="truncate px-2 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-300" title="' +
                            escapeHtml(file.name) + '">' + escapeHtml(file.name) + '</p></li>';
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
                dropZone.addEventListener('click', function() {
                    input.click();
                });
                dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropZone.classList.add('border-emerald-500');
                });
                dropZone.addEventListener('dragleave', function() {
                    dropZone.classList.remove('border-emerald-500');
                });
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
    </script>
@endpush
