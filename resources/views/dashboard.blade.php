@extends("layouts.app")

@section("title", "Dashboard")

@section("content")
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-8">Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Pre-authorizations Overview -->
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Pre-authorizations Overview</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                            <span class="text-slate-600 dark:text-slate-400">Total Pre-authorizations:</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $totalPreauth }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                            <span class="text-slate-600 dark:text-slate-400">Pending:</span>
                            <span class="font-medium text-orange-500">{{ $pendingPreauth }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                            <span class="text-slate-600 dark:text-slate-400">Approved:</span>
                            <span class="font-medium text-emerald-600">{{ $approvedPreauth }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 dark:text-slate-400">Rejected:</span>
                            <span class="font-medium text-red-600">{{ $rejectedPreauth }}</span>
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        <a href="{{ route('preauth.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">View All Pre-authorizations &rarr;</a>
                    </div>
                </div>

                <!-- Admissions Overview -->
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Admissions Overview</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                            <span class="text-slate-600 dark:text-slate-400">Total Admissions:</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $totalAdmissions }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                            <span class="text-slate-600 dark:text-slate-400">In Progress:</span>
                            <span class="font-medium text-blue-500">{{ $inProgressAdmissions }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200 dark:border-slate-700">
                            <span class="text-slate-600 dark:text-slate-400">Discharged:</span>
                            <span class="font-medium text-purple-600">{{ $dischargedAdmissions }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 dark:text-slate-400">Canceled:</span>
                            <span class="font-medium text-red-600">{{ $canceledAdmissions }}</span>
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        <a href="{{ route('admissions.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">View All Admissions &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
@endpush