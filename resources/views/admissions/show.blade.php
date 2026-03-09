@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('admissions.index') }}"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Admission</h1>
                        <p class="mt-1 text-slate-600 dark:text-slate-400">HN {{ $admission->hn }} —
                            {{ $admission->name ?? '—' }}</p>
                    </div>
                    @if (auth()->user()->isAdmin())
                        <div class="ml-auto flex flex-wrap items-center gap-3">
                            <button type="button" onclick="toggleEditMode()" id="edit-toggle-btn"
                                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-blue-700">
                                <i class="fa-solid fa-pen-to-square mr-2"></i>
                                Quick Edit
                            </button>
                            <form action="{{ route('admissions.destroy', $admission) }}" method="POST"
                                class="inline delete-admission-form">
                                @csrf
                                <button type="button"
                                    class="delete-admission-btn inline-flex items-center rounded-lg border border-red-200 bg-white px-4 py-2 font-medium text-red-700 shadow-sm hover:bg-red-50 dark:border-red-800 dark:bg-slate-700 dark:text-red-400 dark:hover:bg-red-900/20">
                                    <i class="fa-solid fa-trash mr-2"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            @if (session('success') || session('error'))
                <script>
                    window.onload = function() {
                        Swal.fire({
                            icon: '{{ session('success') ? 'success' : 'error' }}',
                            title: '{{ session('success') ?: session('error') }}',
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
                <div class="space-y-8">
                    <!-- Patient Info Section -->
                    <div
                        class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div
                            class="border-b border-slate-100 bg-slate-50/50 px-6 py-3 dark:border-slate-700 dark:bg-slate-800/50">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Patient Info</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-5">
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        HN</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $admission->hn }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Name</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->name ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Room No</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->room_no ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Admission Date</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->admission_date?->format('Y-m-d') ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Discharge Date</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->discharge_date?->format('Y-m-d') ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Information Section -->
                    <div
                        class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div
                            class="border-b border-slate-100 bg-slate-50/50 px-6 py-3 dark:border-slate-700 dark:bg-slate-800/50">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Diagnosis</dt>
                                    <dd
                                        class="mt-1 text-sm text-slate-900 dark:text-white whitespace-pre-wrap rounded-lg bg-slate-50 p-3 dark:bg-slate-900/50">
                                        {{ $admission->diagnosis ?: '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Procedure / Treatment</dt>
                                    <dd
                                        class="mt-1 text-sm text-slate-900 dark:text-white whitespace-pre-wrap rounded-lg bg-slate-50 p-3 dark:bg-slate-900/50">
                                        {{ $admission->procedure_treatment ?: '—' }}</dd>
                                </div>
                            </div>
                            <div class="mt-6">
                                <dt
                                    class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Additional Note</dt>
                                <dd
                                    class="mt-1 text-sm text-slate-900 dark:text-white whitespace-pre-wrap rounded-lg bg-slate-50 p-3 dark:bg-slate-900/50">
                                    {{ $admission->additional_note ?: '—' }}</dd>
                            </div>
                            <dl class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-4">
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Departments</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->department ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Admitting Status</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->admitting_status ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Sent-Out Date</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->sent_out_date?->format('Y-m-d') ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Case Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                                            {{ $admission->case_status ?? '—' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Handling User</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->handlingUsers->pluck('name')->join(', ') ?: '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- GOP Section -->
                    <div
                        class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div
                            class="border-b border-slate-100 bg-slate-50/50 px-6 py-3 dark:border-slate-700 dark:bg-slate-800/50">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">GOP</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="sm:col-span-2 lg:col-span-4">
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Contact Provider</dt>
                                    <dd class="mt-2">
                                        @if ($admission->contactProviders->isEmpty())
                                            <span class="text-sm text-slate-400">—</span>
                                        @else
                                            <div class="flex flex-wrap gap-3">
                                                @foreach ($admission->contactProviders as $provider)
                                                    <div
                                                        class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-3 transition-all hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900/50 dark:hover:bg-slate-900">
                                                        <div
                                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm dark:bg-slate-800">
                                                            <img src="{{ asset('provider-logos/' . $provider->logo) }}"
                                                                alt="Provider Image" class="h-8 w-8 rounded-full">
                                                        </div>
                                                        <div class="min-w-0">
                                                            <div
                                                                class="truncate text-sm font-bold text-slate-900 dark:text-white">
                                                                {{ $provider->name }}
                                                            </div>
                                                            <div
                                                                class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
                                                                <span
                                                                    class="font-medium uppercase tracking-tight">{{ $provider->type ?: 'General' }}</span>
                                                                @if ($provider->detail)
                                                                    <span
                                                                        class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                                                    <span class="truncate">{{ $provider->detail }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        GOP Status</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->gop_pre_certification_status ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Initial Receiving Date</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->initial_gop_receiving_date?->format('Y-m-d') ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Final GOP</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->final_gop?->format('Y-m-d H:i') ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        GOP Ref</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->gop_ref ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                        Translated By</dt>
                                    <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $admission->gopTranslators->pluck('name')->join(', ') ?: '—' }}</dd>
                                </div>
                                @if ($admission->preAuthorization)
                                    <div>
                                        <dt
                                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                            Pre-auth Case</dt>
                                        <dd class="mt-1 text-sm">
                                            <a href="{{ route('preauth.show', $admission->preAuthorization) }}"
                                                class="text-emerald-600 hover:underline dark:text-emerald-400">
                                                #{{ $admission->preAuthorization->id }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                <div id="edit-mode-section"
                    class="hidden mb-8 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div
                        class="border-b border-slate-200 px-6 py-4 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Edit Admission</h2>
                        <button type="button" onclick="toggleEditMode()"
                            class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                    <form action="{{ route('admissions.update', $admission) }}" method="POST"
                        enctype="multipart/form-data" class="p-6 space-y-8">
                        @csrf
                        @php
                            $contactProviderIds = old(
                                'contact_providers',
                                $admission->contactProviders->pluck('id')->toArray(),
                            );
                            $handlingUserIds = old('handling_users', $admission->handlingUsers->pluck('id')->toArray());
                            $gopTranslatorIds = old(
                                'gop_translators',
                                $admission->gopTranslators->pluck('id')->toArray(),
                            );
                        @endphp
                        <div class="space-y-8">
                            <!-- Patient Info Section -->
                            <div
                                class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900/50">
                                <h3
                                    class="mb-4 text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Patient Info</h3>
                                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label for="hn"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">HN <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative mt-1">
                                            <input type="text" name="hn" id="hn"
                                                value="{{ old('hn', $admission->hn) }}"
                                                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 pr-10 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm"
                                                data-get-info-url="{{ route('patients.getInfo') }}" required>
                                            <div id="admission_hn_loading"
                                                class="absolute inset-y-0 right-0 hidden items-center pr-3">
                                                <svg class="h-4 w-4 animate-spin text-emerald-500"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
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
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name', $admission->name) }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="room_no"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Room
                                            No</label>
                                        <input type="text" name="room_no" id="room_no"
                                            value="{{ old('room_no', $admission->room_no) }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('room_no')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="admission_date"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Admission
                                            Date <span class="text-red-500">*</span></label>
                                        <input type="date" name="admission_date" id="admission_date"
                                            value="{{ old('admission_date', $admission->admission_date?->format('Y-m-d')) }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('admission_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="discharge_date"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Discharge
                                            Date</label>
                                        <input type="date" name="discharge_date" id="discharge_date"
                                            value="{{ old('discharge_date', $admission->discharge_date?->format('Y-m-d')) }}"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        @error('discharge_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Information Section -->
                            <div
                                class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900/50">
                                <h3
                                    class="mb-4 text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Information</h3>
                                <div class="grid gap-6">
                                    <div class="grid gap-6 sm:grid-cols-2">
                                        <div>
                                            <label for="diagnosis"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Diagnosis</label>
                                            <textarea name="diagnosis" id="diagnosis" rows="5"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('diagnosis', $admission->diagnosis) }}</textarea>
                                            @error('diagnosis')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="procedure_treatment"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Procedure
                                                / Treatment</label>
                                            <textarea name="procedure_treatment" id="procedure_treatment" rows="5"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('procedure_treatment', $admission->procedure_treatment) }}</textarea>
                                            @error('procedure_treatment')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label for="additional_note"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Additional
                                            Note</label>
                                        <textarea name="additional_note" id="additional_note" rows="3"
                                            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('additional_note', $admission->additional_note) }}</textarea>
                                        @error('additional_note')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                        <div>
                                            <label for="department"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Departments</label>
                                            <select name="department" id="department"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                                <option value="">—</option>
                                                @foreach (\App\Models\Admission::departmentOptions() as $opt)
                                                    <option value="{{ $opt }}"
                                                        {{ old('department', $admission->department) === $opt ? 'selected' : '' }}>
                                                        {{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="admitting_status"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Admitting
                                                Status</label>
                                            <select name="admitting_status" id="admitting_status"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                                <option value="">—</option>
                                                @foreach (\App\Models\Admission::admittingStatusOptions() as $opt)
                                                    <option value="{{ $opt }}"
                                                        {{ old('admitting_status', $admission->admitting_status) === $opt ? 'selected' : '' }}>
                                                        {{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="sent_out_date"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Sent-Out
                                                Date</label>
                                            <input type="date" name="sent_out_date" id="sent_out_date"
                                                value="{{ old('sent_out_date', $admission->sent_out_date?->format('Y-m-d')) }}"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="case_status"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Case
                                                Status <span class="text-red-500">*</span></label>
                                            <select name="case_status" id="case_status"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                                <option value="">—</option>
                                                @foreach (\App\Models\Admission::caseStatusOptions() as $opt)
                                                    <option value="{{ $opt }}"
                                                        {{ old('case_status', $admission->case_status) === $opt ? 'selected' : '' }}>
                                                        {{ $opt }}</option>
                                                @endforeach
                                            </select>
                                            @error('case_status')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="relative sm:col-span-2">
                                            <label for="handling_user_search"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Handling
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
                                                    @if (in_array($u->id, $handlingUserIds))
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
                                        </div>
                                    </div>
                                    <script type="application/json" id="handling_users_data">{!! json_encode($adminUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'userid' => $u->userid])->values()) !!}</script>
                                </div>
                            </div>

                            <!-- GOP Section -->
                            <div
                                class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900/50">
                                <h3
                                    class="mb-4 text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    GOP</h3>
                                <div class="grid gap-6">
                                    <div class="relative sm:col-span-2 lg:col-span-3">
                                        <label for="contact_provider_search"
                                            class="block text-sm font-medium text-slate-700 dark:text-slate-300">Contact
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
                                                @if (in_array($p->id, $contactProviderIds))
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
                                    </div>
                                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 sm:col-span-2 lg:col-span-3">
                                        <div>
                                            <label for="gop_pre_certification_status"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP/Pre-Certification
                                                Status</label>
                                            <select name="gop_pre_certification_status" id="gop_pre_certification_status"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                                <option value="">—</option>
                                                @foreach (\App\Models\Admission::gopPreCertificationStatusOptions() as $opt)
                                                    <option value="{{ $opt }}"
                                                        {{ old('gop_pre_certification_status', $admission->gop_pre_certification_status) === $opt ? 'selected' : '' }}>
                                                        {{ $opt }}</option>
                                                @endforeach
                                            </select>
                                            @error('gop_pre_certification_status')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="initial_gop_receiving_date"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Initial
                                                GOP Receiving Date</label>
                                            <input type="date" name="initial_gop_receiving_date"
                                                id="initial_gop_receiving_date"
                                                value="{{ old('initial_gop_receiving_date', $admission->initial_gop_receiving_date?->format('Y-m-d')) }}"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            @error('initial_gop_receiving_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="final_gop"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">Final
                                                GOP</label>
                                            <input type="datetime-local" name="final_gop" id="final_gop"
                                                value="{{ old('final_gop', $admission->final_gop ? $admission->final_gop->format('Y-m-d\TH:i') : '') }}"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            @error('final_gop')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="gop_ref"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP
                                                Ref</label>
                                            <input type="text" name="gop_ref" id="gop_ref"
                                                value="{{ old('gop_ref', $admission->gop_ref) }}"
                                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                            @error('gop_ref')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="relative">
                                            <label for="gop_translator_search"
                                                class="block text-sm font-medium text-slate-700 dark:text-slate-300">GOP
                                                Translated By</label>
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
                                                    @if (in_array($u->id, $gopTranslatorIds))
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
                                        </div>
                                    </div>
                                </div>
                                <script type="application/json" id="gop_translators_data">{!! json_encode($adminUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'userid' => $u->userid])->values()) !!}</script>
                                <script type="application/json" id="contact_providers_data">{!! json_encode($providers->map(fn($p) => ['id' => $p->id, 'name' => $p->name])->values()) !!}</script>
                            </div>
                        </div>
                        <script type="application/json" id="contact_providers_data">{!! json_encode($providers->map(fn($p) => ['id' => $p->id, 'name' => $p->name])->values()) !!}</script>
                        <button type="button" onclick="toggleEditMode()" id="cancel-update-btn"
                            class="hidden px-4 py-2 font-medium text-slate-600 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">Cancel</button>
                        <button type="submit" id="update-admission-btn"
                            class="hidden inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2 font-medium text-white shadow-sm hover:bg-emerald-700">Update
                            Admission</button>
                    </form>
                </div>
            @endif

            <!-- Attachments Section -->
            <div
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 mt-8">
                <h2 class="mb-6 text-xl font-bold text-slate-900 dark:text-white">Attachments</h2>
                @if ($admission->attachments->isEmpty())
                    <p class="text-slate-500 dark:text-slate-400">No attachments.</p>
                @else
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
                        @foreach ($admission->attachments as $att)
                            @php
                                $isPublicPath = str_starts_with($att->path ?? '', 'hn/');
                                $viewUrl = $isPublicPath
                                    ? asset($att->path)
                                    : route('admissions.attachments.view', [$admission, $att]);
                                $downloadUrl = $isPublicPath
                                    ? asset($att->path)
                                    : route('admissions.attachments.download', [$admission, $att]);
                                $ext = strtolower(pathinfo($att->original_name, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'], true);
                                $isPdf = $ext === 'pdf';
                            @endphp
                            <div
                                class="group flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-slate-50 dark:border-slate-600 dark:bg-slate-900 transition-all hover:shadow-md">
                                <button type="button"
                                    class="relative block aspect-[4/3] w-full shrink-0 overflow-hidden bg-slate-200 dark:bg-slate-700 focus:outline-none"
                                    onclick="viewAdmissionFile('{{ e($viewUrl) }}', {{ json_encode($att->original_name) }})">
                                    @if ($isImage)
                                        <img src="{{ $viewUrl }}" alt="{{ $att->original_name }}"
                                            class="h-full w-full object-cover transition duration-300 group-hover:scale-110">
                                    @elseif ($isPdf)
                                        <div
                                            class="flex h-full w-full flex-col items-center justify-center bg-white dark:bg-slate-800">
                                            <i class="fa-solid fa-file-pdf text-4xl text-red-500"></i>
                                            <p class="mt-2 text-[10px] text-slate-400">PDF Document</p>
                                        </div>
                                    @else
                                        <div
                                            class="flex h-full w-full items-center justify-center bg-white dark:bg-slate-800">
                                            <i class="fa-solid fa-file text-4xl text-slate-400 dark:text-slate-500"></i>
                                        </div>
                                    @endif
                                    <span
                                        class="absolute inset-0 flex items-center justify-center bg-slate-900/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                        <div
                                            class="rounded-full bg-white/90 p-2 text-slate-900 shadow-sm dark:bg-slate-800/90 dark:text-white">
                                            <i class="fa-solid fa-eye text-lg"></i>
                                        </div>
                                    </span>
                                </button>
                                <div class="flex min-w-0 flex-1 flex-col justify-between p-3">
                                    <p class="truncate text-xs font-semibold text-slate-700 dark:text-slate-200"
                                        title="{{ $att->original_name }}">{{ $att->original_name }}</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <button type="button" title="View File"
                                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition-colors hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
                                                onclick="viewAdmissionFile('{{ e($viewUrl) }}', {{ json_encode($att->original_name) }})">
                                                <i class="fa-solid fa-eye text-[10px]"></i>
                                            </button>
                                            <a href="{{ $downloadUrl }}" title="Download File"
                                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-400 dark:hover:bg-slate-600"
                                                target="_blank" rel="noopener"
                                                @if ($isPublicPath) download="{{ $att->original_name }}" @endif>
                                                <i class="fa-solid fa-download text-[10px]"></i>
                                            </a>
                                        </div>
                                        @if (auth()->user()->isAdmin())
                                            <form
                                                action="{{ route('admissions.attachments.destroy', [$admission, $att]) }}"
                                                method="POST" class="inline delete-attachment-form">
                                                @csrf
                                                <button type="button" title="Delete File"
                                                    class="delete-attachment-btn flex h-7 w-7 items-center justify-center rounded-lg bg-red-50 text-red-600 transition-colors hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50">
                                                    <i class="fa-solid fa-trash text-[10px]"></i>
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
                    <form action="{{ route('admissions.attachments.store', $admission) }}" method="POST"
                        enctype="multipart/form-data" class="mt-8 border-t border-slate-100 pt-6 dark:border-slate-700">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">Add New
                                Attachment</label>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Upload PDF documents or images (Max 10MB
                                per
                                file)</p>
                        </div>
                        <div id="admission-gop-drop-zone"
                            class="group relative flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 py-10 transition-all duration-300 dark:border-slate-600 dark:bg-slate-800/50 hover:border-emerald-500 hover:bg-emerald-50/50 dark:hover:border-emerald-500 dark:hover:bg-emerald-900/20">
                            <input type="file" name="gop_attachments[]" id="admission_gop_attachments" multiple
                                accept=".pdf,image/jpeg,image/jpg,image/png,image/gif" class="hidden">
                            <div
                                class="mb-3 rounded-full bg-white p-4 shadow-sm transition-transform duration-300 group-hover:scale-110 dark:bg-slate-700">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-emerald-500"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Drop files here or click to
                                browse
                            </p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Supports PDF, JPG, PNG, GIF</p>
                        </div>
                        <ul id="admission-gop-file-list" class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4"
                            aria-live="polite"></ul>
                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition-all hover:bg-emerald-700 hover:shadow-emerald-600/30 active:scale-95">
                                <i class="fa-solid fa-upload"></i>
                                Upload Files
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- File Viewer Modal -->
            <div class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm" id="admissionFileModal">
                <div
                    class="relative flex h-full w-full flex-col rounded-lg bg-white shadow-2xl dark:bg-slate-900 mx-auto my-auto max-w-4xl max-h-[90vh]">
                    <div
                        class="flex items-center justify-between border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 p-6 shadow-sm dark:border-slate-700 dark:from-slate-800 dark:to-slate-700 rounded-t-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg">
                                <i class="fa-solid fa-file-lines text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">File Viewer</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Admission attachment</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button"
                                class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 transition-all duration-200 hover:scale-105 hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-slate-600 dark:text-slate-200 dark:hover:bg-slate-500"
                                onclick="downloadAdmissionFile()">
                                <i class="fa-solid fa-download mr-2"></i>Download
                            </button>
                            <button type="button"
                                class="inline-flex items-center rounded-xl bg-red-100 px-4 py-2.5 text-sm font-medium text-red-700 transition-all duration-200 hover:scale-105 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50"
                                onclick="closeAdmissionFileModal()">
                                <i class="fa-solid fa-times mr-2"></i>Close
                            </button>
                        </div>
                    </div>
                    <div
                        class="flex-1 overflow-hidden bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 rounded-b-lg">
                        <input id="admissionFileUrl" type="hidden" value="">
                        <div class="flex h-full w-full items-center justify-center p-8" id="admissionFileContent"></div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script>
                function toggleEditMode() {
                    var viewSection = document.getElementById('view-mode-section');
                    var editSection = document.getElementById('edit-mode-section');
                    var toggleBtn = document.getElementById('edit-toggle-btn');
                    var cancelBtn = document.getElementById('cancel-update-btn');
                    var updateBtn = document.getElementById('update-admission-btn');

                    if (editSection.classList.contains('hidden')) {
                        // Switch to edit mode
                        viewSection.classList.add('hidden');
                        editSection.classList.remove('hidden');
                        toggleBtn.innerHTML = '<i class="fa-solid fa-eye mr-2"></i>View Mode';
                        toggleBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        toggleBtn.classList.add('bg-slate-600', 'hover:bg-slate-700');
                        if (cancelBtn) cancelBtn.classList.remove('hidden');
                        if (updateBtn) updateBtn.classList.remove('hidden');
                    } else {
                        // Switch back to view mode
                        viewSection.classList.remove('hidden');
                        editSection.classList.add('hidden');
                        toggleBtn.innerHTML = '<i class="fa-solid fa-pen-to-square mr-2"></i>Quick Edit';
                        toggleBtn.classList.remove('bg-slate-600', 'hover:bg-slate-700');
                        toggleBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                        if (cancelBtn) cancelBtn.classList.add('hidden');
                        if (updateBtn) updateBtn.classList.add('hidden');
                    }
                }

                $(document).ready(function() {
                    var escapeHtml = function(s) {
                        return (s + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g,
                            '&quot;');
                    };
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
                            })
                            .then(function(result) {
                                if (result.isConfirmed) form.submit();
                            });
                    });

                    $('.delete-admission-btn').on('click', function(e) {
                        e.preventDefault();
                        var form = $(this).closest('form');
                        Swal.fire({
                            title: 'Delete this admission?',
                            text: "You won't be able to revert this!",
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

                    // Initialize searchable dropdowns for Admission Quick Edit
                    if (document.getElementById('edit-mode-section')) {
                        var hnInput = $('#edit-mode-section #hn');
                        var nameInput = $('#edit-mode-section #name');
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
                                $('#preauth_empty_input').remove();
                                $selected.find('.preauth-chip').remove();
                                if (id == null || id === '') {
                                    $selected.append(
                                        '<input type="hidden" name="pre_authorization_id" value="" id="preauth_empty_input">'
                                    );
                                    $('#hn').val('');
                                    $('#name').val('');
                                    $('.contact-provider-chip').remove(); // Clear existing contact providers
                                    window.initSearchAdd_setEmpty($('#contact_provider_selected'),
                                        'contact_providers[]');
                                    return;
                                }
                                var chip =
                                    '<span class="preauth-chip inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">' +
                                    escapeHtml(label) +
                                    ' <button type="button" class="preauth-remove rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>' +
                                    '<input type="hidden" name="pre_authorization_id" value="' + escapeHtml(String(
                                        id)) + '"></span>';
                                $selected.append(chip);

                                // Pre-fill fields from preauth
                                var selectedPreauth = list.find(function(item) {
                                    return item.id === id;
                                });
                                if (selectedPreauth) {
                                    $('#edit-mode-section #hn').val(selectedPreauth.hn);
                                    $('#edit-mode-section #name').val(selectedPreauth.patient_name);
                                    if (selectedPreauth.diagnosis) $('#edit-mode-section #diagnosis').val(
                                        selectedPreauth.diagnosis);
                                    if (selectedPreauth.procedure) $('#edit-mode-section #procedure_treatment').val(
                                        selectedPreauth.procedure);
                                    if (selectedPreauth.gop_status) $(
                                        '#edit-mode-section #gop_pre_certification_status').val(selectedPreauth
                                        .gop_status);
                                    if (selectedPreauth.init_gop_date) $(
                                        '#edit-mode-section #initial_gop_receiving_date').val(selectedPreauth
                                        .init_gop_date);
                                    if (selectedPreauth.gop_ref) $('#edit-mode-section #gop_ref').val(selectedPreauth
                                        .gop_ref);

                                    // Set Contact Provider
                                    $('.contact-provider-chip').remove(); // Clear existing contact providers
                                    window.initSearchAdd_setEmpty($('#contact_provider_selected'),
                                        'contact_providers[]');
                                    if (selectedPreauth.provider_id) {
                                        var provider = contactProvidersList.find(function(item) {
                                            return item.id === selectedPreauth.provider_id;
                                        });
                                        if (provider) {
                                            window.initSearchAdd_addItem($('#contact_provider_selected'),
                                                selectedPreauth.provider_id, provider.name, 'contact_providers[]',
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
                                    var providerMatch = (item.provider_name || '').toLowerCase().indexOf(
                                        lower) !== -1;
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
                                        escapeHtml(String(item.id)) + '" data-label="' + escapeHtml(item
                                            .label) + '">' + escapeHtml(item.label) + '</div>';
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
                                if ($selected.find('input[name="pre_authorization_id"]').length === 0) {
                                    $selected.append(
                                        '<input type="hidden" name="pre_authorization_id" value="" id="preauth_empty_input">'
                                    );
                                }
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
                        window.initSearchAdd_setEmpty = function($targetSelected, nameAttr) {
                            $targetSelected.empty();
                            $targetSelected.append('<input type="hidden" name="' + nameAttr + '" value="" id="' +
                                nameAttr.replace(/[^\w]/g, '') + '_empty_input">');
                        };

                        initSearchAdd({
                            dataElId: 'contact_providers_data',
                            searchInputId: 'contact_provider_search',
                            resultsDivId: 'contact_provider_results',
                            selectedDivId: 'contact_provider_selected',
                            nameAttr: 'contact_providers[]',
                            chipClass: 'contact-provider-chip',
                            removeBtnClass: 'contact-provider-remove'
                        });

                        initSearchAdd({
                            dataElId: 'handling_users_data',
                            searchInputId: 'handling_user_search',
                            resultsDivId: 'handling_user_results',
                            selectedDivId: 'handling_user_selected',
                            nameAttr: 'handling_users[]',
                            chipClass: 'handling-user-chip',
                            removeBtnClass: 'handling-user-remove',
                            searchByUserid: true,
                            showUserid: true
                        });

                        initSearchAdd({
                            dataElId: 'gop_translators_data',
                            searchInputId: 'gop_translator_search',
                            resultsDivId: 'gop_translator_results',
                            selectedDivId: 'gop_translator_selected',
                            nameAttr: 'gop_translators[]',
                            chipClass: 'gop-translator-chip',
                            removeBtnClass: 'gop-translator-remove',
                            searchByUserid: true,
                            showUserid: true
                        });
                    }

                    // GOP Attachment Handling
                    (function gopAttachmentManagement() {
                        var gopFileList = [];
                        var input = document.getElementById('admission_gop_attachments');
                        var listEl = document.getElementById('admission-gop-file-list');
                        var dropZone = document.getElementById('admission-gop-drop-zone');

                        if (!input || !listEl || !dropZone) return;

                        function getFileIcon(file) {
                            var ext = (file.name.split('.').pop() || '').toLowerCase();
                            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].indexOf(ext) !== -1)
                                return 'fa-image text-blue-500';
                            if (ext === 'pdf') return 'fa-file-pdf text-red-500';
                            return 'fa-file text-slate-400';
                        }

                        function syncInput() {
                            var dt = new DataTransfer();
                            for (var i = 0; i < gopFileList.length; i++) dt.items.add(gopFileList[i]);
                            input.files = dt.files;
                        }

                        function renderFileList() {
                            var html = '';
                            for (var i = 0; i < gopFileList.length; i++) {
                                var file = gopFileList[i];
                                var fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                                html += `
                            <li class="group flex items-center justify-between rounded-lg border border-slate-200 bg-white p-3 shadow-sm transition-all hover:border-emerald-300 dark:border-slate-600 dark:bg-slate-800">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-700">
                                        <i class="fa-solid ${getFileIcon(file)} text-xl"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-700 dark:text-slate-200">${(file.name)}</p>
                                        <p class="text-[10px] text-slate-400">${fileSize}</p>
                                    </div>
                                </div>
                                <button type="button" class="gop-file-remove rounded-lg p-2 text-slate-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20" data-idx="${i}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </li>`;
                            }
                            listEl.innerHTML = html;
                        }

                        function addFiles(files) {
                            for (var i = 0; i < files.length; i++) {
                                if (files[i].size <= 10 * 1024 * 1024) gopFileList.push(files[i]);
                                else Swal.fire({
                                    icon: 'warning',
                                    title: 'File too large',
                                    text: files[i].name + ' exceeds 10MB limit.'
                                });
                            }
                            syncInput();
                            renderFileList();
                        }

                        dropZone.addEventListener('click', function() {
                            input.click();
                        });
                        dropZone.addEventListener('dragover', function(e) {
                            e.preventDefault();
                            dropZone.classList.add('border-emerald-500', 'bg-emerald-50/50');
                        });
                        dropZone.addEventListener('dragleave', function() {
                            dropZone.classList.remove('border-emerald-500', 'bg-emerald-50/50');
                        });
                        dropZone.addEventListener('drop', function(e) {
                            e.preventDefault();
                            dropZone.classList.remove('border-emerald-500', 'bg-emerald-50/50');
                            if (e.dataTransfer.files.length) addFiles(e.dataTransfer.files);
                        });
                        input.addEventListener('change', function() {
                            if (this.files.length) addFiles(this.files);
                        });
                        $(listEl).on('click', '.gop-file-remove', function() {
                            var idx = parseInt($(this).data('idx'), 10);
                            gopFileList.splice(idx, 1);
                            syncInput();
                            renderFileList();
                        });
                    })();
                });

                // File Modal Functions
                function viewAdmissionFile(fileUrl, filename) {
                    var content = document.getElementById('admissionFileContent');
                    var modal = document.getElementById('admissionFileModal');
                    var urlInput = document.getElementById('admissionFileUrl');

                    content.innerHTML =
                        '<div class="flex flex-col items-center justify-center h-full text-slate-500"><i class="fa-solid fa-spinner fa-spin text-3xl mb-4"></i><p>Loading...</p></div>';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    urlInput.value = fileUrl;

                    var ext = filename.split('.').pop().toLowerCase();
                    if (ext === 'pdf') {
                        content.innerHTML =
                            `<iframe src="${fileUrl}" class="w-full h-full border-0 rounded-lg shadow-inner" style="min-height: 70vh;"></iframe>`;
                    } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                        content.innerHTML =
                            `<div class="flex items-center justify-center h-full"><img src="${fileUrl}" class="max-w-full max-h-full object-contain rounded-lg shadow-lg"></div>`;
                    } else {
                        content.innerHTML = `
                    <div class="flex flex-col items-center justify-center p-12 text-center bg-white dark:bg-slate-800 rounded-2xl shadow-xl">
                        <div class="mb-6 rounded-full bg-slate-100 p-6 dark:bg-slate-700 text-slate-400">
                             <i class="fa-solid fa-file-circle-exclamation text-5xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No Preview Available</h4>
                        <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-xs">We can't preview this file type in the browser. You can download it to view it on your device.</p>
                        <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-bold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95">
                            <i class="fa-solid fa-download"></i> Download File
                        </a>
                    </div>`;
                    }
                }

                function downloadAdmissionFile() {
                    var url = document.getElementById('admissionFileUrl').value;
                    if (url) window.open(url, '_blank');
                }

                function closeAdmissionFileModal() {
                    document.getElementById('admissionFileModal').classList.add('hidden');
                    document.getElementById('admissionFileModal').classList.remove('flex');
                    document.getElementById('admissionFileContent').innerHTML = '';
                }
            </script>
        @endpush
