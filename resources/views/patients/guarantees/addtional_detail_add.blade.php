@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-gray-50 py-6 dark:bg-slate-900">
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
                                    Add Guarantee Details
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

            <form class="" action="{{ route("patients.guarantees.additional.detail.store", [$patient->hn, $guarantee->id]) }}" method="POST" enctype="multipart/form-data">
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
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Type
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy_ref" type="text" name="embassy_ref" required placeholder="Enter embassy reference" readonly value=" {{ $guarantee->additionalType->name }}">
                            </div>

                            <!-- Embassy Reference -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="embassy_ref">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Embassy Reference *
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy_ref" readonly type="text" name="embassy_ref" required placeholder="Enter embassy reference" value="{{ old("embassy_ref") ?? $guarantee->embassy_ref }}">

                            </div>

                            <!-- MB -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="mb">
                                    <i class="fas fa-hashtag mr-2 text-purple-500"></i>MB (Optional)
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="mb" readonly type="text" name="mb" placeholder="Enter MB" value="{{ old("mb") ?? $guarantee->mb }}">
                            </div>

                            <!-- Issue Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="issue_date">
                                    <i class="fas fa-calendar-plus mr-2 text-purple-500"></i>Issue Date *
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="issue_date" readonly type="date" name="issue_date" required value="{{ old("issue_date") ?? ($guarantee->issue_date ? \Carbon\Carbon::parse($guarantee->issue_date)->format("Y-m-d") : "") }}">
                            </div>

                            <!-- Cover Start Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_start_date">
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>Cover Start Date (Optional)
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_start_date" readonly type="date" name="cover_start_date" value="{{ old("cover_start_date") ?? ($guarantee->cover_start_date ? \Carbon\Carbon::parse($guarantee->cover_start_date)->format("Y-m-d") : "") }}">
                            </div>

                            <!-- Cover End Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_end_date">
                                    <i class="fas fa-calendar-times mr-2 text-red-500"></i>Cover End Date (Optional)
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_end_date" readonly type="date" name="cover_end_date" value="{{ old("cover_end_date") ?? ($guarantee->cover_end_date ? \Carbon\Carbon::parse($guarantee->cover_end_date)->format("Y-m-d") : "") }}">

                            </div>

                            <!-- Total Price -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="total_price">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Total Price (Optional)
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="total_price" readonly type="text" name="total_price" placeholder="Enter Total Price" value="{{ old("total_price") ?? $guarantee->total_price }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coverage Area/Specialty Content -->
                <div class="flex flex-col gap-6 lg:flex-row">
                    <!-- Left Panel - Details -->
                    <div class="rounded-lg bg-white shadow-sm">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">Coverage Area / Detail</h2>
                        </div>
                        <div class="space-y-6 p-6">
                            <!-- Detail Entries -->
                            <div class="border-t border-gray-200 pt-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="text-md font-semibold text-gray-900">
                                        <i class="fas fa-list mr-2 text-green-500"></i>Additional Information
                                    </h3>
                                </div>

                                <div class="space-y-6" id="details-container">
                                    <!-- Initial detail entry -->
                                    <div class="detail-entry rounded-lg border border-gray-200 bg-white p-4">
                                        <div class="mb-4 flex items-center justify-between">
                                            <h4 class="text-sm font-semibold text-gray-800">
                                                <i class="fas fa-file-alt mr-2 text-blue-500"></i>Detail Entry
                                            </h4>
                                            <button class="text-red-500 transition-colors hover:text-red-700" type="button" onclick="removeDetail(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <!-- Case Selection -->
                                            <div class="md:col-span-2">
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-list mr-2 text-blue-500"></i>Case *
                                                </label>
                                                <select class="@error("details.0.case_id") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="details[0][case_id]" required>
                                                    <option value="">Select a case</option>
                                                    @foreach ($additionalCases as $case)
                                                        <option value="{{ $case->id }}" {{ old("details.0.case_id") == $case->id ? "selected" : "" }}>{{ $case->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("details.0.case_id")
                                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Date Type Selection -->
                                            <div class="md:col-span-2">
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>Date Type
                                                </label>
                                                <div class="flex space-x-4">
                                                    <label class="flex items-center">
                                                        <input class="mr-2" type="radio" name="details[0][date_type]" value="range" checked onchange="toggleDateType(this, 0)">
                                                        <span class="text-sm text-gray-700">Date Range</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input class="mr-2" type="radio" name="details[0][date_type]" value="specific" onchange="toggleDateType(this, 0)">
                                                        <span class="text-sm text-gray-700">Specific Dates</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Date Range Fields -->
                                            <div class="date-range-fields md:col-span-2">
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                    <div>
                                                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                            <i class="fas fa-calendar-plus mr-2 text-green-500"></i>Start Date
                                                        </label>
                                                        <input class="@error("details.0.date_start") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[0][date_start]" value="{{ old("details.0.date_start") }}">
                                                        @error("details.0.date_start")
                                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                            <i class="fas fa-calendar-times mr-2 text-red-500"></i>End Date
                                                        </label>
                                                        <input class="@error("details.0.date_end") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[0][date_end]" value="{{ old("details.0.date_end") }}">
                                                        @error("details.0.date_end")
                                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Specific Dates Fields -->
                                            <div class="specific-dates-fields hidden md:col-span-2">
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>Specific Dates
                                                </label>
                                                <div class="specific-dates-container space-y-2">
                                                    <div class="flex items-center space-x-2">
                                                        <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[0][specific_dates][]" value="{{ old("details.0.specific_dates.0") }}">
                                                        <button class="rounded-lg bg-green-500 px-3 py-2 text-white transition-colors hover:bg-green-600" type="button" onclick="addSpecificDate(this)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button class="rounded-lg bg-red-500 px-3 py-2 text-white transition-colors hover:bg-red-600" type="button" onclick="removeSpecificDate(this)">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail -->
                                            <div class="md:col-span-2">
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-file-alt mr-2 text-green-500"></i>Detail *
                                                </label>
                                                <textarea class="@error("details.0.detail") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="details[0][detail]" rows="3" required placeholder="Enter detail description">{{ old("details.0.detail") }}</textarea>
                                                @error("details.0.detail")
                                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Definition -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-book mr-2 text-indigo-500"></i>Definition
                                                </label>
                                                <input class="@error("details.0.definition") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[0][definition]" placeholder="Enter definition" value="{{ old("details.0.definition") }}">
                                                @error("details.0.definition")
                                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Amount -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-calculator mr-2 text-yellow-500"></i>Amount
                                                </label>
                                                <input class="@error("details.0.amount") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[0][amount]" placeholder="Enter amount (e.g., 100,000)" value="{{ old("details.0.amount") }}">
                                                @error("details.0.amount")
                                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Price -->
                                            <div>
                                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                                    <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Price
                                                </label>
                                                <input class="@error("details.0.price") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[0][price]" placeholder="Enter price" value="{{ old("details.0.price") }}">
                                                @error("details.0.price")
                                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="mt-4 w-full rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700" type="button" onclick="addDetail()">
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
                                @if ($guarantee->file)
                                    @foreach ($guarantee->file as $file)
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
                                        <p class="text-gray-500">No file available</p>
                                        <p class="mt-1 text-sm text-gray-400">No file was uploaded for this guarantee</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <a class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50" href="{{ route("patients.view", $patient->hn) }}">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Patient
                    </a>
                    <button class="rounded-lg bg-blue-500 px-6 py-3 text-white transition-colors hover:bg-blue-600" type="submit">
                        <i class="fas fa-save mr-2"></i>Update Guarantee
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        let detailIndex = 1;

        function addDetail() {
            const container = document.getElementById('details-container');
            const newDetail = `
            <div class="detail-entry rounded-lg border border-gray-200 bg-white p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-800">
                        <i class="fas fa-file-alt mr-2 text-blue-500"></i>Detail Entry
                    </h4>
                    <button type="button" onclick="removeDetail(this)" class="text-red-500 hover:text-red-700 transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Case Selection -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-list mr-2 text-blue-500"></i>Case *
                        </label>
                        <select class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="details[${detailIndex}][case_id]" required>
                            <option value="">Select a case</option>
                            @foreach ($additionalCases as $case)
                                <option value="{{ $case->id }}">{{ $case->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Type Selection -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar mr-2 text-purple-500"></i>Date Type
                        </label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input class="mr-2" type="radio" name="details[${detailIndex}][date_type]" value="range" checked onchange="toggleDateType(this, ${detailIndex})">
                                <span class="text-sm text-gray-700">Date Range</span>
                            </label>
                            <label class="flex items-center">
                                <input class="mr-2" type="radio" name="details[${detailIndex}][date_type]" value="specific" onchange="toggleDateType(this, ${detailIndex})">
                                <span class="text-sm text-gray-700">Specific Dates</span>
                            </label>
                        </div>
                    </div>

                    <!-- Date Range Fields -->
                    <div class="date-range-fields md:col-span-2">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar-plus mr-2 text-green-500"></i>Start Date
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${detailIndex}][date_start]">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar-times mr-2 text-red-500"></i>End Date
                                </label>
                                <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${detailIndex}][date_end]">
                            </div>
                        </div>
                    </div>

                    <!-- Specific Dates Fields -->
                    <div class="specific-dates-fields hidden md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar mr-2 text-purple-500"></i>Specific Dates
                        </label>
                        <div class="specific-dates-container space-y-2">
                            <div class="flex items-center space-x-2">
                                <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${detailIndex}][specific_dates][]">
                                <button type="button" onclick="addSpecificDate(this)" class="rounded-lg bg-green-500 px-3 py-2 text-white transition-colors hover:bg-green-600">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" onclick="removeSpecificDate(this)" class="rounded-lg bg-red-500 px-3 py-2 text-white transition-colors hover:bg-red-600">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Detail -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-file-alt mr-2 text-green-500"></i>Detail *
                        </label>
                        <textarea class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" name="details[${detailIndex}][detail]" rows="3" required placeholder="Enter detail description"></textarea>
                    </div>

                    <!-- Definition -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-book mr-2 text-indigo-500"></i>Definition
                        </label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[${detailIndex}][definition]" placeholder="Enter definition">
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calculator mr-2 text-yellow-500"></i>Amount
                        </label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[${detailIndex}][amount]" placeholder="Enter amount (e.g., 100,000)">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Price
                        </label>
                        <input class="w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="text" name="details[${detailIndex}][price]" placeholder="Enter price">
                    </div>
                </div>
            </div>
            `;

            container.insertAdjacentHTML('beforeend', newDetail);
            detailIndex++;
        }

        function removeDetail(button) {
            const detailEntry = button.closest('.detail-entry');
            const container = document.getElementById('details-container');

            // Prevent removing the last detail entry
            if (container.children.length > 1) {
                detailEntry.remove();
            } else {
                alert('At least one detail entry is required.');
            }
        }

        function toggleDateType(radio, index) {
            const detailEntry = radio.closest('.detail-entry');
            const dateRangeFields = detailEntry.querySelector('.date-range-fields');
            const specificDatesFields = detailEntry.querySelector('.specific-dates-fields');

            if (radio.value === 'range') {
                dateRangeFields.classList.remove('hidden');
                specificDatesFields.classList.add('hidden');
            } else {
                dateRangeFields.classList.add('hidden');
                specificDatesFields.classList.remove('hidden');
            }
        }

        function addSpecificDate(button) {
            const container = button.closest('.specific-dates-container');
            const detailEntry = button.closest('.detail-entry');
            const detailIndex = Array.from(detailEntry.parentNode.children).indexOf(detailEntry);

            const newDateField = `
            <div class="flex items-center space-x-2">
                <input class="flex-1 rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" type="date" name="details[${detailIndex}][specific_dates][]">
                <button type="button" onclick="addSpecificDate(this)" class="rounded-lg bg-green-500 px-3 py-2 text-white transition-colors hover:bg-green-600">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" onclick="removeSpecificDate(this)" class="rounded-lg bg-red-500 px-3 py-2 text-white transition-colors hover:bg-red-600">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            `;

            container.insertAdjacentHTML('beforeend', newDateField);
        }

        function removeSpecificDate(button) {
            const container = button.closest('.specific-dates-container');
            const dateField = button.closest('.flex');

            // Prevent removing the last specific date field
            if (container.children.length > 1) {
                dateField.remove();
            } else {
                alert('At least one specific date field is required.');
            }
        }
    </script>
@endsection
