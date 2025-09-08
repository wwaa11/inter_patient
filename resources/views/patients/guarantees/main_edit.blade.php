@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-gray-50 py-6 dark:bg-slate-900">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 rounded-lg bg-white shadow-sm dark:bg-slate-800">
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a class="mr-4 text-gray-500 hover:text-gray-700" href="{{ route("patients.view", $patient->hn) }}">
                                <i class="fas fa-arrow-left text-lg"></i>
                            </a>
                            <div>
                                <h1 class="flex items-center text-2xl font-bold text-gray-900 dark:text-white">
                                    <i class="fas fa-edit mr-3 text-blue-600"></i>
                                    Edit Main Guarantee ***Not Test
                                </h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Patient: <span class="font-medium">{{ $patient->name }}</span> (HN: {{ $patient->hn }})
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form class="" action="{{ route("patients.guarantees.main.update", [$patient->hn, $guarantee->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Information Content --}}
                <div class="mb-6 rounded-lg bg-white shadow-sm dark:bg-slate-800 dark:text-white">
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Guarantee Information</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 px-6 pb-4 md:grid-cols-3">
                            <!-- Embassy Selection -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-white" for="embassy">
                                    <i class="fas fa-flag mr-2 text-blue-500"></i>Embassy *
                                </label>
                                <select class="@error("embassy") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy" name="embassy" required>
                                    <option class="dark:bg-slate-800 dark:text-white" value="">Select Embassy</option>
                                    @foreach ($embassies as $embassy)
                                        <option class="dark:bg-slate-800 dark:text-white" value="{{ $embassy->name }}" {{ old("embassy", $guarantee->embassy) == $embassy->name ? "selected" : "" }}>{{ $embassy->name }}</option>
                                    @endforeach
                                </select>
                                @error("embassy")
                                    <p class="mt-1 text-sm text-red-500 dark:text-white">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Embassy Reference -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-white" for="embassy_ref">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Embassy Reference
                                </label>
                                <input class="@error("embassy_ref") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy_ref" type="text" name="embassy_ref" required placeholder="Enter embassy reference" value="{{ old("embassy_ref", $guarantee->embassy_ref) }}">
                                @error("embassy_ref")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Number -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-white" for="number">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Number (Optional)
                                </label>
                                <input class="@error("number") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="number" type="text" name="number" placeholder="Enter Number" value="{{ old("number", $guarantee->number) }}">
                                @error("number")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Issue Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-white" for="issue_date">
                                    <i class="fas fa-calendar-plus mr-2 text-purple-500"></i>Issue Date *
                                </label>
                                <input class="@error("issue_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="issue_date" type="date" name="issue_date" required value="{{ old("issue_date", $guarantee->issue_date) }}">
                                @error("issue_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover Start Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-white" for="cover_start_date">
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>Cover Start *
                                </label>
                                <input class="@error("cover_start_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_start_date" type="date" name="cover_start_date" required value="{{ old("cover_start_date", $guarantee->cover_start_date) }}">
                                @error("cover_start_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover End Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-white" for="cover_end_date">
                                    <i class="fas fa-calendar-times mr-2 text-red-500"></i>Cover End *
                                </label>
                                <input class="@error("cover_end_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_end_date" type="date" name="cover_end_date" required value="{{ old("cover_end_date", $guarantee->cover_end_date) }}">
                                @error("cover_end_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Coverage Area/Specialty Content -->
                <div class="flex flex-col gap-6 lg:flex-row">
                    <!-- Left Panel - Form -->
                    <div class="rounded-lg bg-white shadow-sm dark:bg-slate-800">
                        <div class="border-b border-gray-200 px-6 py-4 dark:border-slate-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Coverage Area/Specialty</h2>
                        </div>
                        <div class="space-y-6 p-6">

                            <!-- File Upload -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-white" for="guarantee_file">
                                    <i class="fas fa-cloud-upload-alt mr-2 text-orange-500"></i>Upload File (Optional - Leave empty to keep current file)
                                </label>
                                <div class="relative">
                                    <input class="@error("file") border-red-500 @enderror w-full rounded-lg border-2 border-dashed border-gray-300 px-4 py-3 transition-all file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500" id="guarantee_file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this)">
                                    @error("file")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-white">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Accepted formats: PDF, JPG, JPEG, PNG (Max: 10MB)
                                </p>
                                @if ($guarantee->file && count($guarantee->file) > 0)
                                    <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                                        <i class="fas fa-file mr-1"></i>
                                        Current file: {{ $guarantee->file[0] }}
                                    </p>
                                @endif
                            </div>

                            <!-- Guarantee Cases -->
                            <div>
                                <label class="mb-3 block text-sm font-semibold text-gray-700 dark:text-white">
                                    <i class="fas fa-list-check mr-2 text-indigo-500"></i>Guarantee Cases *
                                </label>

                                <!-- Search Filter -->
                                <div class="mb-4">
                                    <div class="relative">
                                        <input class="w-full rounded-lg border border-gray-300 px-4 py-2 pl-10 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" id="caseSearch" type="text" placeholder="Search guarantee cases...">
                                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                    </div>
                                </div>

                                <!-- Available Cases -->
                                <div class="mb-4">
                                    <h4 class="mb-2 text-sm font-medium text-gray-600 dark:text-white">Available Cases</h4>
                                    <div class="max-h-40 overflow-y-auto rounded-lg border border-gray-200" id="availableCases">
                                        @foreach ($guaranteeCases as $case)
                                            <div class="case-item flex items-center justify-between border-b border-gray-100 p-3 last:border-b-0 hover:bg-gray-50 dark:border-slate-700 dark:hover:bg-slate-600" data-case-name="{{ strtolower($case->case) }}">
                                                <span class="text-sm text-gray-700 dark:text-white">{{ $case->name }}</span>
                                                <button class="rounded-full bg-blue-500 px-3 py-1 text-xs text-white transition-colors hover:bg-blue-600" type="button" onclick="addGuaranteeCase({{ $case->id }}, '{{ addslashes($case->name) }}')" @if (in_array($case->id, $selectedCases)) style="display: none;" @endif>
                                                    <i class="fas fa-plus mr-1"></i>Add
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Selected Cases -->
                                <div>
                                    <h4 class="mb-2 text-sm font-medium text-gray-600 dark:text-white">Selected Cases</h4>
                                    <div class="min-h-16 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-3 dark:bg-slate-700" id="selectedCases">
                                        @if (count($selectedCases) > 0)
                                            @foreach ($guaranteeCases->whereIn("id", $selectedCases) as $selectedCase)
                                                <div class="mb-2 flex items-center justify-between rounded-lg border border-blue-200 bg-blue-50 p-2">
                                                    <span class="text-sm font-medium text-blue-800">{{ $selectedCase->name }}</span>
                                                    <button class="text-red-500 transition-colors hover:text-red-700" type="button" onclick="removeGuaranteeCase({{ $selectedCase->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <input type="hidden" name="guarantee_cases[]" value="{{ $selectedCase->id }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-center text-sm text-gray-500 dark:text-white" id="noCasesMessage">No cases selected</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Right Panel - File Preview -->
                    <div class="relative min-h-[800px] flex-1 rounded-lg bg-white shadow-sm dark:bg-slate-800">
                        <div class="border-b border-gray-200 px-6 py-4 dark:border-slate-700">
                            <h2 class="flex items-center text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-eye mr-2 text-blue-500"></i>File Preview
                            </h2>
                        </div>
                        <div class="m-auto p-6">
                            <div class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6 dark:border-slate-700 dark:bg-slate-700" id="filePreview">
                                @if ($guarantee->file && count($guarantee->file) > 0)
                                    @php
                                        $filePath = "/hn/" . $patient->hn . "/" . $guarantee->file[0];
                                        $fileExtension = pathinfo($guarantee->file[0], PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array(strtolower($fileExtension), ["jpg", "jpeg", "png"]))
                                        <img class="h-auto max-w-full rounded-lg" src="{{ $filePath }}" alt="Current guarantee file">
                                    @elseif(strtolower($fileExtension) === "pdf")
                                        <embed class="h-full min-h-[700px] w-full rounded-lg p-6" src="{{ $filePath }}" type="application/pdf">
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-file mb-3 text-4xl text-gray-400 dark:text-white"></i>
                                            <p class="font-medium text-gray-700 dark:text-white">{{ $guarantee->file[0] }}</p>
                                            <p class="text-sm text-gray-500 dark:text-white">Current file</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-file-upload mb-3 text-4xl text-gray-400 dark:text-white"></i>
                                        <p class="text-gray-500 dark:text-white">No file uploaded</p>
                                        <p class="mt-1 text-sm text-gray-400 dark:text-white">Upload a file to see preview</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <a class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50" href="{{ route("patients.view", $patient->hn) }}">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button class="rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 text-sm font-medium text-white shadow-lg transition-all hover:from-blue-700 hover:to-blue-800" type="submit">
                        <i class="fas fa-save mr-2"></i>Update Main Guarantee
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        let selectedGuaranteeCases = @json($selectedCases);

        function addGuaranteeCase(caseId, caseName) {
            if (!selectedGuaranteeCases.find(c => c == caseId)) {
                selectedGuaranteeCases.push(caseId);
                updateSelectedCasesDisplay();
                // Hide the add button for this case
                document.querySelector(`[onclick="addGuaranteeCase(${caseId}, '${caseName.replace(/'/g, "\\'")}')"]`).style.display = 'none';
            }
        }

        function removeGuaranteeCase(caseId) {
            selectedGuaranteeCases = selectedGuaranteeCases.filter(c => c != caseId);
            updateSelectedCasesDisplay();
            // Show the add button for this case
            const addButton = document.querySelector(`[onclick*="addGuaranteeCase(${caseId},"]`);
            if (addButton) {
                addButton.style.display = 'inline-block';
            }
        }

        function updateSelectedCasesDisplay() {
            const container = document.getElementById('selectedCases');
            const guaranteeCases = @json($guaranteeCases);

            if (selectedGuaranteeCases.length === 0) {
                container.innerHTML = '<p class="text-center text-sm text-gray-500 dark:text-white" id="noCasesMessage">No cases selected</p>';
            } else {
                let html = '';
                selectedGuaranteeCases.forEach(caseId => {
                    const guaranteeCase = guaranteeCases.find(c => c.id == caseId);
                    if (guaranteeCase) {
                        html += `
                        <div class="mb-2 flex items-center justify-between rounded-lg border border-blue-200 bg-blue-50 p-2">
                            <span class="text-sm font-medium text-blue-800">${guaranteeCase.name}</span>
                            <button class="text-red-500 transition-colors hover:text-red-700" type="button" onclick="removeGuaranteeCase(${guaranteeCase.id})">
                                <i class="fas fa-times"></i>
                            </button>
                            <input type="hidden" name="guarantee_cases[]" value="${guaranteeCase.id}">
                        </div>
                        `;
                    }
                });
                container.innerHTML = html;
            }
        }

        // Search functionality
        document.getElementById('caseSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const caseItems = document.querySelectorAll('.case-item');

            caseItems.forEach(item => {
                const caseName = item.getAttribute('data-case-name');
                if (caseName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // File preview functionality
        function previewFile(input) {
            const file = input.files[0];
            const preview = document.getElementById('filePreview');

            if (file) {
                const fileType = file.type;
                const fileName = file.name;

                if (fileType.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img class="h-auto max-w-full rounded-lg" src="${e.target.result}" alt="Preview">`;
                    };
                    reader.readAsDataURL(file);
                } else if (fileType === 'application/pdf') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<embed class="p-6  min-h-[700px] h-full w-full rounded-lg" src="${e.target.result}" type="application/pdf">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = `
                        <div class="text-center">
                            <i class="fas fa-file mb-3 text-4xl text-gray-400 dark:text-white"></i>
                            <p class="font-medium text-gray-700 dark:text-white">${fileName}</p>
                            <p class="text-sm text-gray-500 dark:text-white">File uploaded successfully</p>
                        </div>
                        `;
                }
            }
        }

        // Initialize the display on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCasesDisplay();
        });
    </script>
@endpush
