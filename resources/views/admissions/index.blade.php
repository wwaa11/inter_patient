@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Admissions</h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400">Manage admission forms</p>
                    </div>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admissions.create') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white shadow-sm transition-colors duration-200 hover:bg-emerald-700">
                            <i class="fa-solid fa-plus mr-2"></i>
                            New Admission
                        </a>
                    @endif
                </div>
            </div>

            @if (session('success') || session('error'))
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

            <form action="{{ route('admissions.index') }}" method="GET" class="mb-6 flex flex-wrap items-end gap-x-4 gap-y-6 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
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
                        @foreach (\App\Models\Admission::caseStatusOptions() as $opt)
                            <option value="{{ $opt }}" {{ request('case_status') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admissions.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">Reset</a>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">HN</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Name</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Admission Date</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Department</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Admitting Status</th>
                                <th class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Case Status</th>
                                <th class="relative whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                            @forelse ($admissions as $adm)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">{{ $adm->hn }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700 dark:text-slate-300">{{ $adm->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $adm->admission_date?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $adm->department ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $adm->admitting_status ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $statusClass = $adm->case_status === \App\Models\Admission::CASE_STATUS_CLOSE ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $adm->case_status ?? '—' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium flex items-center gap-2">
                                        <a href="{{ route('admissions.show', $adm) }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300">
                                            <i class="fa-solid fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-12 text-center" colspan="7">
                                        <div class="text-center text-slate-500 dark:text-slate-400">
                                            <i class="fa-solid fa-folder-open mb-4 text-4xl"></i>
                                            <p class="text-lg font-medium">No admissions found</p>
                                            <p class="mt-1">Create your first admission or adjust filters.</p>
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
