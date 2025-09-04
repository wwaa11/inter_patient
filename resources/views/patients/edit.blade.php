@extends("layouts.app")

@section("title", "Edit Patient")

@section("content")
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 dark:from-slate-900 dark:to-slate-800">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session("error"))
                <div class="animate-fade-in mb-8 rounded-xl bg-red-50 p-6 shadow-lg ring-1 ring-red-200 dark:bg-red-900/20 dark:ring-red-800">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/50">
                                <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Error</h3>
                            <p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ session("error") }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col items-start justify-between space-y-4 sm:flex-row sm:items-center sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 shadow-lg">
                            <i class="fa-solid fa-user-edit text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Edit Patient</h1>
                            <p class="mt-1 text-slate-600 dark:text-slate-400">Update information for <span class="font-semibold text-slate-900 dark:text-white">{{ $patient->name }}</span></p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a class="inline-flex items-center rounded-xl bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-lg ring-1 ring-slate-200 transition-all duration-200 hover:bg-slate-50 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-slate-800 dark:text-slate-200 dark:ring-slate-700 dark:hover:bg-slate-700" href="{{ route("patients.view", $patient->hn) }}">
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            Back to Patient
                        </a>
                    </div>
                </div>
            </div>

            <!-- Patient Form -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 dark:from-blue-900/20 dark:to-indigo-900/20">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Patient Information</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Update the patient details below. Fields marked with an asterisk (*) are required.</p>
                </div>

                <form id="patientForm" method="POST" action="{{ route("patients.update", $patient->hn) }}">
                    @csrf
                    <div class="px-8 py-8">
                        <div class="space-y-8">
                            <!-- Primary Identifiers Section -->
                            <div class="rounded-xl bg-slate-50 p-6 dark:bg-slate-700/50">
                                <h3 class="mb-4 flex items-center text-lg font-semibold text-slate-900 dark:text-white">
                                    <i class="fa-solid fa-id-card mr-2 text-blue-600"></i>
                                    Primary Identifiers
                                </h3>
                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-hospital mr-2 text-blue-600"></i>
                                            Hospital Number (HN)
                                        </label>
                                        <input class="block w-full rounded-xl border-0 bg-gray-100 px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="hn" readonly type="text" name="hn" required value="{{ old("hn", $patient->hn) }}" placeholder="Enter hospital number">
                                        @error("hn")
                                            <div class="mt-2 flex items-center text-sm text-red-600">
                                                <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-id-badge mr-2 text-blue-600"></i>
                                            QID
                                        </label>
                                        <input class="block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="qid" type="text" name="qid" value="{{ old("qid", $patient->qid) }}" placeholder="Enter QID">
                                        @error("qid")
                                            <div class="mt-2 flex items-center text-sm text-red-600">
                                                <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information Section -->
                            <div class="rounded-xl bg-slate-50 p-6 dark:bg-slate-700/50">
                                <h3 class="mb-4 flex items-center text-lg font-semibold text-slate-900 dark:text-white">
                                    <i class="fa-solid fa-user mr-2 text-blue-600"></i>
                                    Personal Information
                                </h3>
                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-signature mr-2 text-blue-600"></i>
                                            Full Name *
                                        </label>
                                        <input class="block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="name" type="text" name="name" required value="{{ old("name", $patient->name) }}" placeholder="Enter patient's full name">
                                        @error("name")
                                            <div class="mt-2 flex items-center text-sm text-red-600">
                                                <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                        <div class="space-y-2">
                                            <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                                <i class="fa-solid fa-venus-mars mr-2 text-blue-600"></i>
                                                Gender *
                                            </label>
                                            <select class="block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="gender" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old("gender", $patient->gender) == "Male" ? "selected" : "" }}>Male</option>
                                                <option value="Female" {{ old("gender", $patient->gender) == "Female" ? "selected" : "" }}>Female</option>
                                            </select>
                                            @error("gender")
                                                <div class="mt-2 flex items-center text-sm text-red-600">
                                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                                <i class="fa-solid fa-calendar mr-2 text-blue-600"></i>
                                                Birthday *
                                            </label>
                                            <input class="block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="birthday" type="date" name="birthday" required value="{{ old("birthday", $patient->birthday ? \Carbon\Carbon::parse($patient->birthday)->format("Y-m-d") : "") }}">
                                            @error("birthday")
                                                <div class="mt-2 flex items-center text-sm text-red-600">
                                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Information Section -->
                            <div class="rounded-xl bg-slate-50 p-6 dark:bg-slate-700/50">
                                <h3 class="mb-4 flex items-center text-lg font-semibold text-slate-900 dark:text-white">
                                    <i class="fa-solid fa-stethoscope mr-2 text-blue-600"></i>
                                    Medical Information
                                </h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                        <div class="space-y-2">
                                            <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                                <i class="fa-solid fa-flag mr-2 text-blue-600"></i>
                                                Nationality
                                            </label>
                                            <input class="block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="nationality" type="text" name="nationality" value="{{ old("nationality", $patient->nationality) }}" placeholder="Enter nationality">
                                            @error("nationality")
                                                <div class="mt-2 flex items-center text-sm text-red-600">
                                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                                <i class="fa-solid fa-clipboard-list mr-2 text-blue-600"></i>
                                                Patient Type
                                            </label>
                                            <select class="block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="type" name="type">
                                                <option value="">Select Type</option>
                                                <option value="OPD" {{ old("type", $patient->type) == "OPD" ? "selected" : "" }}>OPD (Outpatient)</option>
                                                <option value="IPD" {{ old("type", $patient->type) == "IPD" ? "selected" : "" }}>IPD (Inpatient)</option>
                                            </select>
                                            @error("type")
                                                <div class="mt-2 flex items-center text-sm text-red-600">
                                                    <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                            <i class="fa-solid fa-map-marker-alt mr-2 text-blue-600"></i>
                                            Location
                                        </label>
                                        <input class="block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white dark:ring-slate-600 dark:focus:ring-blue-400" id="location" type="text" name="location" value="{{ old("location", $patient->location) }}" placeholder="Enter location or department">
                                        @error("location")
                                            <div class="mt-2 flex items-center text-sm text-red-600">
                                                <i class="fa-solid fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 dark:from-slate-700/50 dark:to-slate-800/50">
                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <a class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-slate-500 dark:bg-slate-600 dark:text-slate-200 dark:hover:bg-slate-500" href="{{ route("patients.view", $patient->hn) }}">
                                <i class="fa-solid fa-times mr-2"></i>
                                Cancel
                            </a>
                            <button class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="submitBtn" type="submit">
                                <i class="fa-solid fa-save mr-2"></i>
                                <span id="submitText">Update Patient</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        // Form submission with enhanced loading state
        $('#patientForm').on('submit', function(e) {
            const submitBtn = $('#submitBtn');
            const submitText = $('#submitText');

            // Show loading state with animation
            submitBtn.prop('disabled', true);
            submitBtn.removeClass('from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700');
            submitBtn.addClass('from-slate-400 to-slate-500');
            submitText.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Updating Patient...');
        });

        // Add custom CSS for animations
        $('<style>').text(`
            .animate-fade-in {
                animation: fadeIn 0.5s ease-in-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .form-field-success {
                transition: all 0.3s ease;
            }
        `).appendTo('head');
    </script>
@endpush
