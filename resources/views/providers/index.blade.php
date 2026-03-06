@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Providers</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Manage insurance providers and medical partners</p>
                    </div>
                    <a href="{{ route('providers.create') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white shadow-sm transition-colors duration-200 hover:bg-emerald-700">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Provider
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session("success") || session("error"))
                <script>
                    window.onload = function() {
                        Swal.fire({
                            icon: '{{ session("success") ? "success" : "error" }}',
                            title: '{{ session("success") ?: session("error") }}',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    };
                </script>
            @endif

            <!-- Providers Table -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Logo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Detail</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                            @forelse($providers as $index => $provider)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-medium text-slate-900 dark:text-white">#{{ $index + 1 }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if($provider->logo)
                                            <img src="{{ asset('provider-logos/' . $provider->logo) }}" alt="{{ $provider->name }}" class="h-10 w-10 object-contain rounded border border-slate-200 dark:border-slate-700">
                                        @else
                                            <div class="h-10 w-10 flex items-center justify-center bg-slate-100 dark:bg-slate-700 rounded text-slate-400">
                                                <i class="fa-solid fa-building"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                        {{ $provider->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $provider->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate">
                                        {{ $provider->detail }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('providers.edit', $provider) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <i class="fa-solid fa-edit mr-1"></i> Edit
                                            </a>
                                            <form action="{{ route('providers.destroy', $provider) }}" method="POST" class="delete-form">
                                                @csrf
                                                <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 delete-button">
                                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-12 text-center" colspan="4">
                                        <div class="text-slate-500 dark:text-slate-400">
                                            <i class="fa-solid fa-building-shield mb-4 text-4xl"></i>
                                            <p class="text-lg font-medium">No providers found</p>
                                            <p class="mt-1">Get started by adding your first provider.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
