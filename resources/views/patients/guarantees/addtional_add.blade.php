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
                                    Add Additional Guarantee
                                </h1>
                                <p class="mt-1 text-sm text-gray-600">
                                    Patient: <span class="font-medium">{{ $patient->name }}</span> (HN: {{ $patient->hn }})
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form class="space-y-8" action="{{ route("patients.guarantees.additional.store", $patient->hn) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Two Panel Layout -->
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Left Panel: Form Fields -->
                    <div class="rounded-xl bg-white p-8 shadow-lg">
                        <h2 class="mb-6 text-2xl font-bold text-gray-800">
                            <i class="fas fa-edit mr-2 text-green-500"></i>
                            Guarantee Information
                        </h2>

                        <div class="space-y-6">
                            <!-- Embassy -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="embassy">
                                    <i class="fas fa-building mr-2 text-blue-500"></i>Embassy *
                                </label>
                                <select class="@error("embassy") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy" name="embassy" required>
                                    <option value="">Select Embassy</option>
                                    @foreach ($embassies as $embassy)
                                        <option value="{{ $embassy->name }}" {{ old("embassy") == $embassy->name ? "selected" : "" }}>{{ $embassy->name }}</option>
                                    @endforeach
                                </select>
                                @error("embassy")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                </select>
                            </div>

                            <!-- Upload File Section -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="file">
                                    <i class="fas fa-upload mr-2 text-purple-500"></i>Upload Files
                                </label>
                                <input class="@error("file") border-red-500 @enderror w-full rounded-lg border-2 border-dashed border-gray-300 px-4 py-3 transition-all file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500" id="file" type="file" name="file[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                                @error("file")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Supported formats: PDF, JPG, JPEG, PNG (Max: 10MB each)</p>
                            </div>

                            <!-- Type -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="type">
                                    <i class="fas fa-tag mr-2 text-indigo-500"></i>Type *
                                </label>
                                <select class="@error("type") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    @foreach ($additionalTypes as $type)
                                        <option value="{{ $type->type }}" {{ old("type") == $type->type ? "selected" : "" }}>{{ $type->type }}</option>
                                    @endforeach
                                </select>
                                @error("type")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                </select>
                            </div>

                            <!-- Embassy Reference and MB -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="embassy_ref">
                                        <i class="fas fa-hashtag mr-2 text-green-500"></i>Embassy Reference
                                    </label>
                                    <input class="@error("embassy_ref") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy_ref" type="text" name="embassy_ref" placeholder="Enter embassy reference" value="{{ old("embassy_ref") }}">
                                    @error("embassy_ref")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="mb">
                                        <i class="fas fa-hashtag mr-2 text-purple-500"></i>MB
                                    </label>
                                    <input class="@error("mb") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="mb" type="text" name="mb" placeholder="Enter MB" value="{{ old("mb") }}">
                                    @error("mb")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="issue_date">
                                        <i class="fas fa-calendar-plus mr-2 text-green-500"></i>Issue Date *
                                    </label>
                                    <input class="@error("issue_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="issue_date" type="date" name="issue_date" required value="{{ old("issue_date") }}">
                                    @error("issue_date")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_start_date">
                                        <i class="fas fa-calendar-check mr-2 text-blue-500"></i>Cover Start Date *
                                    </label>
                                    <input class="@error("cover_start_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_start_date" type="date" name="cover_start_date" required value="{{ old("cover_start_date") }}">
                                    @error("cover_start_date")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_end_date">
                                        <i class="fas fa-calendar-times mr-2 text-red-500"></i>Cover End Date *
                                    </label>
                                    <input class="@error("cover_end_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_end_date" type="date" name="cover_end_date" required value="{{ old("cover_end_date") }}">
                                    @error("cover_end_date")
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Total Price -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="total_price">
                                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Total Price *
                                </label>
                                <input class="@error("total_price") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="total_price" type="number" name="total_price" step="0.01" min="0" required placeholder="Enter total price" value="{{ old("total_price") }}">
                                @error("total_price")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel: Guarantee Details -->
                    <div class="rounded-xl bg-white p-8 shadow-lg">
                        <h2 class="mb-6 text-2xl font-bold text-gray-800">
                            <i class="fas fa-list mr-2 text-purple-500"></i>
                            Guarantee Details
                        </h2>

                        <div class="space-y-4" id="guarantee-details-container">
                            <!-- Initial Detail Row -->
                            <div class="guarantee-detail-row rounded-lg border border-gray-200 p-4">
                                <div class="mb-4 flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-700">Detail #1</h4>
                                    <button class="remove-detail-btn hidden rounded-full bg-red-500 px-3 py-1 text-sm text-white hover:bg-red-600" type="button" onclick="removeDetailRow(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-600">Case *</label>
                                        <select class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" name="details[0][case]" required>
                                            <option value="">Select Case</option>
                                            @foreach ($additionalCases as $case)
                                                <option value="{{ $case->case }}">{{ $case->case }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-600">Specific Date</label>
                                            <input class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="date" name="details[0][specific_date]">
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-600">Amount</label>
                                            <input class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="number" name="details[0][amount]" step="0.01" min="0" placeholder="0.00">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-600">Details</label>
                                            <textarea class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" name="details[0][details]" rows="2" placeholder="Enter details"></textarea>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-600">Definition</label>
                                            <textarea class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" name="details[0][definition]" rows="2" placeholder="Enter definition"></textarea>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-600">Price</label>
                                        <input class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="number" name="details[0][price]" step="0.01" min="0" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="mt-4 w-full rounded-lg bg-green-500 px-4 py-2 text-white transition-colors hover:bg-green-600" id="add-detail-btn" type="button">
                            <i class="fas fa-plus mr-2"></i>Add Another Detail
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center space-x-4">
                    <a class="rounded-lg bg-gray-500 px-8 py-3 text-white transition-colors hover:bg-gray-600" href="{{ route("patients.view", $patient->hn) }}">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel
                    </a>
                    <button class="rounded-lg bg-blue-600 px-8 py-3 text-white transition-colors hover:bg-blue-700" type="submit">
                        <i class="fas fa-save mr-2"></i>Save Additional Guarantee
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        let detailIndex = 1;

        document.getElementById('add-detail-btn').addEventListener('click', function() {
            addDetailRow();
        });

        function addDetailRow() {
            const container = document.getElementById('guarantee-details-container');
            const newRow = document.createElement('div');
            newRow.className = 'guarantee-detail-row rounded-lg border border-gray-200 p-4';

            newRow.innerHTML = `
            <div class="mb-4 flex items-center justify-between">
                <h4 class="font-semibold text-gray-700">Detail #${detailIndex + 1}</h4>
                <button type="button" class="remove-detail-btn rounded-full bg-red-500 px-3 py-1 text-sm text-white hover:bg-red-600" onclick="removeDetailRow(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-600">Case *</label>
                    <select class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" name="details[${detailIndex}][case]" required>
                        <option value="">Select Case</option>
                        @foreach ($additionalCases as $case)
                            <option value="{{ $case->case }}">{{ $case->case }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">Specific Date</label>
                        <input class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="date" name="details[${detailIndex}][specific_date]">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">Amount</label>
                        <input class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="number" name="details[${detailIndex}][amount]" step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">Details</label>
                        <textarea class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" name="details[${detailIndex}][details]" rows="2" placeholder="Enter details"></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">Definition</label>
                        <textarea class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" name="details[${detailIndex}][definition]" rows="2" placeholder="Enter definition"></textarea>
                    </div>
                </div>
                
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-600">Price</label>
                    <input class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="number" name="details[${detailIndex}][price]" step="0.01" min="0" placeholder="0.00">
                </div>
            </div>
            `;

            container.appendChild(newRow);
            detailIndex++;

            updateRemoveButtons();
        }

        function removeDetailRow(button) {
            const row = button.closest('.guarantee-detail-row');
            row.remove();
            updateDetailNumbers();
            updateRemoveButtons();
        }

        function updateDetailNumbers() {
            const rows = document.querySelectorAll('.guarantee-detail-row');
            rows.forEach((row, index) => {
                const header = row.querySelector('h4');
                header.textContent = `Detail #${index + 1}`;
            });
        }

        function updateRemoveButtons() {
            const removeButtons = document.querySelectorAll('.remove-detail-btn');
            const totalRows = document.querySelectorAll('.guarantee-detail-row').length;

            removeButtons.forEach(button => {
                if (totalRows > 1) {
                    button.classList.remove('hidden');
                } else {
                    button.classList.add('hidden');
                }
            });
        }
    </script>
@endpush
