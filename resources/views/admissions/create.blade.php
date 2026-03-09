@extends('layouts.app')

@section('content')
    <div class="min-h-screen">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admissions.index') }}"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">New Admission</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Create a new admission form</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 rounded-lg bg-red-50 p-4 dark:bg-red-900/30">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors with your
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

            <div>
                <form action="{{ route('admissions.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @php
                        $oldHn = old('hn', $preauth?->hn ?? '');
                        $oldName = old('name', $preauth?->patient_name ?? '');
                        $oldPreAuthId = old('pre_authorization_id', $preauth?->id ?? '');
                        $oldContactProviders = old('contact_providers', $preauth ? [$preauth->provider_id] : []);
                        $oldHandlingUsers = old('handling_users', []);
                        $oldGopTranslators = old('gop_translators', []);
                    @endphp

                    <div class="space-y-8">

                        <div
                            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <label for="preauth_search"
                                class="block text-xl font-medium text-slate-700 dark:text-slate-300">Pre-auth
                                Case</label>
                            <div
                                class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                <input type="text" id="preauth_search" placeholder="Search and click to select..."
                                    autocomplete="off"
                                    class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                <div id="preauth_results"
                                    class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden">
                                </div>
                            </div>
                            <div id="preauth_selected" class="mt-2 flex flex-wrap gap-2">
                                @php $sel = $preAuthList->firstWhere('id', (int)$oldPreAuthId); @endphp
                                @if ($sel)
                                    <span
                                        class="preauth-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                        {{ $sel['label'] }}
                                        <button type="button"
                                            class="preauth-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800"
                                            aria-label="Remove">&times;</button>
                                        <input type="hidden" name="pre_authorization_id" value="{{ $sel['id'] }}">
                                    </span>
                                @endif
                            </div>
                            @error('pre_authorization_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <script type="application/json" id="preauth_list_data">{!! json_encode($preAuthList) !!}</script>
                        </div>

                        <!-- Patient Info Section -->
                        <div
                            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <h2 class="mb-6 text-xl font-bold text-slate-900 dark:text-white">Patient Info</h2>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
                                <div>
                                    <label for="hn"
                                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300">HN <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative mt-1">
                                        <input type="text" name="hn" id="hn" value="{{ $oldHn }}"
                                            class="p-2 block w-full rounded-lg border-slate-300 pr-10 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm"
                                            data-get-info-url="{{ route('patients.getInfo') }}" required>
                                        <div id="admission_hn_loading"
                                            class="absolute inset-y-0 right-0 hidden items-center pr-3">
                                            <svg class="h-4 w-4 animate-spin text-emerald-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('hn')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="name"
                                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Name
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ $oldName }}"
                                        class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="room_no"
                                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Room
                                        No</label>
                                    <input type="text" name="room_no" id="room_no" value="{{ old('room_no') }}"
                                        class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    @error('room_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="admission_date"
                                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Admission
                                        Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="admission_date" id="admission_date"
                                        value="{{ old('admission_date', request('date_of_service', date('Y-m-d'))) }}"
                                        class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    @error('admission_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="discharge_date"
                                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Discharge
                                        Date</label>
                                    <input type="date" name="discharge_date" id="discharge_date"
                                        value="{{ old('discharge_date') }}"
                                        class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                    @error('discharge_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Information Section -->
                        <div
                            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <h2 class="mb-6 text-xl font-bold text-slate-900 dark:text-white">Information</h2>
                            <div class="grid grid-cols-1 gap-6">
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="diagnosis"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Diagnosis</label>
                                        <textarea name="diagnosis" id="diagnosis" rows="5"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('diagnosis') }}</textarea>
                                        @error('diagnosis')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="procedure_treatment"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Procedure
                                            / Treatment</label>
                                        <textarea name="procedure_treatment" id="procedure_treatment" rows="5"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('procedure_treatment', request('operations_procedures')) }}</textarea>
                                        @error('procedure_treatment')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="additional_note"
                                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Additional
                                        Note</label>
                                    <textarea name="additional_note" id="additional_note" rows="3"
                                        class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('additional_note') }}</textarea>
                                    @error('additional_note')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                    <div>
                                        <label for="department"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Departments</label>
                                        <select name="department" id="department"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            <option value="">Select Department</option>
                                            @foreach (App\Models\Admission::departmentOptions() as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('department') == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="admitting_status"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Admitting
                                            Status</label>
                                        <select name="admitting_status" id="admitting_status"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            <option value="">Select Status</option>
                                            @foreach (App\Models\Admission::admittingStatusOptions() as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('admitting_status') == $option ? 'selected' : '' }}>
                                                    {{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @error('admitting_status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="sent_out_date"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Sent-Out
                                            Date</label>
                                        <input type="date" name="sent_out_date" id="sent_out_date"
                                            value="{{ old('sent_out_date', request('send_out_date')) }}"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('sent_out_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="case_status"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Case
                                            Status <span class="text-red-500">*</span></label>
                                        <select name="case_status" id="case_status"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            <option value="">Select Status</option>
                                            @foreach (App\Models\Admission::caseStatusOptions() as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('case_status') == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('case_status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <div class="relative">
                                        <label for="handling_user_search"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Handling
                                            User</label>
                                        <div
                                            class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                            <input type="text" id="handling_user_search"
                                                placeholder="Search and click to add..." autocomplete="off"
                                                class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                            <div id="handling_user_results"
                                                class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden">
                                            </div>
                                        </div>
                                        <div id="handling_user_selected" class="mt-2 flex flex-wrap gap-2">
                                            @foreach ($adminUsers as $u)
                                                @if (in_array($u->id, $oldHandlingUsers))
                                                    <span
                                                        class="handling-user-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                                        {{ $u->name }}
                                                        <button type="button"
                                                            class="handling-user-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800"
                                                            data-id="{{ $u->id }}"
                                                            aria-label="Remove">&times;</button>
                                                        <input type="hidden" name="handling_users[]"
                                                            value="{{ $u->id }}">
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                        @error('handling_users')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <script type="application/json" id="handling_users_data">{!! json_encode($adminUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'userid' => $u->userid])->values()) !!}</script>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- GOP Section -->
                        <div
                            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <h2 class="mb-6 text-xl font-bold text-slate-900 dark:text-white">GOP</h2>
                            <div class="grid grid-cols-1 gap-6">
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                    <div>
                                        <label for="gop_pre_certification_status"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">GOP
                                            Status</label>
                                        <select name="gop_pre_certification_status" id="gop_pre_certification_status"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            <option value="">Select Status</option>
                                            @foreach (App\Models\Admission::gopPreCertificationStatusOptions() as $option)
                                                <option value="{{ $option }}"
                                                    {{ old('gop_pre_certification_status') == $option ? 'selected' : '' }}>
                                                    {{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @error('gop_pre_certification_status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="initial_gop_receiving_date"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300 font-semibold italic text-emerald-600 dark:text-emerald-400">Initial
                                            Receiving Date</label>
                                        <input type="date" name="initial_gop_receiving_date"
                                            id="initial_gop_receiving_date"
                                            value="{{ old('initial_gop_receiving_date') }}"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('initial_gop_receiving_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="final_gop"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Final
                                            GOP</label>
                                        <input type="datetime-local" name="final_gop" id="final_gop"
                                            value="{{ old('final_gop') }}"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('final_gop')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="gop_ref"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">GOP
                                            Ref</label>
                                        <input type="text" name="gop_ref" id="gop_ref"
                                            value="{{ old('gop_ref') }}"
                                            class="p-2 mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('gop_ref')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="relative">
                                        <label for="gop_translator_search"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Translated
                                            By</label>
                                        <div
                                            class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                            <input type="text" id="gop_translator_search"
                                                placeholder="Search and click to add..." autocomplete="off"
                                                class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                            <div id="gop_translator_results"
                                                class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden">
                                            </div>
                                        </div>
                                        <div id="gop_translator_selected" class="mt-2 flex flex-wrap gap-2">
                                            @foreach ($adminUsers as $u)
                                                @if (in_array($u->id, $oldGopTranslators))
                                                    <span
                                                        class="gop-translator-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                                        {{ $u->name }}
                                                        <button type="button"
                                                            class="gop-translator-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800"
                                                            data-id="{{ $u->id }}"
                                                            aria-label="Remove">&times;</button>
                                                        <input type="hidden" name="gop_translators[]"
                                                            value="{{ $u->id }}">
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                        @error('gop_translators')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <script type="application/json" id="gop_translators_data">{!! json_encode($adminUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'userid' => $u->userid])->values()) !!}</script>
                                    </div>

                                    <div class="relative">
                                        <label for="contact_provider_search"
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Contact
                                            Provider</label>
                                        <div
                                            class="searchable-dropdown mt-1 rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-600 dark:bg-slate-800">
                                            <input type="text" id="contact_provider_search"
                                                placeholder="Search and click to add..." autocomplete="off"
                                                class="block w-full rounded-t-lg border-0 bg-transparent px-3 py-2 text-slate-900 placeholder-slate-400 focus:ring-0 dark:text-white dark:placeholder-slate-500 sm:text-sm">
                                            <div id="contact_provider_results"
                                                class="max-h-48 overflow-auto border-t border-slate-200 dark:border-slate-600 hidden">
                                            </div>
                                        </div>
                                        <div id="contact_provider_selected" class="mt-2 flex flex-wrap gap-2">
                                            @foreach ($providers as $p)
                                                @if (in_array($p->id, $oldContactProviders))
                                                    <span
                                                        class="contact-provider-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                                        {{ $p->name }}
                                                        <button type="button"
                                                            class="contact-provider-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800"
                                                            data-id="{{ $p->id }}"
                                                            aria-label="Remove">&times;</button>
                                                        <input type="hidden" name="contact_providers[]"
                                                            value="{{ $p->id }}">
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                        @error('contact_providers')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <script type="application/json" id="contact_providers_data">{!! json_encode($providers->map(fn($p) => ['id' => $p->id, 'name' => $p->name])->values()) !!}</script>
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label
                                            class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Attached
                                            GOP</label>
                                        <div id="gop-drop-zone"
                                            class="mt-1 flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 py-4 transition dark:border-slate-600 dark:bg-slate-800/50 hover:border-emerald-400 hover:bg-emerald-50/50 dark:hover:border-emerald-500 dark:hover:bg-emerald-900/20">
                                            <input type="file" name="gop_attachments[]" id="gop_attachments" multiple
                                                accept=".pdf,image/jpeg,image/jpg,image/png,image/gif" class="hidden">
                                            <i
                                                class="fa-solid fa-cloud-arrow-up mb-1 text-2xl text-slate-400 dark:text-slate-500"></i>
                                            <p
                                                class="text-xs font-medium text-slate-600 dark:text-slate-400 text-center px-4">
                                                Drop files or click to upload</p>
                                        </div>
                                        <ul id="gop-file-list" class="mt-3 grid grid-cols-2 gap-3" aria-live="polite">
                                        </ul>
                                        @error('gop_attachments')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('admissions.index') }}"
                            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2 font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Create
                            Admission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var escapeHtml = function(s) {
                return (s + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                    '&quot;');
            };
            var hnInput = $('#hn');
            var nameInput = $('#name');
            var url = hnInput.data('get-info-url');
            var csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector(
                'meta[name="csrf-token"]').getAttribute('content') : '';
            hnInput.on('blur', function() {
                var hn = $(this).val().trim();
                if (!hn) {
                    nameInput.val('');
                    $('#admission_hn_loading').addClass('hidden');
                    return;
                }
                $('#admission_hn_loading').removeClass('hidden');
                axios.post(url, {
                        hn: hn
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(function(res) {
                        if (res.data.status === 'success' && res.data.data && res.data.data.name)
                            nameInput.val(res.data.data.name);
                        else nameInput.val('');
                    }).catch(function() {
                        nameInput.val('');
                    }).finally(function() {
                        $('#admission_hn_loading').addClass('hidden');
                    });
            });

            (function preauthSingleSelect() {
                var dataEl = document.getElementById('preauth_list_data');
                if (!dataEl) return;
                var list = JSON.parse(dataEl.textContent);
                var $search = $('#preauth_search');
                var $results = $('#preauth_results');
                var $selected = $('#preauth_selected');
                var contactProvidersList = JSON.parse(document.getElementById('contact_providers_data')
                    .textContent);

                function setPreauth(id, label) {
                    $selected.find('.preauth-chip').remove();
                    if (id == null || id === '') {
                        $('#hn').val('');
                        $('#name').val('');
                        // Clear existing contact providers
                        $('#contact_provider_selected').find('.contact-provider-chip').remove();
                        window.initSearchAdd_setEmpty($('#contact_provider_selected'), 'contact_providers[]');
                        return;
                    }
                    var chip =
                        '<span class="preauth-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">' +
                        escapeHtml(label) +
                        ' <button type="button" class="preauth-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>' +
                        '<input type="hidden" name="pre_authorization_id" value="' + escapeHtml(String(id)) +
                        '"></span>';
                    $selected.append(chip);

                    // Pre-fill HN, Patient Name, Contact Provider
                    var selectedPreauth = list.find(function(item) {
                        return item.id === id;
                    });
                    if (selectedPreauth) {
                        $('#hn').val(selectedPreauth.hn);
                        $('#name').val(selectedPreauth.patient_name);

                        // Set Contact Provider (single select for now, based on preauth's provider_id)
                        $('#contact_provider_selected').find('.contact-provider-chip')
                            .remove(); // Clear existing contact providers
                        window.initSearchAdd_setEmpty($('#contact_provider_selected'), 'contact_providers[]');
                        if (selectedPreauth.provider_id) {
                            var provider = contactProvidersList.find(function(item) {
                                return item.id === selectedPreauth.provider_id;
                            });
                            if (provider) {
                                window.initSearchAdd_addItem($('#contact_provider_selected'), selectedPreauth
                                    .provider_id, provider.name, 'contact_providers[]',
                                    'contact-provider-chip', 'contact-provider-remove');
                            }
                        }
                    }
                }

                function renderResults(q) {
                    var lower = (q || '').toLowerCase();
                    var filtered = list.filter(function(item) {
                        if (!lower) return true;
                        var labelMatch = (item.label || '').toLowerCase().indexOf(lower) !== -1;
                        var hnMatch = (String(item.hn || '')).toLowerCase().indexOf(lower) !== -1;
                        var providerMatch = (item.provider_name || '').toLowerCase().indexOf(lower) !==
                            -1;
                        return labelMatch || hnMatch || providerMatch;
                    });
                    if (filtered.length === 0) {
                        $results.hide().empty();
                        return;
                    }
                    var html = '';
                    filtered.forEach(function(item) {
                        html +=
                            '<div class="preauth-row cursor-pointer px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700" data-id="' +
                            escapeHtml(String(item.id)) + '" data-label="' + escapeHtml(item.label) +
                            '">' + escapeHtml(item.label) + '</div>';
                    });
                    $results.html(html).show();
                }
                $search.on('input focus', function() {
                    renderResults($(this).val());
                });
                $search.on('blur', function() {
                    setTimeout(function() {
                        $results.hide();
                    }, 200);
                });
                $results.on('click', '.preauth-row', function() {
                    setPreauth($(this).data('id'), $(this).data('label'));
                    $search.val('');
                    $results.hide();
                });
                $selected.on('click', '.preauth-remove', function(e) {
                    e.preventDefault();
                    $(this).closest('span').remove();
                    // When preauth is removed, clear HN, Name, and Contact Provider
                    $('#hn').val('');
                    $('#name').val('');
                    $('#contact_provider_selected').find('.contact-provider-chip').remove();
                    window.initSearchAdd_setEmpty($('#contact_provider_selected'),
                        'contact_providers[]');
                });
            })();

            (function() {
                var gopFileList = [];
                var input = document.getElementById('gop_attachments');
                var listEl = document.getElementById('gop-file-list');
                var dropZone = document.getElementById('gop-drop-zone');
                var accept = '.pdf,image/jpeg,image/jpg,image/png,image/gif';

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
                        var id = 'gop-file-' + i;
                        var preview = '';
                        if (isImage(file)) {
                            var url = URL.createObjectURL(file);
                            preview = '<img src="' + url +
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

            window.initSearchAdd_addItem = function($targetSelected, id, name, nameAttr, chipClass,
                removeBtnClass) {
                var chip = '<span class="' + chipClass +
                    ' inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">' +
                    escapeHtml(name) + ' <button type="button" class="' + removeBtnClass +
                    ' rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>' +
                    '<input type="hidden" name="' + nameAttr + '" value="' + escapeHtml(String(id)) + '">' +
                    '</span>';
                $targetSelected.append(chip);
            };
            // Helper for single selects or pre-filling
            window.initSearchAdd_setEmpty = function($targetSelected, nameAttr) {
                $targetSelected.empty();
                $targetSelected.append('<input type="hidden" name="' + nameAttr + '" value="" id="' + nameAttr
                    .replace(/[^\w]/g, '') + '_empty_input">');
            };

            // Initialize searchable dropdowns for Admission
            // The other initSearchAdd calls (contact_providers, handling_users, gop_translators)
            // are now handled globally in resources/js/admission-forms.js via Vite.
        });
    </script>
@endpush
