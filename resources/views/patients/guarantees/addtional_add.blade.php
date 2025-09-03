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

            <!-- Main Content -->
            <div class="flex flex-col gap-6 lg:flex-row">
                <!-- Left Panel - Form -->
                <div class="rounded-lg bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Guarantee Information</h2>
                    </div>

                    <form class="p-6" action="{{ route("patients.guarantees.additional.store", $patient->hn) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">

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

                            <!-- Embassy Reference -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="embassy_ref">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Embassy Reference
                                </label>
                                <input class="@error("embassy_ref") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="embassy_ref" type="text" name="embassy_ref" required placeholder="Enter embassy reference" value="{{ old("embassy_ref") }}">
                                @error("embassy_ref")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Number -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="number">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>Number
                                </label>
                                <input class="@error("number") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="number" type="text" name="number" placeholder="Enter Number" value="{{ old("number") }}">
                                @error("number")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- <!-- MB -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="mb">
                                    <i class="fas fa-hashtag mr-2 text-purple-500"></i>MB
                                </label>
                                <input class="@error("mb") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="mb" type="text" name="mb" placeholder="Enter MB" value="{{ old("mb") }}">
                                @error("mb")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div> --}}

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
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>Cover Start *
                                </label>
                                <input class="@error("cover_start_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_start_date" type="date" name="cover_start_date" required value="{{ old("cover_start_date") }}">
                                @error("cover_start_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover End Date -->
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-700" for="cover_end_date">
                                    <i class="fas fa-calendar-times mr-2 text-red-500"></i>Cover End *
                                </label>
                                <input class="@error("cover_end_date") border-red-500 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-blue-500" id="cover_end_date" type="date" name="cover_end_date" required value="{{ old("cover_end_date") }}">
                                @error("cover_end_date")
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
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
                        </div>
                    </form>
                </div>

                <!-- Right Panel - File Preview -->
                <div class="relative flex-1 rounded-lg bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="flex items-center text-lg font-semibold text-gray-900">
                            <i class="fas fa-eye mr-2 text-blue-500"></i>File Preview
                        </h2>
                    </div>
                    <div class="m-auto p-6">
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
        </div>
    </div>
@endsection
@push("scripts")
    <script>
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
                        preview.innerHTML = `<img class="p-6 h-auto max-w-full rounded-lg" src="${e.target.result}" alt="Preview">`;
                    };
                    reader.readAsDataURL(file);
                } else if (fileType === 'application/pdf') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<embed class="p-6 absolute bottom-0 left-0 right-0 min-h-[700px] w-full rounded-lg" src="${e.target.result}" type="application/pdf">`;
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
