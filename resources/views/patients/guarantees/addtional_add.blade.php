@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 rounded-lg bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a class="mr-4 text-gray-500 hover:text-gray-700" href="{{ route("patients.view", $patient->hn) }}">
                                <i class="fas fa-arrow-left text-lg"></i>
                            </a>
                            <div>
                                <h1 class="flex items-center text-2xl font-bold text-gray-900">
                                    <i class="fas fa-shield-alt mr-3 text-blue-600"></i>
                                    Add Main Guarantee
                                </h1>
                                <p class="mt-1 text-sm text-gray-600">
                                    Patient: <span class="font-medium">{{ $patient->name }}</span> (HN: {{ $patient->hn }})
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div id="error-box">
                    <!-- Display errors here -->
                    @foreach ($errors->all() as $error)
                        <p class="text-red-500">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="" action="{{ route("patients.guarantees.additional.store", $patient->hn) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Information Content --}}
                <div class="mb-6 rounded-lg bg-white shadow-sm">
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Guarantee Information</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 px-6 pb-4 md:grid-cols-3">

                            {{-- Type --}}
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="type">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Type *
                                </label>
                                <select class="@error("type") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="type" name="type" required>
                                    @foreach ($additionalTypes as $type)
                                        <option {{ old("type") == $type->id ? "selected" : "" }} value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error("type")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Embassy Reference -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="embassy_ref">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Embassy Reference *
                                </label>
                                <input class="@error("embassy_ref") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy_ref" type="text" name="embassy_ref" required placeholder="Enter embassy reference" value="{{ old("embassy_ref") }}">
                                @error("embassy_ref")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- MB -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="mb">
                                    <i class="fas fa-hashtag mr-2 text-purple-500"></i>MB (Optional)
                                </label>
                                <input class="@error("mb") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="mb" type="text" name="mb" placeholder="Enter MB" value="{{ old("mb") }}">
                                @error("mb")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Issue Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="issue_date">
                                    <i class="fas fa-calendar-plus mr-2 text-purple-500"></i>Issue Date *
                                </label>
                                <input class="@error("issue_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="issue_date" type="date" name="issue_date" required value="{{ old("issue_date") }}">
                                @error("issue_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover Start Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_start_date">
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>Cover Start Date (Optional)
                                </label>
                                <input class="@error("cover_start_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_start_date" type="date" name="cover_start_date" value="{{ old("cover_start_date") }}">
                                @error("cover_start_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover End Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_end_date">
                                    <i class="fas fa-calendar-times mr-2 text-red-500"></i>Cover End Date (Optional)
                                </label>
                                <input class="@error("cover_end_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_end_date" type="date" name="cover_end_date" value="{{ old("cover_end_date") }}">
                                @error("cover_end_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Price -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="total_price">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Total Price (Optional)
                                </label>
                                <input class="@error("total_price") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="total_price" type="text" name="total_price" placeholder="Enter Total Price" value="{{ old("total_price") }}">
                                @error("total_price")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Coverage Area/Specialty Content -->
                <div class="flex flex-col gap-6 lg:flex-row">
                    <!-- Left Panel - Form -->
                    <div class="rounded-lg bg-white shadow-sm">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Coverage Area / Detail</h2>
                        </div>
                        <div class="space-y-6 p-6">

                            <!-- File Upload -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="guarantee_file">
                                    <i class="fas fa-cloud-upload-alt mr-2 text-orange-500"></i>Upload File *
                                </label>
                                <div class="relative">
                                    <input class="@error("file") border-red-500 @enderror w-full rounded-lg border-2 border-dashed border-gray-300 px-4 py-3 transition-all file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500" id="guarantee_file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(this)">
                                    @error("file")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Accepted formats: PDF, JPG, JPEG, PNG (Max: 10MB)
                                </p>
                            </div>

                            <!-- Add Button Section -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="text-md font-semibold text-gray-900">
                                        <i class="fas fa-plus mr-2 text-green-500"></i>Additional Information
                                    </h3>

                                </div>

                                <div class="space-y-6" id="detailEntries">
                                    <!-- Initial Detail Entry -->
                                    <div class="detail-entry rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <div class="mb-4 flex items-center justify-between">
                                            <h4 class="text-sm font-semibold text-gray-800">
                                                <i class="fas fa-file-alt mr-2 text-blue-500"></i>Detail Entry #1
                                            </h4>
                                            <button class="text-red-500 transition-colors hover:text-red-700" type="button" onclick="removeDetailEntry(this)" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="flex flex-col gap-4">
                                            <!-- Select Case -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-list mr-2 text-blue-500"></i>Select Case (Optional)
                                                </label>
                                                <div class="relative">
                                                    <input class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-10 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" placeholder="Search and select a case..." onclick="toggleCaseDropdown(this)" oninput="filterCases(this)" readonly>
                                                    <i class="fas fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 transform text-gray-400"></i>
                                                    <div class="case-dropdown absolute z-10 mt-1 hidden max-h-60 w-full overflow-y-auto rounded-lg border border-gray-300 bg-white shadow-lg">
                                                        <div class="border-b p-2">
                                                            <input class="case-search w-full rounded border border-gray-300 px-3 py-2 text-sm" type="text" placeholder="Type to search..." oninput="filterCaseOptions(this)">
                                                        </div>
                                                        <div class="case-options">
                                                            <div class="case-option cursor-pointer px-3 py-2 hover:bg-gray-100" data-value="" data-name="" onclick="selectCase(this, 0)">
                                                                Please select
                                                            </div>
                                                            @if (isset($additionalCases))
                                                                @foreach ($additionalCases as $case)
                                                                    <div class="case-option cursor-pointer px-3 py-2 hover:bg-gray-100" data-value="{{ $case->id }}" data-name="{{ $case->name }}" onclick="selectCase(this, 0)">
                                                                        {{ $case->name }}
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input class="case-value" type="hidden" name="details[0][additional_case]">
                                                </div>
                                            </div>

                                            <!-- Specific Date -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>Specific Date (Optional)
                                                </label>
                                                <div class="space-y-3">
                                                    <div class="flex items-center">
                                                        <input class="mr-2" id="dateRange_0" type="checkbox" onchange="toggleDateRange(this, 0)">
                                                        <label class="text-sm text-gray-600" for="dateRange_0">Use date range</label>
                                                    </div>
                                                    <div class="date-single">
                                                        <div class="space-y-2">
                                                            <div class="flex items-center justify-between">
                                                                <span class="text-sm font-medium text-gray-700">Dates</span>
                                                                <button class="text-sm font-medium text-green-600 hover:text-green-700" type="button" onclick="addDateEntry(0)">
                                                                    <i class="fas fa-plus mr-1"></i>Add Date
                                                                </button>
                                                            </div>
                                                            <div class="date-entries" id="dateEntries_0">
                                                                <div class="date-entry mb-2 flex items-center space-x-2">
                                                                    <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[0][specific_dates][]">
                                                                    <button class="p-2 text-red-500 hover:text-red-700" type="button" onclick="removeDateEntry(this)" style="display: none;">
                                                                        <i class="fas fa-trash text-sm"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="date-range hidden">
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[0][date_start]" placeholder="Start date">
                                                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[0][date_end]" placeholder="End date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail -->
                                            <div class="md:col-span-2">
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-file-alt mr-2 text-green-500"></i>Detail *
                                                </label>
                                                <textarea class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="details[0][detail]" rows="3" required placeholder="Enter detail information..."></textarea>
                                            </div>

                                            <!-- Definition -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-book mr-2 text-indigo-500"></i>Definition (Optional)
                                                </label>
                                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[0][definition]" placeholder="Enter definition...">
                                            </div>

                                            <!-- Amount -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-calculator mr-2 text-yellow-500"></i>Amount (Optional)
                                                </label>
                                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[0][amount]" placeholder="Enter amount..., Ex. 15 tabs">
                                            </div>

                                            <!-- Price -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Price (Optional)
                                                </label>
                                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="number" step="0.01" name="details[0][price]" placeholder="Enter price...">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="mt-4 w-full rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700" type="button" onclick="addDetailEntry()">
                                    <i class="fas fa-plus mr-2"></i>Add Detail
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Right Panel - File Preview -->
                    <div class="relative min-h-[800px] flex-1 rounded-lg bg-white shadow-sm">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="flex items-center text-lg font-semibold text-gray-900">
                                <i class="fas fa-eye mr-2 text-blue-500"></i>File Preview
                            </h2>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 m-auto p-6">
                            <div class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6" id="filePreview">
                                <div class="text-center">
                                    <i class="fas fa-file-upload mb-3 text-4xl text-gray-400"></i>
                                    <p class="text-gray-500">No file selected</p>
                                    <p class="mt-1 text-sm text-gray-400">Upload a file to see preview</p>
                                </div>
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
                        <i class="fas fa-save mr-2"></i>Add Main Guarantee
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            let selectedGuaranteeCases = [];

            function addGuaranteeCase(caseId, caseName) {
                if (!selectedGuaranteeCases.find(c => c.id === caseId)) {
                    selectedGuaranteeCases.push({
                        id: caseId,
                        name: caseName
                    });
                    updateSelectedCasesDisplay();
                }
            }

            function removeGuaranteeCase(caseId) {
                selectedGuaranteeCases = selectedGuaranteeCases.filter(c => c.id !== caseId);
                updateSelectedCasesDisplay();
            }

            function updateSelectedCasesDisplay() {
                const container = document.getElementById('selectedCases');

                if (selectedGuaranteeCases.length === 0) {
                    container.innerHTML = '<p class="text-center text-sm text-gray-500" id="noCasesMessage">No cases selected</p>';
                } else {
                    let html = '';
                    selectedGuaranteeCases.forEach(guaranteeCase => {
                        html += `
                    <div class="mb-2 flex items-center justify-between rounded-lg border border-blue-200 bg-blue-50 p-2">
                        <span class="text-sm font-medium text-blue-800">${guaranteeCase.name}</span>
                        <button class="text-red-500 transition-colors hover:text-red-700" type="button" onclick="removeGuaranteeCase(${guaranteeCase.id})">
                            <i class="fas fa-times"></i>
                        </button>
                        <input type="hidden" name="guarantee_cases[]" value="${guaranteeCase.id}">
                    </div>
                    `;
                    });
                    container.innerHTML = html;
                }
            }

            // Search functionality (only if caseSearch element exists)
            const caseSearchElement = document.getElementById('caseSearch');
            if (caseSearchElement) {
                caseSearchElement.addEventListener('input', function(e) {
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
            }

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
                            preview.innerHTML = `<embed class="p-6 h-full min-h-[600px] w-full rounded-lg" src="${e.target.result}" type="application/pdf">`;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = `
                        <div class="text-center">
                            <i class="fas fa-file mb-3 text-4xl text-gray-400"></i>
                            <p class="font-medium text-gray-700">${fileName}</p>
                            <p class="text-sm text-gray-500">File uploaded successfully</p>
                        </div>
                        `;
                    }
                } else {
                    preview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-file-upload mb-3 text-4xl text-gray-400"></i>
                        <p class="text-gray-500">No file selected</p>
                        <p class="mt-1 text-sm text-gray-400">Upload a file to see preview</p>
                    </div>
                    `;
                }
            }

            // Detail entries management
            let detailEntryCount = 1;

            function addDetailEntry() {
                const container = document.getElementById('detailEntries');
                const newIndex = detailEntryCount;

                const newEntry = document.createElement('div');
                newEntry.className = 'detail-entry border border-gray-200 rounded-lg p-4 bg-gray-50';
                newEntry.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-semibold text-gray-800">
                        <i class="fas fa-file-alt mr-2 text-blue-500"></i>Detail Entry #${newIndex + 1}
                    </h4>
                    <button type="button" onclick="removeDetailEntry(this)" class="text-red-500 hover:text-red-700 transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="flex flex-col gap-4">
                    <!-- Select Case -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-list mr-2 text-blue-500"></i>Select Case (Optional)
                        </label>
                        <div class="relative">
                            <input type="text" class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-10 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" placeholder="Search and select a case..." onclick="toggleCaseDropdown(this)" oninput="filterCases(this)" readonly>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            <div class="case-dropdown absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden">
                                <div class="p-2 border-b">
                                    <input type="text" class="case-search w-full px-3 py-2 border border-gray-300 rounded text-sm" placeholder="Type to search..." oninput="filterCaseOptions(this)">
                                </div>
                                <div class="case-options">
                                    @if (isset($additionalCases))
                                        @foreach ($additionalCases as $case)
                                            <div class="case-option px-3 py-2 hover:bg-gray-100 cursor-pointer" data-value="{{ $case->id }}" data-name="{{ $case->name }}" onclick="selectCase(this, ${newIndex})">
                                                {{ $case->name }}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" name="details[${newIndex}][additional_case]" class="case-value">
                        </div>
                    </div>

                    <!-- Specific Date -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar mr-2 text-purple-500"></i>Specific Date (Optional)
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="dateRange_${newIndex}" class="mr-2" onchange="toggleDateRange(this, ${newIndex})">
                                <label for="dateRange_${newIndex}" class="text-sm text-gray-600">Use date range</label>
                            </div>
                            <div class="date-single">
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-medium text-gray-700">Dates</span>
                                                <button type="button" onclick="addDateEntry(${newIndex})" class="text-green-600 hover:text-green-700 text-sm font-medium">
                                                    <i class="fas fa-plus mr-1"></i>Add Date
                                                </button>
                                            </div>
                                            <div class="date-entries" id="dateEntries_${newIndex}">
                                                <div class="date-entry flex items-center space-x-2 mb-2">
                                                    <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${newIndex}][specific_dates][]">
                                                    <button type="button" onclick="removeDateEntry(this)" class="text-red-500 hover:text-red-700 p-2" style="display: none;">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <div class="date-range hidden">
                                <div class="grid grid-cols-2 gap-2">
                                    <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${newIndex}][date_start]" placeholder="Start date">
                                    <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${newIndex}][date_end]" placeholder="End date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-file-alt mr-2 text-green-500"></i>Detail *
                        </label>
                        <textarea class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="details[${newIndex}][detail]" rows="3" required placeholder="Enter detail information..."></textarea>
                    </div>

                    <!-- Definition -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-book mr-2 text-indigo-500"></i>Definition (Optional)
                        </label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[${newIndex}][definition]" placeholder="Enter definition...">
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calculator mr-2 text-yellow-500"></i>Amount (Optional)
                        </label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[${newIndex}][amount]" placeholder="Enter amount..., Ex. 15 tabs">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Price (Optional)
                        </label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="number" step="0.01" name="details[${newIndex}][price]" placeholder="Enter price...">
                    </div>
                </div>
                `;

                container.appendChild(newEntry);
                detailEntryCount++;

                // Show remove buttons for all entries if there's more than one
                updateRemoveButtons();
            }

            function removeDetailEntry(button) {
                const entry = button.closest('.detail-entry');
                entry.remove();

                // Update entry numbers and remove buttons
                updateEntryNumbers();
                updateRemoveButtons();
            }

            function updateEntryNumbers() {
                const entries = document.querySelectorAll('.detail-entry');
                entries.forEach((entry, index) => {
                    const header = entry.querySelector('h4');
                    header.innerHTML = `<i class="fas fa-file-alt mr-2 text-blue-500"></i>Detail Entry #${index + 1}`;
                });
            }

            function updateRemoveButtons() {
                const entries = document.querySelectorAll('.detail-entry');
                const removeButtons = document.querySelectorAll('.detail-entry button[onclick*="removeDetailEntry"]');

                removeButtons.forEach((button, index) => {
                    if (entries.length > 1) {
                        button.style.display = 'block';
                    } else {
                        button.style.display = 'none';
                    }
                });
            }

            // Case dropdown functionality
            function toggleCaseDropdown(input) {
                const dropdown = input.nextElementSibling.nextElementSibling;
                const allDropdowns = document.querySelectorAll('.case-dropdown');

                // Close all other dropdowns
                allDropdowns.forEach(dd => {
                    if (dd !== dropdown) {
                        dd.classList.add('hidden');
                    }
                });

                // Toggle current dropdown
                dropdown.classList.toggle('hidden');

                // Focus search input when opening
                if (!dropdown.classList.contains('hidden')) {
                    const searchInput = dropdown.querySelector('.case-search');
                    setTimeout(() => searchInput.focus(), 100);
                }
            }

            function selectCase(option, index) {
                const container = option.closest('.relative');
                const input = container.querySelector('input[type="text"]');
                const hiddenInput = container.querySelector('.case-value');
                const dropdown = container.querySelector('.case-dropdown');

                input.value = option.getAttribute('data-name');
                hiddenInput.value = option.getAttribute('data-value');
                dropdown.classList.add('hidden');
            }

            function filterCaseOptions(searchInput) {
                const searchTerm = searchInput.value.toLowerCase();
                const options = searchInput.closest('.case-dropdown').querySelectorAll('.case-option');

                options.forEach(option => {
                    const caseName = option.getAttribute('data-name').toLowerCase();
                    if (caseName.includes(searchTerm)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }

            // Date range functionality
            function toggleDateRange(checkbox, index) {
                const container = checkbox.closest('.space-y-3');
                const singleDate = container.querySelector('.date-single');
                const dateRange = container.querySelector('.date-range');

                if (checkbox.checked) {
                    singleDate.classList.add('hidden');
                    dateRange.classList.remove('hidden');
                    // Clear single date value
                    singleDate.querySelector('input').value = '';
                } else {
                    singleDate.classList.remove('hidden');
                    dateRange.classList.add('hidden');
                    // Clear range date values
                    dateRange.querySelectorAll('input').forEach(input => input.value = '');
                }
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.relative')) {
                    document.querySelectorAll('.case-dropdown').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                }
            });

            // Date entry management functions
            function addDateEntry(detailIndex) {
                const container = document.getElementById(`dateEntries_${detailIndex}`);
                const dateEntries = container.querySelectorAll('.date-entry');

                const newDateEntry = document.createElement('div');
                newDateEntry.className = 'date-entry flex items-center space-x-2 mb-2';
                newDateEntry.innerHTML = `
                <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${detailIndex}][specific_dates][]">
                <button type="button" onclick="removeDateEntry(this)" class="text-red-500 hover:text-red-700 p-2">
                    <i class="fas fa-trash text-sm"></i>
                </button>
                `;

                container.appendChild(newDateEntry);
                updateDateRemoveButtons(detailIndex);
            }

            function removeDateEntry(button) {
                const dateEntry = button.closest('.date-entry');
                const container = dateEntry.closest('.date-entries');
                const detailIndex = container.id.split('_')[1];

                dateEntry.remove();
                updateDateRemoveButtons(detailIndex);
            }

            function updateDateRemoveButtons(detailIndex) {
                const container = document.getElementById(`dateEntries_${detailIndex}`);
                const dateEntries = container.querySelectorAll('.date-entry');
                const removeButtons = container.querySelectorAll('button[onclick*="removeDateEntry"]');

                removeButtons.forEach((button, index) => {
                    if (dateEntries.length > 1) {
                        button.style.display = 'block';
                    } else {
                        button.style.display = 'none';
                    }
                });
            }

            // Make functions globally accessible
            window.addDetailEntry = addDetailEntry;
            window.removeDetailEntry = removeDetailEntry;
            window.previewFile = previewFile;
            window.addGuaranteeCase = addGuaranteeCase;
            window.removeGuaranteeCase = removeGuaranteeCase;
            window.toggleCaseDropdown = toggleCaseDropdown;
            window.selectCase = selectCase;
            window.filterCaseOptions = filterCaseOptions;
            window.toggleDateRange = toggleDateRange;
            window.addDateEntry = addDateEntry;
            window.removeDateEntry = removeDateEntry;

        }); // End of DOMContentLoaded
    </script>
@endpush
