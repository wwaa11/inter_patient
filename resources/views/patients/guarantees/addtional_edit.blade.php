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
                                    <i class="fas fa-edit mr-3 text-blue-600"></i>
                                    Edit Additional Guarantee
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
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
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

            <form id="updateForm" action="{{ route("patients.guarantees.additional.update", [$patient->hn, $detail->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Information Content --}}
                <div class="mb-6 rounded-lg bg-white shadow-sm">
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Guarantee Information ( Update all guarantee that use the same information)</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 px-6 pb-4 md:grid-cols-3">

                            {{-- Type --}}
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="type">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Type *
                                </label>
                                <select class="@error("type") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="type" name="type" required>
                                    @foreach ($additionalTypes as $type)
                                        <option value="{{ $type->id }}" {{ old("type", $header->name) == $type->id ? "selected" : "" }}>
                                            {{ $type->name }}
                                        </option>
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
                                <input class="@error("embassy_ref") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy_ref" type="text" name="embassy_ref" required placeholder="Enter embassy reference" value="{{ old("embassy_ref", $header->embassy_ref) }}">
                                @error("embassy_ref")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- MB -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="mb">
                                    <i class="fas fa-hashtag mr-2 text-purple-500"></i>MB (Optional)
                                </label>
                                <input class="@error("mb") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="mb" type="text" name="mb" placeholder="Enter MB" value="{{ old("mb", $header->mb) }}">
                                @error("mb")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Issue Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="issue_date">
                                    <i class="fas fa-calendar-plus mr-2 text-purple-500"></i>Issue Date *
                                </label>
                                <input class="@error("issue_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="issue_date" type="date" name="issue_date" required value="{{ old("issue_date", $header->issue_date) }}">
                                @error("issue_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover Start Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_start_date">
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>Cover Start Date (Optional)
                                </label>
                                <input class="@error("cover_start_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_start_date" type="date" name="cover_start_date" value="{{ $header->cover_start_date == null ? "" : date("Y-m-d", strtotime($header->cover_start_date)) }}">
                                @error("cover_start_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover End Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_end_date">
                                    <i class="fas fa-calendar-times mr-2 text-red-500"></i>Cover End Date (Optional)
                                </label>
                                <input class="@error("cover_end_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_end_date" type="date" name="cover_end_date" value="{{ $header->cover_end_date == null ? "" : date("Y-m-d", strtotime($header->cover_end_date)) }}">
                                @error("cover_end_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Price -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="total_price">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Total Price (Optional)
                                </label>
                                <input class="@error("total_price") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="total_price" type="text" name="total_price" placeholder="Enter Total Price" value="{{ old("total_price", $header->total_price) }}">
                                @error("total_price")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current Files Display -->
                            @if ($header->file && count($header->file) > 0)
                                <div class="md:col-span-3">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                                        <i class="fas fa-paperclip mr-2 text-blue-500"></i>Current Files
                                    </label>
                                    <div class="space-y-2">
                                        @foreach ($header->file as $index => $file)
                                            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-3" id="file-item-{{ $index }}">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file mr-2 text-gray-500"></i>
                                                    <span class="text-sm text-gray-700">{{ $file }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a class="text-blue-600 hover:text-blue-800" href="{{ asset("hn/" . $patient->hn . "/" . $file) }}" target="_blank">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                    <button class="text-red-600 hover:text-red-800" type="button" onclick="removeFile('{{ $file }}', {{ $index }})">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- File Upload -->
                            <div class="md:col-span-3">
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="guarantee_file">
                                    <i class="fas fa-cloud-upload-alt mr-2 text-orange-500"></i>Upload New File (Optional)
                                </label>
                                <div class="relative">
                                    <input class="@error("file") border-red-500 @enderror w-full rounded-lg border-2 border-dashed border-gray-300 px-4 py-3 transition-all file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500" id="guarantee_file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this)">
                                    @error("file")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Accepted formats: PDF, JPG, JPEG, PNG (Max: 10MB)
                                </p>
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

                            <!-- Detail Entry -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="mb-4">
                                    <h3 class="text-md font-semibold text-gray-900">
                                        <i class="fas fa-edit mr-2 text-green-500"></i>Detail Information
                                    </h3>
                                </div>

                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <div class="flex flex-col gap-4">
                                        <!-- Select Case -->
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                <i class="fas fa-list mr-2 text-blue-500"></i>Select Case (Optional)
                                            </label>
                                            <select class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="additional_case">
                                                <option value="">Please select</option>
                                                @if (isset($additionalCases))
                                                    @foreach ($additionalCases as $case)
                                                        <option value="{{ $case->id }}" {{ old("additional_case", $detail->case) == $case->id ? "selected" : "" }}>
                                                            {{ $case->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <!-- Specific Date -->
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                <i class="fas fa-calendar mr-2 text-purple-500"></i>Specific Date (Optional)
                                            </label>
                                            <div class="space-y-3">
                                                @php
                                                    $hasDateRange = !empty($detail->start_date) && !empty($detail->end_date);
                                                    $hasSpecificDates = !empty($detail->specific_date) && is_array($detail->specific_date);
                                                @endphp

                                                <div class="flex items-center">
                                                    <input class="mr-2" id="dateRange" type="checkbox" onchange="toggleDateRange(this)" {{ $hasDateRange ? "checked" : "" }}>
                                                    <label class="text-sm text-gray-600" for="dateRange">Use date range</label>
                                                </div>

                                                <div class="date-single {{ $hasDateRange ? "hidden" : "" }}">
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-sm font-medium text-gray-700">Dates</span>
                                                            <button class="text-sm font-medium text-green-600 hover:text-green-700" type="button" onclick="addDateEntry()">
                                                                <i class="fas fa-plus mr-1"></i>Add Date
                                                            </button>
                                                        </div>
                                                        <div class="date-entries" id="dateEntries">
                                                            @if ($hasSpecificDates)
                                                                @foreach ($detail->specific_date as $index => $date)
                                                                    <div class="date-entry mb-2 flex items-center space-x-2">
                                                                        <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="specific_dates[]" value="{{ $date }}">
                                                                        <button class="p-2 text-red-500 hover:text-red-700" type="button" onclick="removeDateEntry(this)" {{ $index === 0 ? "style=display:none;" : "" }}>
                                                                            <i class="fas fa-trash text-sm"></i>
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="date-entry mb-2 flex items-center space-x-2">
                                                                    <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="specific_dates[]" value="{{ old("specific_dates.0") }}">
                                                                    <button class="p-2 text-red-500 hover:text-red-700" type="button" onclick="removeDateEntry(this)" style="display: none;">
                                                                        <i class="fas fa-trash text-sm"></i>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="date-range {{ !$hasDateRange ? "hidden" : "" }}">
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="date_range_start" placeholder="Start date" value="{{ old("date_range_start", $detail->start_date) }}">
                                                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="date_range_end" placeholder="End date" value="{{ old("date_range_end", $detail->end_date) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Detail -->
                                        <div class="md:col-span-2">
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                <i class="fas fa-file-alt mr-2 text-green-500"></i>Detail *
                                            </label>
                                            <textarea class="@error("detail") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="detail" rows="3" required placeholder="Enter detail information...">{{ old("detail", $detail->details) }}</textarea>
                                            @error("detail")
                                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Definition -->
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                <i class="fas fa-book mr-2 text-indigo-500"></i>Definition (Optional)
                                            </label>
                                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="definition" placeholder="Enter definition..." value="{{ old("definition", $detail->definition) }}">
                                        </div>

                                        <!-- Amount -->
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                <i class="fas fa-calculator mr-2 text-yellow-500"></i>Amount (Optional)
                                            </label>
                                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="amount" placeholder="Enter amount..., Ex. 15 tabs" value="{{ old("amount", $detail->amount) }}">
                                        </div>

                                        <!-- Price -->
                                        <div>
                                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Price (Optional)
                                            </label>
                                            <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="number" step="0.01" name="price" placeholder="Enter price..." value="{{ old("price", $detail->price) }}">
                                        </div>
                                    </div>
                                </div>
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
                                @if ($header->file && count($header->file) > 0)
                                    @foreach ($header->file as $file)
                                        @php
                                            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                        @endphp
                                        @if (in_array(strtolower($fileExtension), ["jpg", "jpeg", "png", "gif"]))
                                            <img class="h-auto max-w-full rounded-lg" src="{{ asset("hn/" . $patient->hn . "/" . $file) }}" alt="Guarantee File">
                                        @elseif(strtolower($fileExtension) === "pdf")
                                            <embed class="h-full min-h-[600px] w-full rounded-lg p-6" src="{{ asset("hn/" . $patient->hn . "/" . $file) }}" type="application/pdf">
                                        @else
                                            <div class="text-center">
                                                <i class="fas fa-file mb-3 text-4xl text-gray-400"></i>
                                                <p class="font-medium text-gray-700">{{ basename($file) }}</p>
                                                <p class="text-sm text-gray-500">File available for download</p>
                                                <a class="mt-2 inline-block rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" href="{{ asset("hn/" . $patient->hn . "/" . $file) }}" target="_blank">
                                                    <i class="fas fa-download mr-2"></i>Download
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-file-upload mb-3 text-4xl text-gray-400"></i>
                                        <p class="text-gray-500">No file selected</p>
                                        <p class="mt-1 text-sm text-gray-400">Upload a file to see preview</p>
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
                        <i class="fas fa-save mr-2"></i>Update Guarantee
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        function toggleDateRange(checkbox) {
            const dateRange = document.querySelector('.date-range');
            const dateSingle = document.querySelector('.date-single');

            if (checkbox.checked) {
                dateRange.classList.remove('hidden');
                dateSingle.classList.add('hidden');
            } else {
                dateRange.classList.add('hidden');
                dateSingle.classList.remove('hidden');
            }
        }

        function addDateEntry() {
            const container = document.getElementById('dateEntries');
            const newEntry = document.createElement('div');
            newEntry.className = 'date-entry mb-2 flex items-center space-x-2';
            newEntry.innerHTML = `
                <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="specific_dates[]">
                <button class="p-2 text-red-500 hover:text-red-700" type="button" onclick="removeDateEntry(this)">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            `;
            container.appendChild(newEntry);
            updateRemoveButtons();
        }

        function removeDateEntry(button) {
            button.closest('.date-entry').remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const entries = document.querySelectorAll('.date-entry');
            entries.forEach((entry, index) => {
                const removeBtn = entry.querySelector('button');
                if (index === 0 && entries.length === 1) {
                    removeBtn.style.display = 'none';
                } else {
                    removeBtn.style.display = 'block';
                }
            });
        }

        // Initialize remove button visibility
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });

        // File removal functionality
        let filesToRemove = [];

        // Initialize the hidden input on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateFilesToRemoveInput();
        });

        function removeFile(filename, index) {
            if (confirm('Are you sure you want to remove this file?')) {
                // Add to removal list
                filesToRemove.push(filename);

                // Hide the file elements
                const fileItem = document.getElementById('file-item-' + index);
                const fileDisplay = document.getElementById('file-display-' + index);

                if (fileItem) {
                    fileItem.style.display = 'none';
                }
                if (fileDisplay) {
                    fileDisplay.style.display = 'none';
                }

                // Update hidden input for files to remove
                updateFilesToRemoveInput();

                // Check if all files are removed
                checkAllFilesRemoved();
            }
        }

        function updateFilesToRemoveInput() {
            // Remove existing hidden input if any
            const existingInput = document.querySelector('input[name="files_to_remove"]');
            if (existingInput) {
                existingInput.remove();
            }

            // Always add hidden input with files to remove (even if empty)
            const form = document.getElementById('updateForm');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'files_to_remove';
            hiddenInput.value = JSON.stringify(filesToRemove);
            form.appendChild(hiddenInput);
        }

        function checkAllFilesRemoved() {
            const allFileItems = document.querySelectorAll('[id^="file-item-"]');
            const allFileDisplays = document.querySelectorAll('[id^="file-display-"]');

            let allHidden = true;
            allFileItems.forEach(item => {
                if (item.style.display !== 'none') {
                    allHidden = false;
                }
            });

            if (allHidden && allFileItems.length > 0) {
                // Show "No files" message in right panel
                const rightPanel = document.querySelector('.min-h-\[400px\] .p-6');
                if (rightPanel) {
                    rightPanel.innerHTML = `
                        <div class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6">
                            <div class="text-center">
                                <i class="fas fa-file-upload mb-3 text-4xl text-gray-400"></i>
                                <p class="text-gray-500">No files uploaded</p>
                                <p class="mt-1 text-sm text-gray-400">Upload a file to see it here</p>
                            </div>
                        </div>
                    `;
                }
            }
        }

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
    </script>
@endpush
