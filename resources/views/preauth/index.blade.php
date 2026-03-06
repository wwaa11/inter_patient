@extends("layouts.app")

@section("content")
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Pre Authorization</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Manage pre-authorization cases</p>
                    </div>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('preauth.create') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white shadow-sm transition-colors duration-200 hover:bg-emerald-700">
                            <i class="fa-solid fa-plus mr-2"></i>
                            New Pre Authorization
                        </a>
                    @endif
                </div>
            </div>

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

            {{-- Search & Filter --}}
            <form action="{{ route('preauth.index') }}" method="GET" class="mb-6 flex flex-wrap items-end gap-x-4 gap-y-6 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                <div class="min-w-0 flex-1 sm:w-48">
                    <label for="hn" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Search by HN</label>
                    <input type="text" name="hn" id="hn" value="{{ request('hn') }}" placeholder="HN..."
                        class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                </div>
                <div class="min-w-0 flex-1 sm:w-56">
                    <label for="case_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Case Status</label>
                    <select name="case_status" id="case_status"
                        class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white sm:text-sm">
                        <option value="">All</option>
                        @foreach(\App\Models\PreAuthorization::caseStatusOptions() as $opt)
                            <option value="{{ $opt }}" {{ request('case_status') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>
                        Filter
                    </button>
                    <a href="{{ route('preauth.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                        Reset
                    </a>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">HN</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Patient</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Service Type</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Payor</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Date of Service</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Case Status</th>
                                <th class="relative whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                            @forelse($preAuthorizations as $pa)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">{{ $pa->hn }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700 dark:text-slate-300">{{ $pa->patient_name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $pa->serviceType?->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $pa->provider?->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $pa->date_of_service?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $statusClass = match($pa->case_status) {
                                                'Complete' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
                                                'In-Progress' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                                                'Appel' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $pa->case_status }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium flex items-center gap-2">
                                        <a href="{{ route('preauth.show', $pa) }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300">
                                            <i class="fa-solid fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-12 text-center" colspan="7">
                                        <div class="text-center text-slate-500 dark:text-slate-400">
                                            <i class="fa-solid fa-folder-open mb-4 text-4xl"></i>
                                            <p class="text-lg font-medium">No pre-authorizations found</p>
                                            <p class="mt-1">Create your first pre-authorization case or adjust filters.</p>
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
