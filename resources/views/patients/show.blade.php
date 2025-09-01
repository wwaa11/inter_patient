@extends("layouts.management")

@section("title", "Patient Details")

@section("content")
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Patient Details</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">View patient information for {{ $patient->name }}</p>
            </div>
            <div class="mt-4 flex space-x-2 sm:mt-0">
                <a class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route("patients.edit", $patient->hn) }}">
                    <i class="fa-solid fa-edit mr-2"></i>
                    Edit Patient
                </a>
                <form class="inline" method="POST" action="{{ route("patients.destroy", $patient->hn) }}" onsubmit="return confirm('Are you sure you want to delete this patient?')">
                    @csrf
                    <button class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" type="submit">
                        <i class="fa-solid fa-trash mr-2"></i>
                        Delete Patient
                    </button>
                </form>
                <a class="inline-flex items-center rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2" href="{{ route("patients.index") }}">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Back to Patients
                </a>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Patient Profile Card -->
            <div class="lg:col-span-1">
                <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                    <div class="px-6 py-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-r from-emerald-500 to-teal-500">
                                <span class="text-2xl font-bold text-white">{{ substr($patient->name, 0, 2) }}</span>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $patient->name }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Hospital Number: {{ $patient->hn }}</p>
                            @if ($patient->qid)
                                <p class="text-sm text-slate-500 dark:text-slate-400">QID: {{ $patient->qid }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient Details -->
            <div class="lg:col-span-2">
                <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                    <div class="px-6 py-6">
                        <h3 class="mb-6 text-lg font-medium text-slate-900 dark:text-white">Patient Information</h3>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Full Name</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $patient->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hospital Number (HN)</label>
                                <p class="mt-1 font-mono text-sm text-slate-900 dark:text-white">{{ $patient->hn }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gender</label>
                                <p class="mt-1">
                                    <span class="{{ $patient->gender === "Male" ? "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" : ($patient->gender === "Female" ? "bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200" : "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200") }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                        {{ $patient->gender }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Birthday</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">
                                    {{ $patient->birthday ? \Carbon\Carbon::parse($patient->birthday)->format("M d, Y") : "N/A" }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">QID</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $patient->qid ?? "N/A" }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nationality</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $patient->nationality ?? "N/A" }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Type</label>
                                <p class="mt-1">
                                    <span class="{{ $patient->type === "OPD" ? "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200" : ($patient->type === "IPD" ? "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200" : "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200") }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                        {{ $patient->type ?? "Unknown" }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Location</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $patient->location ?? "N/A" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Patient Notes -->
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                <div class="px-6 py-6">
                    <h3 class="mb-4 text-lg font-medium text-slate-900 dark:text-white">Patient Notes</h3>
                    @if ($patient->notes && $patient->notes->count() > 0)
                        <div class="space-y-3">
                            @foreach ($patient->notes as $note)
                                <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700">
                                    <p class="text-sm text-slate-900 dark:text-white">{{ $note->note }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $note->created_at->format("M d, Y H:i") }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">No notes available for this patient.</p>
                    @endif
                </div>
            </div>

            <!-- Medical Records -->
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                <div class="px-6 py-6">
                    <h3 class="mb-4 text-lg font-medium text-slate-900 dark:text-white">Medical Records</h3>
                    @if ($patient->medicalRecords && $patient->medicalRecords->count() > 0)
                        <div class="space-y-3">
                            @foreach ($patient->medicalRecords as $record)
                                <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700">
                                    <p class="text-sm text-slate-900 dark:text-white">{{ $record->file }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $record->date->format("M d, Y") }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">No medical records available for this patient.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
