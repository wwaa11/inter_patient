@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('providers.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-sm transition-colors hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Edit Provider</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Update provider details</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <form action="{{ route('providers.update', $provider) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Provider Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $provider->name) }}" required
                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Provider Type</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                                <option value="">Select a type</option>
                                <option value="Insurance Company" {{ old('type') == 'Insurance Company' ? 'selected' : '' }}>Insurance Company</option>
                                <option value="Assistance Company" {{ old('type') == 'Assistance Company' ? 'selected' : '' }}>Assistance Company</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="detail" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Provider Detail</label>
                            <textarea name="detail" id="detail" rows="4"
                                class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">{{ old('detail', $provider->detail) }}</textarea>
                            @error('detail')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="logo" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Provider Logo</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <div id="logo-preview-container" class="h-20 w-20 flex items-center justify-center rounded-lg border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900">
                                    @if($provider->logo)
                                        <img id="logo-preview" src="{{ asset('provider-logos/' . $provider->logo) }}" alt="Logo Preview" class="h-16 w-16 object-contain">
                                    @else
                                        <i id="logo-placeholder" class="fa-solid fa-image text-slate-400 text-2xl"></i>
                                    @endif
                                </div>
                                <input type="file" name="logo" id="logo" accept="image/*"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400">
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Allowed formats: JPEG, PNG, JPG, GIF, SVG. Max size: 2MB.</p>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('providers.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2 font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Update Provider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('logo').onchange = evt => {
            const [file] = evt.target.files
            if (file) {
                const preview = document.getElementById('logo-preview')
                if (preview) {
                    preview.src = URL.createObjectURL(file)
                } else {
                    const container = document.getElementById('logo-preview-container')
                    container.innerHTML = `<img id="logo-preview" src="${URL.createObjectURL(file)}" alt="Logo Preview" class="h-16 w-16 object-contain">`
                }
            }
        }
    </script>
@endsection
