@extends('layouts.app')

@section('title', 'Admissions Dashboard')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Admissions Dashboard</h1>

                <form action="{{ route('dashboard.admissions') }}" method="GET"
                    class="flex items-center gap-4 bg-white dark:bg-slate-800 p-2 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <label for="start_date" class="text-sm font-medium text-slate-600 dark:text-slate-400">From:</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                            class="rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="end_date" class="text-sm font-medium text-slate-600 dark:text-slate-400">To:</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                            class="rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Filter
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Admitting Status Summary -->
                @foreach ($admittingStatus as $status)
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {{ $status->admitting_status ?? 'Unspecified' }}</h3>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $status->count }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Providers Distribution -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Provider</h3>
                    <div class="h-64 relative">
                        <canvas id="providersChart"></canvas>
                    </div>
                </div>

                <!-- Department Distribution -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Department</h3>
                    <div class="h-64 relative">
                        <canvas id="departmentsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- GOP Status Distribution -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">GOP/Pre-Certification Status</h3>
                    <div class="h-64 relative">
                        <canvas id="gopStatusChart"></canvas>
                    </div>
                </div>

                <!-- Handling Users Distribution -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Cases by Handling User</h3>
                    <div class="h-64 relative">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Providers Chart
            new Chart(document.getElementById('providersChart'), {
                type: 'bar',
                data: {
                    labels: @json($providerData->pluck('provider')),
                    datasets: [{
                        label: 'Admissions',
                        data: @json($providerData->pluck('count')),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                        }
                    }
                }
            });

            // Departments Chart
            new Chart(document.getElementById('departmentsChart'), {
                type: 'pie',
                data: {
                    labels: @json($departmentData->pluck('department')),
                    datasets: [{
                        data: @json($departmentData->pluck('count')),
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });

            // GOP Status Chart
            new Chart(document.getElementById('gopStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($gopStatusData->pluck('gop_pre_certification_status')),
                    datasets: [{
                        data: @json($gopStatusData->pluck('count')),
                        backgroundColor: ['#34d399', '#f87171', '#fbbf24', '#60a5fa']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Users Chart
            new Chart(document.getElementById('usersChart'), {
                type: 'bar',
                indexAxis: 'y',
                data: {
                    labels: @json($userData->pluck('user')),
                    datasets: [{
                        label: 'Cases Handled',
                        data: @json($userData->pluck('count')),
                        backgroundColor: 'rgba(139, 92, 246, 0.6)',
                        borderColor: 'rgb(139, 92, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endpush
