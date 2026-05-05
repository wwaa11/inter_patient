@extends('layouts.app')

@section('title', 'Admissions Dashboard')

@section('content')
    @php
        $dailyCountsList = collect($dailyCases)->pluck('count');
        $daysInMonth = count($dailyCases);
        $dailyMaxCount = $daysInMonth ? (int) $dailyCountsList->max() : 0;
        $dailyAvgPerDay = $daysInMonth ? round($dailyCountsList->sum() / $daysInMonth, 1) : 0;
        $dailyActiveDays = $daysInMonth ? $dailyCountsList->filter(fn ($c) => $c > 0)->count() : 0;
        $peakDayRow = $daysInMonth ? collect($dailyCases)->sortByDesc('count')->first() : null;
        $maxProviderVolume = count($providersSortedHighToLow) > 0 ? $providerTotalsForPivot->max() : 0;
        $providerPeriodSum = $providerTotalsForPivot->sum();
    @endphp

    <div class="min-h-screen bg-slate-100/90 pb-16 pt-8 dark:bg-slate-950 dark:pb-20">
        <div class="mx-auto max-w-7xl space-y-10 px-4 sm:px-6 lg:px-8">

            <header
                class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900 dark:ring-slate-700/80 sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0 space-y-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                                Dashboard</p>
                            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                                Admissions</h1>
                        </div>
                        <p class="max-w-xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            Metrics use <span class="font-medium text-slate-800 dark:text-slate-200">admission date</span>
                            inside the range below unless noted (e.g. outstanding queues are live backlog).
                        </p>
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                <span class="tabular-nums">{{ $startDate }}</span>
                                <span class="mx-1.5 text-slate-400">→</span>
                                <span class="tabular-nums">{{ $endDate }}</span>
                            </span>
                        </div>
                        <nav class="flex flex-wrap gap-x-4 gap-y-1 border-t border-slate-100 pt-4 text-xs font-medium dark:border-slate-800">
                            <a href="#overview"
                                class="text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400">Overview</a>
                            <a href="#daily-volume"
                                class="text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400">Daily
                                volume</a>
                            <a href="#handlers"
                                class="text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400">Handling
                                users</a>
                            <a href="#providers"
                                class="text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400">Providers</a>
                            <a href="#attention"
                                class="text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400">Attention</a>
                        </nav>
                    </div>
                    <form action="{{ route('dashboard.admissions') }}" method="GET"
                        class="flex shrink-0 flex-col gap-4 rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/50 sm:flex-row sm:flex-wrap sm:items-end">
                        <div class="flex flex-col gap-1">
                            <label for="month"
                                class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Month</label>
                            <select name="month" id="month"
                                class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" @selected((int) $month === $m)>
                                        {{ \Carbon\Carbon::createFromDate(2000, $m, 1)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="year"
                                class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Year</label>
                            <select name="year" id="year"
                                class="rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @for ($y = now()->year + 1; $y >= 2020; $y--)
                                    <option value="{{ $y }}" @selected((int) $year === $y)>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                                Apply
                            </button>
                            <button type="submit" formaction="{{ route('dashboard.admissions.export') }}" formmethod="get"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 dark:focus:ring-offset-slate-900"
                                title="UTF-8 CSV for Raw Data (same month/year as selected)">
                                Export CSV (Raw Data)
                            </button>
                        </div>
                    </form>
                </div>
            </header>

            <section id="overview" class="scroll-mt-6">
                <div class="mb-4 flex items-center gap-3">
                    <span class="h-7 w-1 shrink-0 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Overview</h2>
                </div>
                <div
                    class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900 dark:ring-slate-700/80">
                    <div class="grid gap-px bg-slate-200 dark:bg-slate-700 sm:grid-cols-3">
                        <div class="bg-white p-6 dark:bg-slate-900">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Total admissions</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $totalAdmissions }}
                            </p>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">In selected month</p>
                        </div>
                        <div class="bg-amber-50/90 p-6 dark:bg-amber-950/25">
                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-900 dark:text-amber-200">Pending
                                &gt; 7 days</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-amber-950 dark:text-amber-100">
                                {{ $pendingOver7Days->count() }}</p>
                            <p class="mt-2 text-xs text-amber-800/90 dark:text-amber-200/80">Sent out, no initial GOP (live)</p>
                        </div>
                        <div class="bg-red-50/90 p-6 dark:bg-red-950/25">
                            <p class="text-xs font-semibold uppercase tracking-wide text-red-900 dark:text-red-200">Pending
                                &gt; 10 days</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-red-950 dark:text-red-100">
                                {{ $pendingOver10Days->count() }}</p>
                            <p class="mt-2 text-xs text-red-800/90 dark:text-red-200/80">Sent out, no initial GOP (live)</p>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 p-6 dark:border-slate-800">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Admitting
                            status</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @forelse ($admittingStatus as $row)
                                <span
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm dark:border-slate-700 dark:bg-slate-800/80">
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $row->admitting_status ?? '—' }}</span>
                                    <span class="tabular-nums font-semibold text-emerald-600 dark:text-emerald-400">{{ $row->count }}</span>
                                </span>
                            @empty
                                <span class="text-sm text-slate-500 dark:text-slate-400">No data for this period.</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="border-t border-slate-100 p-6 dark:border-slate-800">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Case status</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @forelse ($caseStatusCounts as $cs)
                                <span
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm dark:border-slate-700 dark:bg-slate-800/80">
                                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $cs->case_status ?? '—' }}</span>
                                    <span class="tabular-nums font-semibold text-emerald-600 dark:text-emerald-400">{{ $cs->count }}</span>
                                </span>
                            @empty
                                <span class="text-sm text-slate-500 dark:text-slate-400">No data for this period.</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="border-t border-slate-100 p-6 dark:border-slate-800">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">GOP /
                            pre-certification status</p>
                        @if ($gopStatusData->isEmpty())
                            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">No GOP status breakdown for this period.</p>
                        @else
                            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                @foreach ($gopStatusData as $gop)
                                    <div
                                        class="rounded-xl border border-slate-100 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-800/40">
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                            {{ $gop->gop_pre_certification_status ?? '—' }}</p>
                                        <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $gop->count }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section id="daily-volume" class="scroll-mt-6">
                <div class="mb-4 flex items-center gap-3">
                    <span class="h-7 w-1 shrink-0 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Daily volume</h2>
                </div>
                <div
                    class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900 dark:ring-slate-700/80">
                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800 sm:px-8">
                        <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">Admissions per calendar day by
                            <span class="font-medium text-slate-800 dark:text-slate-200">admission date</span>.</p>
                        <dl class="mt-4 flex flex-wrap gap-3">
                            <div
                                class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 dark:border-slate-800 dark:bg-slate-800/50">
                                <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Month total</dt>
                                <dd class="text-lg font-bold tabular-nums text-slate-900 dark:text-white">{{ $totalAdmissions }}
                                </dd>
                            </div>
                            <div
                                class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 dark:border-slate-800 dark:bg-slate-800/50">
                                <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Daily avg</dt>
                                <dd class="text-lg font-bold tabular-nums text-slate-900 dark:text-white">{{ $dailyAvgPerDay }}
                                </dd>
                            </div>
                            <div
                                class="rounded-xl border border-emerald-100 bg-emerald-50/80 px-3 py-2 dark:border-emerald-900/50 dark:bg-emerald-950/30">
                                <dt
                                    class="text-[11px] font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">
                                    Peak day</dt>
                                <dd class="text-lg font-bold tabular-nums text-emerald-950 dark:text-emerald-100">
                                    @if ($peakDayRow && $dailyMaxCount > 0)
                                        <span class="whitespace-nowrap">{{ $peakDayRow['date']->format('D j M') }}</span>
                                        <span class="text-base font-semibold text-emerald-600 dark:text-emerald-400">({{ $dailyMaxCount }})</span>
                                    @else
                                        —
                                    @endif
                                </dd>
                            </div>
                            <div
                                class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 dark:border-slate-800 dark:bg-slate-800/50">
                                <dt class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Active days</dt>
                                <dd class="text-lg font-bold tabular-nums text-slate-900 dark:text-white">{{ $dailyActiveDays }}
                                    <span class="text-sm font-normal text-slate-500">/ {{ $daysInMonth }}</span></dd>
                            </div>
                        </dl>
                    </div>
                    <div class="px-4 pb-2 pt-6 sm:px-8">
                        <div class="relative h-56 w-full sm:h-64 lg:h-72">
                            <canvas id="dailyAdmissionsChart" aria-label="Admissions per day chart"></canvas>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 dark:border-slate-800">
                        <div class="px-6 py-3 dark:bg-slate-900/50 sm:px-8">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Day-by-day
                                detail</span>
                        </div>
                        <div class="overflow-x-auto max-h-64 overflow-y-auto px-2 pb-4 sm:px-6">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50/90 dark:border-slate-700 dark:bg-slate-800/80">
                                        <th scope="col"
                                            class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                            Date</th>
                                        <th scope="col"
                                            class="py-3 px-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums">
                                            Admissions</th>
                                        <th scope="col"
                                            class="hidden py-3 pl-3 pr-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums sm:table-cell">
                                            Share</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach ($dailyCases as $dayRow)
                                        @php
                                            $sharePct =
                                                $totalAdmissions > 0
                                                    ? round((100 * $dayRow['count']) / $totalAdmissions, 1)
                                                    : 0;
                                            $isPeak =
                                                $dailyMaxCount > 0 && (int) $dayRow['count'] === (int) $dailyMaxCount;
                                        @endphp
                                        <tr
                                            class="{{ $isPeak ? 'bg-emerald-50/80 dark:bg-emerald-950/20' : 'even:bg-slate-50/40 dark:even:bg-slate-800/20' }}">
                                            <td class="py-2.5 pl-4 pr-3 text-slate-800 dark:text-slate-200">
                                                <span class="font-medium">{{ $dayRow['date']->format('l') }}</span>
                                                <span
                                                    class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400 sm:ml-2 sm:mt-0 sm:inline sm:text-sm">{{ $dayRow['date']->format('j M Y') }}</span>
                                            </td>
                                            <td
                                                class="py-2.5 px-3 text-right text-sm font-semibold tabular-nums text-slate-900 dark:text-white">
                                                {{ $dayRow['count'] }}</td>
                                            <td
                                                class="hidden py-2.5 pl-3 pr-4 text-right text-sm tabular-nums text-slate-600 dark:text-slate-400 sm:table-cell">
                                                {{ $sharePct }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section id="handlers" class="scroll-mt-6">
                <div class="mb-4 flex items-center gap-3">
                    <span class="h-7 w-1 shrink-0 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Handling users</h2>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Send-out → initial GOP for assigned
                            admissions</p>
                    </div>
                </div>
                <div
                    class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900 dark:ring-slate-700/80">
                    <div class="border-b border-slate-100 px-6 py-4 dark:border-slate-800 sm:px-8">
                        <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400"><span
                                class="font-medium text-slate-800 dark:text-slate-200">Total</span> counts each assignment on the
                            admission. Response metrics use send-out and <span class="font-medium">initial GOP receiving</span>
                            dates.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                            <thead class="bg-slate-50 dark:bg-slate-800/90">
                                <tr>
                                    <th scope="col"
                                        class="whitespace-nowrap py-3 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 sm:pl-8">
                                        User</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-3 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums">
                                        Total</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-3 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums">
                                        Sent out</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-3 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums">
                                        Avg days</th>
                                    <th scope="col"
                                        class="whitespace-nowrap px-3 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums">
                                        Response</th>
                                    <th scope="col"
                                        class="whitespace-nowrap py-3 pl-3 pr-6 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums sm:pr-8">
                                        Send-out %</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse ($handlingUserKpis as $row)
                                    <tr class="even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                        <td class="whitespace-nowrap py-3 pl-6 pr-3 font-medium text-slate-900 dark:text-white sm:pl-8">
                                            {{ $row->name }}</td>
                                        <td class="whitespace-nowrap px-3 py-3 text-right tabular-nums text-slate-900 dark:text-white">
                                            {{ $row->total_cases }}</td>
                                        <td class="whitespace-nowrap px-3 py-3 text-right tabular-nums text-slate-600 dark:text-slate-300">
                                            {{ $row->sent_out_total }}</td>
                                        <td class="whitespace-nowrap px-3 py-3 text-right tabular-nums text-slate-600 dark:text-slate-300">
                                            {{ $row->avg_response_days !== null ? $row->avg_response_days . ' d' : '—' }}</td>
                                        <td class="whitespace-nowrap px-3 py-3 text-right tabular-nums text-slate-600 dark:text-slate-300">
                                            {{ $row->response_rate }}%</td>
                                        <td class="whitespace-nowrap py-3 pl-3 pr-6 text-right tabular-nums text-slate-600 dark:text-slate-300 sm:pr-8">
                                            {{ $row->send_out_share_pct }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                            No handling users for admissions in this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="providers" class="scroll-mt-6">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-7 w-1 shrink-0 rounded-full bg-emerald-500" aria-hidden="true"></span>
                        <div>
                            <h2 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Contact providers by
                                volume</h2>
                            <p class="mt-1 max-w-3xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">Sorted by
                                admission count linked via contact providers. <span
                                    class="font-medium text-slate-800 dark:text-slate-200">Response rate</span> is initial GOP
                                received ÷ rows with send-out (per provider linkage).</p>
                        </div>
                    </div>
                    @if ($providerPeriodSum > 0 && count($providersSortedHighToLow) > 0)
                        <p class="shrink-0 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 sm:text-sm">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $providersSortedHighToLow[0] }}</span>
                            leads —
                            <span class="tabular-nums font-semibold text-emerald-600 dark:text-emerald-400">{{ $providerTotalsForPivot[$providersSortedHighToLow[0]] }}</span>
                            <span class="text-slate-400">({{ round((100 * $providerTotalsForPivot[$providersSortedHighToLow[0]]) / $providerPeriodSum, 1) }}%)</span>
                        </p>
                    @endif
                </div>

                @if (count($providersSortedHighToLow) > 0 && count($departmentsForPivot) > 0)
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($providersSortedHighToLow as $providerName)
                            @php
                                $providerTotal = $providerTotalsForPivot[$providerName];
                                $breakdown = collect($departmentsForPivot)
                                    ->map(function ($dept) use ($providerName, $providerDeptPivot) {
                                        return [
                                            'name' => $dept,
                                            'count' => (int) ($providerDeptPivot[$providerName][$dept] ?? 0),
                                        ];
                                    })
                                    ->sortByDesc('count')
                                    ->values();
                                $nonZero = $breakdown->filter(fn ($r) => $r['count'] > 0);
                                $barPct =
                                    $maxProviderVolume > 0 ? round((100 * $providerTotal) / $maxProviderVolume) : 0;
                                $providerResponseRate = $providerResponseRates[$providerName] ?? null;
                            @endphp
                            <article
                                class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900 dark:ring-slate-700/80">
                                <div class="flex gap-3 border-b border-slate-100 p-4 dark:border-slate-800">
                                    <span
                                        class="flex h-9 min-w-9 items-center justify-center rounded-xl bg-slate-100 text-xs font-bold tabular-nums text-slate-700 dark:bg-slate-800 dark:text-slate-200">#{{ $loop->iteration }}</span>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="break-words font-semibold leading-snug text-slate-900 dark:text-white">
                                            {{ $providerName }}</h3>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Relative to top provider</p>
                                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                            <div class="h-full rounded-full bg-emerald-500 dark:bg-emerald-600"
                                                style="width: {{ $barPct }}%"></div>
                                        </div>
                                    </div>
                                    <div class="shrink-0 space-y-2 text-right">
                                        <div>
                                            <p class="text-2xl font-bold tabular-nums text-slate-900 dark:text-white">
                                                {{ $providerTotal }}</p>
                                            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">rows</p>
                                        </div>
                                        @if ($providerResponseRate !== null)
                                            <div
                                                class="rounded-xl border border-sky-200 bg-sky-50 px-2.5 py-1.5 dark:border-sky-800 dark:bg-sky-950/50">
                                                <p class="text-base font-bold tabular-nums text-sky-800 dark:text-sky-200">
                                                    {{ $providerResponseRate }}<span class="text-xs font-semibold">%</span></p>
                                                <p class="text-[10px] font-semibold uppercase tracking-wide text-sky-600 dark:text-sky-400">
                                                    response</p>
                                            </div>
                                        @else
                                            <p class="max-w-[7rem] text-[11px] leading-snug text-slate-400 dark:text-slate-500">
                                                No send-outs — N/A</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 p-4 pt-3">
                                    <p class="mb-2 text-[11px] font-semibold uppercase tracking-wide text-slate-400">By
                                        department</p>
                                    @if ($nonZero->isEmpty())
                                        <p class="text-sm text-slate-500 dark:text-slate-400">No breakdown.</p>
                                    @else
                                        <ul class="space-y-2">
                                            @foreach ($nonZero as $row)
                                                @php
                                                    $share =
                                                        $providerTotal > 0
                                                            ? round((100 * $row['count']) / $providerTotal)
                                                            : 0;
                                                @endphp
                                                <li class="flex items-baseline justify-between gap-3 text-sm">
                                                    <span class="truncate text-slate-700 dark:text-slate-300"
                                                        title="{{ $row['name'] }}">{{ $row['name'] }}</span>
                                                    <span class="flex shrink-0 items-center gap-2 tabular-nums">
                                                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $share }}%</span>
                                                        <span class="min-w-[2rem] text-right font-semibold text-slate-900 dark:text-white">{{ $row['count'] }}</span>
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/80 px-6 py-14 text-center dark:border-slate-700 dark:bg-slate-900/40">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No provider-linked admissions for this period.</p>
                    </div>
                @endif
            </section>

            <section id="attention" class="scroll-mt-6 pb-4">
                <div class="mb-4 flex items-center gap-3">
                    <span class="h-7 w-1 shrink-0 rounded-full bg-amber-500" aria-hidden="true"></span>
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Needs attention</h2>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Outstanding send-outs without initial GOP
                            (live backlog)</p>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <div
                        class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-amber-200/90 dark:bg-slate-900 dark:ring-amber-900/40">
                        <div class="border-b border-amber-100 bg-amber-50/80 px-6 py-4 dark:border-amber-900/30 dark:bg-amber-950/25">
                            <h3 class="font-semibold text-amber-950 dark:text-amber-100">Over 7 days waiting</h3>
                            <p class="mt-1 text-xs text-amber-900/80 dark:text-amber-200/80">Days since send-out</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                                <thead class="bg-slate-50 dark:bg-slate-800/80">
                                    <tr>
                                        <th scope="col"
                                            class="py-3 pl-6 pr-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">HN</th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Patient</th>
                                        <th scope="col"
                                            class="hidden px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 md:table-cell">Dept</th>
                                        <th scope="col"
                                            class="hidden px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 lg:table-cell">Providers</th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Sent</th>
                                        <th scope="col"
                                            class="px-2 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums">Days</th>
                                        <th scope="col" class="py-3 pl-2 pr-6 text-right sm:pr-8"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse ($pendingOver7Days as $adm)
                                        @php
                                            $waitDays = $adm->sent_out_date
                                                ? $adm->sent_out_date->diffInDays(now()->startOfDay())
                                                : null;
                                            $provList = $adm->contactProviders->pluck('name')->join(', ');
                                        @endphp
                                        <tr class="even:bg-slate-50/40 dark:even:bg-slate-800/20">
                                            <td class="py-2.5 pl-6 pr-2 font-medium text-slate-900 dark:text-white">{{ $adm->hn }}</td>
                                            <td class="max-w-[10rem] truncate px-2 py-2.5 text-slate-600 dark:text-slate-300">
                                                {{ $adm->name ?? '—' }}</td>
                                            <td class="hidden max-w-[8rem] truncate px-2 py-2.5 text-slate-600 dark:text-slate-300 md:table-cell">
                                                {{ $adm->department ?? '—' }}</td>
                                            <td class="hidden max-w-[10rem] truncate px-2 py-2.5 text-slate-600 dark:text-slate-300 lg:table-cell"
                                                title="{{ $provList }}">{{ $provList !== '' ? $provList : '—' }}</td>
                                            <td class="whitespace-nowrap px-2 py-2.5 tabular-nums text-slate-600 dark:text-slate-300">
                                                {{ $adm->sent_out_date?->format('Y-m-d') ?? '—' }}</td>
                                            <td class="px-2 py-2.5 text-right text-sm font-semibold tabular-nums text-amber-800 dark:text-amber-200">
                                                {{ $waitDays }}</td>
                                            <td class="py-2.5 pl-2 pr-6 text-right sm:pr-8">
                                                <a href="{{ route('admissions.show', $adm) }}"
                                                    class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">None over 7 days.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-red-200/90 dark:bg-slate-900 dark:ring-red-900/40">
                        <div class="border-b border-red-100 bg-red-50/80 px-6 py-4 dark:border-red-900/30 dark:bg-red-950/25">
                            <h3 class="font-semibold text-red-950 dark:text-red-100">Over 10 days waiting</h3>
                            <p class="mt-1 text-xs text-red-900/80 dark:text-red-200/80">Longest outstanding</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                                <thead class="bg-slate-50 dark:bg-slate-800/80">
                                    <tr>
                                        <th scope="col"
                                            class="py-3 pl-6 pr-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">HN</th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Patient</th>
                                        <th scope="col"
                                            class="hidden px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 md:table-cell">Dept</th>
                                        <th scope="col"
                                            class="hidden px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 lg:table-cell">Providers</th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Sent</th>
                                        <th scope="col"
                                            class="px-2 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 tabular-nums">Days</th>
                                        <th scope="col" class="py-3 pl-2 pr-6 text-right sm:pr-8"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse ($pendingOver10Days as $adm)
                                        @php
                                            $waitDays10 = $adm->sent_out_date
                                                ? $adm->sent_out_date->diffInDays(now()->startOfDay())
                                                : null;
                                            $provList10 = $adm->contactProviders->pluck('name')->join(', ');
                                        @endphp
                                        <tr class="even:bg-slate-50/40 dark:even:bg-slate-800/20">
                                            <td class="py-2.5 pl-6 pr-2 font-medium text-slate-900 dark:text-white">{{ $adm->hn }}</td>
                                            <td class="max-w-[10rem] truncate px-2 py-2.5 text-slate-600 dark:text-slate-300">
                                                {{ $adm->name ?? '—' }}</td>
                                            <td class="hidden max-w-[8rem] truncate px-2 py-2.5 text-slate-600 dark:text-slate-300 md:table-cell">
                                                {{ $adm->department ?? '—' }}</td>
                                            <td class="hidden max-w-[10rem] truncate px-2 py-2.5 text-slate-600 dark:text-slate-300 lg:table-cell"
                                                title="{{ $provList10 }}">{{ $provList10 !== '' ? $provList10 : '—' }}</td>
                                            <td class="whitespace-nowrap px-2 py-2.5 tabular-nums text-slate-600 dark:text-slate-300">
                                                {{ $adm->sent_out_date?->format('Y-m-d') ?? '—' }}</td>
                                            <td class="px-2 py-2.5 text-right text-sm font-semibold tabular-nums text-red-800 dark:text-red-200">
                                                {{ $waitDays10 }}</td>
                                            <td class="py-2.5 pl-2 pr-6 text-right sm:pr-8">
                                                <a href="{{ route('admissions.show', $adm) }}"
                                                    class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">None over 10 days.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('dailyAdmissionsChart');
            if (!canvas)
                return;

            const labelsShort = @json(collect($dailyCases)->map(fn ($r) => $r['date']->format('j'))->values()->all());
            const labelsFull = @json(collect($dailyCases)->map(fn ($r) => $r['date']->format('l, j M Y'))->values()->all());
            const counts = @json(collect($dailyCases)->pluck('count')->values()->all());

            const dark = document.documentElement.classList.contains('dark');
            const gridColor = dark ? 'rgba(148, 163, 184, 0.18)' : 'rgba(148, 163, 184, 0.35)';
            const tickColor = dark ? '#94a3b8' : '#64748b';

            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labelsShort,
                    datasets: [{
                        label: 'Admissions',
                        data: counts,
                        backgroundColor: 'rgba(59, 130, 246, 0.62)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 4,
                        hoverBackgroundColor: 'rgba(59, 130, 246, 0.88)',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                title: function(items) {
                                    const i = items[0].dataIndex;
                                    return labelsFull[i];
                                },
                                label: function(item) {
                                    const n = item.raw;
                                    return n + ' admission' + (n !== 1 ? 's' : '');
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Day of month',
                                color: tickColor,
                                font: {
                                    size: 11,
                                },
                            },
                            ticks: {
                                color: tickColor,
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 18,
                            },
                            grid: {
                                display: false,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: tickColor,
                                precision: 0,
                            },
                            grid: {
                                color: gridColor,
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush
