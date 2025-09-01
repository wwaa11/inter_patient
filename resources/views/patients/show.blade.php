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
                @if (auth()->user()->role === "admin")
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
                @endif
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

                            @if ($latestPassport)
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Latest Passport</label>
                                    <div class="mt-1 flex items-center gap-3">
                                        <span class="text-sm text-slate-900 dark:text-white">{{ $latestPassport->number ?? "No Number" }}</span>
                                        <span class="{{ $latestPassport->status_class }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                            {{ $latestPassport->status_text }}
                                        </span>
                                        @if ($latestPassport->expiry_date)
                                            <span class="text-xs text-slate-500 dark:text-slate-400">Expires: {{ $latestPassport->expiry_date->format("M d, Y") }}</span>
                                        @endif
                                        <button class="text-sm font-medium text-blue-600 hover:text-blue-800" onclick="viewFile('{{ $patient->hn }}', '{{ $latestPassport->file }}')">
                                            View
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Notes -->
        <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
            <div class="px-6 py-6">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white">Patient Notes</h3>
                    @if (auth()->user()->role === "admin")
                        <button class="text-blue-600 hover:text-blue-800" onclick="openAddNoteModal()">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    @endif
                </div>
                @if ($patient->notes && $patient->notes->count() > 0)
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-3">
                        @foreach ($patient->notes->sortByDesc("created_at") as $note)
                            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition-shadow hover:shadow-md dark:border-slate-600 dark:bg-slate-800">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                            <i class="fa-solid fa-sticky-note text-sm text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="whitespace-pre-wrap text-sm leading-relaxed text-slate-900 dark:text-slate-100">{{ $note->note }}</div>
                                        @if ($note->created_at)
                                            <div class="mt-3 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                                <i class="fa-solid fa-clock"></i>
                                                <span>{{ $note->created_at->format("M d, Y g:i A") }}</span>
                                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                                <span>{{ $note->created_at->diffForHumans() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    @if (auth()->user()->role === "admin")
                                        <div class="flex-shrink-0">
                                            <button class="rounded-lg bg-red-600 px-2 py-1 text-xs font-medium text-white hover:bg-red-700" onclick="deleteNote('{{ $patient->hn }}', '{{ $note->id }}')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">No notes available for this patient.</p>
                @endif
            </div>
        </div>

        <!-- Additional Information -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Patient Passports -->
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                <div class="px-6 py-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white">Passports</h3>
                        @if (auth()->user()->role === "admin")
                            <button class="text-blue-600 hover:text-blue-800" onclick="openAddPassportModal()">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        @endif
                    </div>
                    @if ($passportsWithStatus && $passportsWithStatus->count() > 0)
                        <div class="space-y-3">
                            @foreach ($passportsWithStatus as $passport)
                                <div class="{{ $passport->status_class }} rounded-lg border p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="mb-2 flex items-center gap-2">
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                                    {{ $passport->number ?? "No Number" }}
                                                    @if ($loop->first)
                                                        <span class="ml-2 text-xs text-slate-500">(Latest)</span>
                                                    @endif
                                                </p>
                                                <span class="{{ $passport->status_class }} inline-flex items-center rounded-full px-2 py-1 text-xs font-medium">
                                                    {{ $passport->status_text }}
                                                </span>
                                            </div>
                                            @if ($passport->issue_date || $passport->expiry_date)
                                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                    @if ($passport->issue_date)
                                                        Issued: {{ \Carbon\Carbon::parse($passport->issue_date)->format("M d, Y") }}
                                                    @endif
                                                    @if ($passport->expiry_date)
                                                        {{ $passport->issue_date ? " | " : "" }}Expires: {{ \Carbon\Carbon::parse($passport->expiry_date)->format("M d, Y") }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex gap-2">
                                            <button class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700" onclick="viewFile('{{ $patient->hn }}', '{{ $passport->file }}')">
                                                <i class="fa-solid fa-eye mr-1"></i>View
                                            </button>
                                            @if (auth()->user()->role === "admin")
                                                <button class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700" onclick="deletePassport('{{ $patient->hn }}', '{{ $passport->id }}')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">No passports available for this patient.</p>
                    @endif
                </div>
            </div>

            <!-- Medical Reports -->
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                <div class="px-6 py-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white">Medical Reports</h3>
                        @if (auth()->user()->role === "admin")
                            <button class="text-blue-600 hover:text-blue-800" onclick="openAddMedicalReportModal()">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        @endif
                    </div>
                    @if ($patient->medicalReports && $patient->medicalReports->count() > 0)
                        <div class="space-y-3">
                            @foreach ($patient->medicalReports as $report)
                                <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm text-slate-900 dark:text-white">{{ $report->date->format("d M Y") }}</p>
                                        </div>
                                        <div class="ml-2 flex gap-2">
                                            <button class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700" onclick="viewFile('{{ $patient->hn }}', '{{ $report->file }}')">
                                                <i class="fa-solid fa-eye mr-1"></i>View
                                            </button>
                                            @if (auth()->user()->role === "admin")
                                                <button class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700" onclick="deleteMedicalReport('{{ $patient->hn }}', '{{ $report->id }}')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400">No medical reports available for this patient.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4" id="addNoteModal">
        <div class="w-full max-w-lg transform rounded-xl bg-white shadow-2xl transition-all">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                        <i class="fa-solid fa-sticky-note text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Add Patient Note</h3>
                </div>
                <button class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" onclick="closeAddNoteModal()">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
            <!-- Form -->
            <form class="p-6" action="{{ route("patients.notes.store", $patient->hn) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="note">Note Content</label>
                    <textarea class="w-full resize-none rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" id="note" name="note" rows="5" placeholder="Enter your note here..." required></textarea>
                    <p class="mt-2 text-xs text-gray-500">Your name will be automatically added to the note.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button class="rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200" type="button" onclick="closeAddNoteModal()">
                        Cancel
                    </button>
                    <button class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" type="submit">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Passport Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4" id="addPassportModal">
        <div class="max-h-[90vh] w-full max-w-2xl transform overflow-y-auto rounded-xl bg-white shadow-2xl transition-all">
            <!-- Header -->
            <div class="sticky top-0 flex items-center justify-between rounded-t-xl border-b border-gray-200 bg-white p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                        <i class="fa-solid fa-passport text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Add New Passport</h3>
                </div>
                <button class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" onclick="closeAddPassportModal()">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
            <!-- Form -->
            <form class="p-6" action="{{ route("patients.passports.store", $patient->hn) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- File Upload -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700" for="passport_file">Passport File *</label>
                        <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center transition-colors hover:border-blue-400">
                            <i class="fa-solid fa-cloud-upload-alt mb-3 text-3xl text-gray-400"></i>
                            <input class="hidden" id="passport_file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required onchange="updateFileName(this, 'passport-filename')">
                            <label class="cursor-pointer" for="passport_file">
                                <span class="font-medium text-blue-600 hover:text-blue-700">Click to upload</span>
                                <span class="text-gray-500"> or drag and drop</span>
                            </label>
                            <p class="mt-2 text-xs text-gray-500">PDF, JPG, PNG up to 10MB</p>
                            <p class="mt-2 hidden text-sm text-gray-700" id="passport-filename"></p>
                        </div>
                    </div>

                    <!-- Passport Number -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700" for="passport_number">Passport Number</label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" id="passport_number" type="text" name="number" placeholder="Enter passport number">
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700" for="issue_date">Issue Date</label>
                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" id="issue_date" type="date" name="issue_date">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700" for="expiry_date">Expiry Date</label>
                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" id="expiry_date" type="date" name="expiry_date">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 pt-6">
                    <button class="rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200" type="button" onclick="closeAddPassportModal()">
                        Cancel
                    </button>
                    <button class="rounded-lg bg-green-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2" type="submit">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Passport
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Medical Report Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4" id="addMedicalReportModal">
        <div class="w-full max-w-xl transform rounded-xl bg-white shadow-2xl transition-all">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                        <i class="fa-solid fa-file-medical text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Add Medical Report</h3>
                </div>
                <button class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" onclick="closeAddMedicalReportModal()">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
            <!-- Form -->
            <form class="p-6" action="{{ route("patients.medical-reports.store", $patient->hn) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- File Upload -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700" for="medical_file">Medical Report File *</label>
                        <div class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center transition-colors hover:border-red-400">
                            <i class="fa-solid fa-file-medical mb-3 text-3xl text-gray-400"></i>
                            <input class="hidden" id="medical_file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required onchange="updateFileName(this, 'medical-filename')">
                            <label class="cursor-pointer" for="medical_file">
                                <span class="font-medium text-red-600 hover:text-red-700">Click to upload</span>
                                <span class="text-gray-500"> or drag and drop</span>
                            </label>
                            <p class="mt-2 text-xs text-gray-500">PDF, JPG, PNG up to 10MB</p>
                            <p class="mt-2 hidden text-sm text-gray-700" id="medical-filename"></p>
                        </div>
                    </div>

                    <!-- Report Date -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700" for="report_date">Report Date *</label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-colors focus:border-red-500 focus:ring-2 focus:ring-red-500" id="report_date" type="date" name="date" required>
                        <p class="mt-2 text-xs text-gray-500">Select the date when this medical report was created.</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 pt-6">
                    <button class="rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200" type="button" onclick="closeAddMedicalReportModal()">
                        Cancel
                    </button>
                    <button class="rounded-lg bg-red-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2" type="submit">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- File Viewer Modal - Full Screen -->
    <div class="fixed inset-0 z-50 hidden bg-black bg-opacity-95" id="fileModal">
        <div class="relative flex h-full w-full flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b bg-white p-4 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                        <i class="fa-solid fa-file text-sm text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">File Viewer</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200" onclick="closeFileModal()">
                        <i class="fa-solid fa-times mr-2"></i>
                        Close
                    </button>
                </div>
            </div>
            <!-- Content Area -->
            <div class="flex-1 overflow-hidden bg-gray-50">
                <div class="flex h-full w-full items-center justify-center p-4" id="fileContent">
                    <!-- File content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewFile(hn, filename) {
            const fileContent = document.getElementById('fileContent');
            const modal = document.getElementById('fileModal');

            // Clear previous content and show loading
            fileContent.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-500">
                    <i class="fa-solid fa-spinner fa-spin text-3xl mb-4"></i>
                    <p class="text-lg">Loading file...</p>
                </div>
            `;

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            const fileUrl = `/files/${hn}/${filename}`;
            const fileExtension = filename.split('.').pop().toLowerCase();

            if (['pdf'].includes(fileExtension)) {
                fileContent.innerHTML = `
                    <iframe src="${fileUrl}" 
                            class="w-full h-full border-0 rounded-lg shadow-lg" 
                            style="min-height: calc(100vh - 120px);">
                    </iframe>
                `;
            } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExtension)) {
                fileContent.innerHTML = `
                    <div class="w-full h-full flex items-center justify-center">
                        <img src="${fileUrl}" 
                             class="max-w-full max-h-full object-contain rounded-lg shadow-lg" 
                             alt="${filename}"
                             style="max-height: calc(100vh - 120px);">
                    </div>
                `;
            } else {
                fileContent.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <div class="bg-white rounded-lg p-8 shadow-lg max-w-md">
                            <i class="fa-solid fa-file text-6xl text-gray-400 mb-6"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">${filename}</h3>
                            <p class="text-gray-600 mb-6">This file type cannot be previewed in the browser.</p>
                            <a href="${fileUrl}" 
                               target="_blank" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fa-solid fa-download mr-2"></i>
                                Download File
                            </a>
                        </div>
                    </div>
                `;
            }
        }

        function closeFileModal() {
            const modal = document.getElementById('fileModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('fileContent').innerHTML = '';
        }

        function openAddNoteModal() {
            const modal = document.getElementById('addNoteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAddNoteModal() {
            const modal = document.getElementById('addNoteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('note').value = '';
            document.body.style.overflow = 'auto';
        }

        function openAddPassportModal() {
            const modal = document.getElementById('addPassportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAddPassportModal() {
            const modal = document.getElementById('addPassportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.querySelector('#addPassportModal form').reset();
            document.getElementById('passport-filename').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openAddMedicalReportModal() {
            const modal = document.getElementById('addMedicalReportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAddMedicalReportModal() {
            const modal = document.getElementById('addMedicalReportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.querySelector('#addMedicalReportModal form').reset();
            document.getElementById('medical-filename').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // File name display function
        function updateFileName(input, displayId) {
            const display = document.getElementById(displayId);
            if (input.files && input.files[0]) {
                display.textContent = `Selected: ${input.files[0].name}`;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }
        }

        function deleteNote(hn, noteId) {
            if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
                axios.post('{{ route("patients.notes.destroy", ["hn" => $patient->hn, "id" => "__ID__"]) }}'.replace('__ID__', noteId))
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error deleting note:', error);
                        alert('Failed to delete note. Please try again.');
                    });
            }
        }

        function deletePassport(hn, passportId) {
            if (confirm('Are you sure you want to delete this passport? This will also delete the associated file. This action cannot be undone.')) {
                axios.post('{{ route("patients.passports.destroy", ["hn" => $patient->hn, "id" => "__ID__"]) }}'.replace('__ID__', passportId))
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error deleting passport:', error);
                        alert('Failed to delete passport. Please try again.');
                    });
            }
        }

        function deleteMedicalReport(hn, reportId) {
            if (confirm('Are you sure you want to delete this medical report? This will also delete the associated file. This action cannot be undone.')) {
                axios.post('{{ route("patients.medical-reports.destroy", ["hn" => $patient->hn, "id" => "__ID__"]) }}'.replace('__ID__', reportId))
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error deleting medical report:', error);
                        alert('Failed to delete medical report. Please try again.');
                    });
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFileModal();
                closeAddNoteModal();
                closeAddPassportModal();
                closeAddMedicalReportModal();
            }
        });
    </script>
@endsection
