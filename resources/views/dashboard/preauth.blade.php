@extends('layouts.app')

@section('title', 'Pre-Authorization Dashboard')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Pre-Authorization Dashboard</h1>

                <form action="{{ route('dashboard.preauth') }}" method="GET"
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Coverage Decisions Summary -->
                @foreach ($coverageDecisions as $decision)
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                        <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {{ $decision->coverage_decision ?? 'Unknown' }}</h3>
                        <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $decision->count }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Providers Distribution -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Top Providers</h3>
                    <div class="h-64 relative">
                        <canvas id="providersChart"></canvas>
                    </div>
                </div>

                <!-- Service Types Distribution -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Service Types</h3>
                    <div class="h-64 relative">
                        <canvas id="serviceTypesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Handling Staff Percentage -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Handling Staffs Percentage</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Staff Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Cases</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Percentage</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach ($staffCases as $staff)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                            {{ $staff->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                            {{ $staff->count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                                    <div class="bg-emerald-500 h-2 rounded-full"
                                                        style="width: {{ $staff->percentage }}%"></div>
                                                </div>
                                                <span
                                                    class="text-xs text-slate-600 dark:text-slate-400">{{ $staff->percentage }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Date Difference Analysis -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">GOP Processing Time (Days)</h3>
                    <div class="h-64 relative">
                        <canvas id="dateDiffChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Case Status by Service Type Table -->
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Service Type & Case Status Matrix</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Service Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Case Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                                    Count</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach ($caseStatusByServiceType as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                        {{ $item->service_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $item->case_status }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 text-right">
                                        {{ $item->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    labels: @json($providerServiceData->pluck('provider')),
                    datasets: [{
                        label: 'Cases by Provider',
                        data: @json($providerServiceData->pluck('count')),
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: 'rgb(16, 185, 129)',
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
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Service Types Chart
            new Chart(document.getElementById('serviceTypesChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($serviceTypeData->pluck('service_type')),
                    datasets: [{
                        data: @json($serviceTypeData->pluck('count')),
                        backgroundColor: [
                            '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899',
                            '#6366f1'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Date Diff Chart
            new Chart(document.getElementById('dateDiffChart'), {
                type: 'line',
                data: {
                    labels: @json($dateDiffs->pluck('diff')->map(fn($d) => $d . ' days')),
                    datasets: [{
                        label: 'Case Count',
                        data: @json($dateDiffs->pluck('count')),
                        borderColor: '#10b981',
                        tension: 0.1,
                        fill: true,
                        backgroundColor: 'rgba(16, 185, 129, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Processing Time'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
