@extends("layouts.app")

@section("title", "Edit Patient")

@section("content")
    <div class="space-y-6">
        <!-- Flash Messages -->
        @if (session("error"))
            <div class="rounded-md bg-red-50 p-4 dark:bg-red-900/20">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-exclamation-circle h-5 w-5 text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session("error") }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Patient</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Update patient information for {{ $patient->name }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a class="inline-flex items-center rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2" href="{{ route("patients.view", $patient->hn) }}">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Back to Patient {{ $patient->name }}
                </a>
            </div>
        </div>

        <!-- Patient Form -->
        <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
            <form id="patientForm" method="POST" action="{{ route("patients.update", $patient->hn) }}">
                @csrf
                <div class="px-6 py-6">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Hospital Number (HN) *</label>
                                <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="hn" type="text" name="hn" required value="{{ old("hn", $patient->hn) }}">
                                @error("hn")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">QID</label>
                                <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="qid" type="text" name="qid" value="{{ old("qid", $patient->qid) }}">
                                @error("qid")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Full Name *</label>
                            <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="name" type="text" name="name" required value="{{ old("name", $patient->name) }}">
                            @error("name")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gender *</label>
                                <select class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old("gender", $patient->gender) == "Male" ? "selected" : "" }}>Male</option>
                                    <option value="Female" {{ old("gender", $patient->gender) == "Female" ? "selected" : "" }}>Female</option>
                                    <option value="Other" {{ old("gender", $patient->gender) == "Other" ? "selected" : "" }}>Other</option>
                                </select>
                                @error("gender")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Birthday *</label>
                                <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="birthday" type="date" name="birthday" required value="{{ old("birthday", $patient->birthday ? \Carbon\Carbon::parse($patient->birthday)->format("Y-m-d") : "") }}">
                                @error("birthday")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nationality</label>
                                <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="nationality" type="text" name="nationality" value="{{ old("nationality", $patient->nationality) }}">
                                @error("nationality")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                                <select class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="type" name="type">
                                    <option value="">Select Type</option>
                                    <option value="OPD" {{ old("type", $patient->type) == "OPD" ? "selected" : "" }}>OPD</option>
                                    <option value="IPD" {{ old("type", $patient->type) == "IPD" ? "selected" : "" }}>IPD</option>
                                </select>
                                @error("type")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Location</label>
                            <input class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="location" type="text" name="location" value="{{ old("location", $patient->location) }}">
                            @error("location")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-3 sm:flex sm:flex-row-reverse dark:bg-slate-700">
                    <button class="inline-flex w-full justify-center rounded-md border border-transparent bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm" id="submitBtn" type="submit">
                        Update Patient
                    </button>
                    <a class="mt-3 inline-flex w-full justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-base font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm dark:border-slate-500 dark:bg-slate-600 dark:text-slate-200 dark:hover:bg-slate-500" href="{{ route("patients.index") }}">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        // Form submission with loading state
        $('#patientForm').on('submit', function(e) {
            const submitBtn = $('#submitBtn');

            // Show loading state
            submitBtn.prop('disabled', true);
            submitBtn.html('Updating...');
        });
    </script>
@endpush
