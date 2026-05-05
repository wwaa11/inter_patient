<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\PreAuthorization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function preauth(Request $request)
    {
        [$rangeStart, $rangeEnd, $year, $month] = $this->preauthDashboardPeriod($request);
        $startDate = $rangeStart->format('Y-m-d');
        $endDate = $rangeEnd->format('Y-m-d');

        $query = PreAuthorization::query()->whereBetween('requested_date', [$rangeStart, $rangeEnd]);

        $totalCases = $query->clone()->count();

        $requestedDaySql = $this->requestedDateDaySql();

        $countsByDay = $query->clone()
            ->select(DB::raw($requestedDaySql.' as day'), DB::raw('count(*) as cnt'))
            ->groupBy(DB::raw($requestedDaySql))
            ->orderBy('day')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->day)->format('Y-m-d'));

        $dailyCases = [];
        $cursor = $rangeStart->copy()->startOfDay();
        $monthEnd = $rangeEnd->copy()->startOfDay();
        while ($cursor <= $monthEnd) {
            $key = $cursor->format('Y-m-d');
            $rowForDay = $countsByDay->get($key);
            $dailyCases[] = [
                'date' => $cursor->copy(),
                'count' => (int) ($rowForDay->cnt ?? 0),
            ];
            $cursor->addDay();
        }

        // Case status totals
        $caseStatusCounts = $query->clone()
            ->select('case_status', DB::raw('count(*) as count'))
            ->groupBy('case_status')
            ->orderByDesc('count')
            ->get();

        // Coverage Decision
        $coverageDecisions = $query->clone()
            ->select('coverage_decision', DB::raw('count(*) as count'))
            ->groupBy('coverage_decision')
            ->get();

        // Provider columns (high → low) with service-type sub-columns
        $providerServiceRows = $query->clone()
            ->join('providers', 'pre_authorizations.provider_id', '=', 'providers.id')
            ->join('service_types', 'pre_authorizations.service_type_id', '=', 'service_types.id')
            ->select(
                'providers.name as provider',
                'service_types.name as service_type',
                DB::raw('count(*) as cnt')
            )
            ->groupBy('providers.name', 'service_types.name')
            ->get();

        $serviceTypesForPivot = $providerServiceRows->pluck('service_type')->unique()->sort()->values()->all();
        $providerTotalsForPivot = $providerServiceRows
            ->groupBy('provider')
            ->map(fn ($group) => (int) $group->sum('cnt'))
            ->sortDesc();

        $providersSortedHighToLow = $providerTotalsForPivot->keys()->values()->all();
        $providerServicePivot = [];
        foreach ($providersSortedHighToLow as $providerName) {
            $providerServicePivot[$providerName] = array_fill_keys($serviceTypesForPivot, 0);
        }
        foreach ($providerServiceRows as $row) {
            $providerServicePivot[$row->provider][$row->service_type] = (int) $row->cnt;
        }

        $providerResponseRows = $query->clone()
            ->join('providers', 'pre_authorizations.provider_id', '=', 'providers.id')
            ->select(
                'providers.name as provider',
                DB::raw('SUM(CASE WHEN pre_authorizations.send_out_date IS NOT NULL THEN 1 ELSE 0 END) as sent_out'),
                DB::raw('SUM(CASE WHEN pre_authorizations.send_out_date IS NOT NULL AND pre_authorizations.gop_receiving_date IS NOT NULL THEN 1 ELSE 0 END) as responded')
            )
            ->groupBy('providers.name')
            ->get();

        $providerResponseRates = [];
        foreach ($providerResponseRows as $pr) {
            $sentOut = (int) $pr->sent_out;
            $providerResponseRates[$pr->provider] = $sentOut > 0
                ? round(100 * (int) $pr->responded / $sentOut, 1)
                : null;
        }

        $dayDiffSql = $this->dayDiffSendOutToGopSql();

        // Handling staff: avg days (send-out → GOP), response rate (GOP received ÷ sent out)
        $staffBaseline = DB::table('pre_authorization_handling_staff')
            ->join('pre_authorizations', 'pre_authorization_handling_staff.pre_authorization_id', '=', 'pre_authorizations.id')
            ->join('users', 'pre_authorization_handling_staff.user_id', '=', 'users.id')
            ->whereBetween('pre_authorizations.requested_date', [$rangeStart, $rangeEnd])
            ->whereNull('pre_authorizations.deleted_at');

        $staffResponseKpis = $staffBaseline->clone()
            ->select(
                'users.name',
                DB::raw('COUNT(*) as total_cases'),
                DB::raw('SUM(CASE WHEN pre_authorizations.send_out_date IS NOT NULL THEN 1 ELSE 0 END) as sent_out_total'),
                DB::raw('SUM(CASE WHEN pre_authorizations.send_out_date IS NOT NULL AND pre_authorizations.gop_receiving_date IS NOT NULL THEN 1 ELSE 0 END) as responded'),
                DB::raw('AVG(CASE WHEN pre_authorizations.gop_receiving_date IS NOT NULL THEN '.$dayDiffSql.' END) as avg_response_days')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->orderBy('users.name')
            ->get();

        $totalSentOutAssignments = (int) $staffResponseKpis->sum('sent_out_total');

        $staffResponseKpis = $staffResponseKpis->map(function ($row) use ($totalSentOutAssignments) {
            $sent = (int) $row->sent_out_total;
            $responded = (int) $row->responded;
            $row->response_rate = $sent > 0 ? round(100 * $responded / $sent, 1) : 0.0;
            $row->avg_response_days = $row->avg_response_days !== null ? round((float) $row->avg_response_days, 1) : null;
            $row->send_out_share_pct = $totalSentOutAssignments > 0 ? round(100 * $sent / $totalSentOutAssignments, 1) : 0.0;

            return $row;
        });

        $daysWaitingSql = $this->daysWaitingSinceSendOutSql();

        $pendingOver7Days = PreAuthorization::query()
            ->with(['provider', 'serviceType'])
            ->whereNotNull('send_out_date')
            ->whereNull('gop_receiving_date')
            ->whereRaw($daysWaitingSql.' > ?', [7])
            ->orderBy('send_out_date')
            ->get();

        $pendingOver10Days = PreAuthorization::query()
            ->with(['provider', 'serviceType'])
            ->whereNotNull('send_out_date')
            ->whereNull('gop_receiving_date')
            ->whereRaw($daysWaitingSql.' > ?', [10])
            ->orderBy('send_out_date')
            ->get();

        return view('dashboard.preauth', compact(
            'month',
            'year',
            'startDate',
            'endDate',
            'totalCases',
            'dailyCases',
            'caseStatusCounts',
            'coverageDecisions',
            'providersSortedHighToLow',
            'serviceTypesForPivot',
            'providerServicePivot',
            'providerTotalsForPivot',
            'providerResponseRates',
            'staffResponseKpis',
            'pendingOver7Days',
            'pendingOver10Days'
        ));
    }

    public function preauthExport(Request $request): StreamedResponse
    {
        [$rangeStart, $rangeEnd, $year, $month] = $this->preauthDashboardPeriod($request);
        $filename = sprintf('preauth_raw_%04d-%02d.csv', $year, $month);

        return response()->streamDownload(function () use ($rangeStart, $rangeEnd): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fprintf($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'pre_authorization_id',
                'hn',
                'patient_name',
                'requested_date',
                'date_of_service',
                'send_out_date',
                'gop_receiving_date',
                'days_send_out_to_gop',
                'case_status',
                'coverage_decision',
                'gop_reference_number',
                'operations_procedures',
                'provider_name',
                'service_type_name',
                'notifier_name',
                'handling_staff_names',
                'gop_translated_by',
                'created_at',
                'updated_at',
            ]);

            PreAuthorization::query()
                ->with([
                    'provider:id,name',
                    'serviceType:id,name',
                    'notifier:id,name',
                    'handlingStaffs:id,name',
                    'gopTranslateByUser:id,name',
                ])
                ->whereBetween('requested_date', [$rangeStart, $rangeEnd])
                ->orderBy('id')
                ->chunk(250, function ($rows) use ($handle): void {
                    foreach ($rows as $pa) {
                        $daysSendOutToGop = '';
                        if ($pa->send_out_date !== null && $pa->gop_receiving_date !== null) {
                            $daysSendOutToGop = (string) $pa->send_out_date->diffInDays($pa->gop_receiving_date);
                        }

                        fputcsv($handle, [
                            $pa->id,
                            $pa->hn,
                            $pa->patient_name,
                            $pa->requested_date?->format('Y-m-d H:i:s'),
                            $pa->date_of_service?->format('Y-m-d'),
                            $pa->send_out_date?->format('Y-m-d'),
                            $pa->gop_receiving_date?->format('Y-m-d'),
                            $daysSendOutToGop,
                            $pa->case_status,
                            $pa->coverage_decision,
                            $pa->gop_reference_number,
                            $pa->operations_procedures,
                            $pa->provider?->name,
                            $pa->serviceType?->name,
                            $pa->notifier?->name,
                            $pa->handlingStaffs->pluck('name')->join('|'),
                            $pa->gopTranslateByUser?->name,
                            $pa->created_at?->format('Y-m-d H:i:s'),
                            $pa->updated_at?->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: int, 3: int}
     */
    private function preauthDashboardPeriod(Request $request): array
    {
        return $this->resolveDashboardMonth($request);
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: int, 3: int}
     */
    private function admissionsDashboardPeriod(Request $request): array
    {
        return $this->resolveDashboardMonth($request);
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: int, 3: int}
     */
    private function resolveDashboardMonth(Request $request): array
    {
        $month = max(1, min(12, (int) $request->input('month', now()->month)));
        $year = (int) $request->input('year', now()->year);
        if ($year < 2000 || $year > 2100) {
            $year = now()->year;
        }

        $rangeStart = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $rangeEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        return [$rangeStart, $rangeEnd, $year, $month];
    }

    private function requestedDateDaySql(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlsrv' => 'CAST(pre_authorizations.requested_date AS DATE)',
            'sqlite' => 'date(pre_authorizations.requested_date)',
            'mysql', 'mariadb' => 'DATE(pre_authorizations.requested_date)',
            default => 'DATE(pre_authorizations.requested_date)',
        };
    }

    /**
     * Calendar days from send-out to GOP receiving (SQL Server / SQLite / MySQL).
     */
    private function dayDiffSendOutToGopSql(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlsrv' => 'DATEDIFF(day, pre_authorizations.send_out_date, pre_authorizations.gop_receiving_date)',
            'sqlite' => 'cast((julianday(pre_authorizations.gop_receiving_date) - julianday(pre_authorizations.send_out_date)) as integer)',
            'mysql', 'mariadb' => 'DATEDIFF(pre_authorizations.gop_receiving_date, pre_authorizations.send_out_date)',
            default => 'DATEDIFF(pre_authorizations.gop_receiving_date, pre_authorizations.send_out_date)',
        };
    }

    /**
     * Days from send-out date to today (pending GOP).
     */
    private function daysWaitingSinceSendOutSql(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlsrv' => 'DATEDIFF(day, pre_authorizations.send_out_date, GETDATE())',
            'sqlite' => 'cast((julianday(date(\'now\')) - julianday(pre_authorizations.send_out_date)) as integer)',
            'mysql', 'mariadb' => 'DATEDIFF(CURDATE(), date(pre_authorizations.send_out_date))',
            default => 'DATEDIFF(CURDATE(), date(pre_authorizations.send_out_date))',
        };
    }

    private function admissionDateDaySql(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlsrv' => 'CAST(admissions.admission_date AS DATE)',
            'sqlite' => 'date(admissions.admission_date)',
            'mysql', 'mariadb' => 'DATE(admissions.admission_date)',
            default => 'DATE(admissions.admission_date)',
        };
    }

    private function dayDiffAdmissionSendOutToInitialGopSql(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlsrv' => 'DATEDIFF(day, admissions.sent_out_date, admissions.initial_gop_receiving_date)',
            'sqlite' => 'cast((julianday(admissions.initial_gop_receiving_date) - julianday(admissions.sent_out_date)) as integer)',
            'mysql', 'mariadb' => 'DATEDIFF(admissions.initial_gop_receiving_date, admissions.sent_out_date)',
            default => 'DATEDIFF(admissions.initial_gop_receiving_date, admissions.sent_out_date)',
        };
    }

    private function daysWaitingAdmissionSendOutSql(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlsrv' => 'DATEDIFF(day, admissions.sent_out_date, GETDATE())',
            'sqlite' => 'cast((julianday(date(\'now\')) - julianday(admissions.sent_out_date)) as integer)',
            'mysql', 'mariadb' => 'DATEDIFF(CURDATE(), date(admissions.sent_out_date))',
            default => 'DATEDIFF(CURDATE(), date(admissions.sent_out_date))',
        };
    }

    public function admissions(Request $request)
    {
        [$rangeStart, $rangeEnd, $year, $month] = $this->admissionsDashboardPeriod($request);
        $startDate = $rangeStart->format('Y-m-d');
        $endDate = $rangeEnd->format('Y-m-d');
        $admissionRange = [$startDate, $endDate];

        $query = Admission::query()->whereBetween('admission_date', $admissionRange);

        $totalAdmissions = $query->clone()->count();

        $admissionDaySql = $this->admissionDateDaySql();
        $countsByDay = $query->clone()
            ->select(DB::raw($admissionDaySql.' as day'), DB::raw('count(*) as cnt'))
            ->groupBy(DB::raw($admissionDaySql))
            ->orderBy('day')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->day)->format('Y-m-d'));

        $dailyCases = [];
        $cursor = $rangeStart->copy()->startOfDay();
        $monthEnd = $rangeEnd->copy()->startOfDay();
        while ($cursor <= $monthEnd) {
            $key = $cursor->format('Y-m-d');
            $rowForDay = $countsByDay->get($key);
            $dailyCases[] = [
                'date' => $cursor->copy(),
                'count' => (int) ($rowForDay->cnt ?? 0),
            ];
            $cursor->addDay();
        }

        $admittingStatus = $query->clone()
            ->select('admitting_status', DB::raw('count(*) as count'))
            ->groupBy('admitting_status')
            ->orderByDesc('count')
            ->get();

        $caseStatusCounts = $query->clone()
            ->select('case_status', DB::raw('count(*) as count'))
            ->groupBy('case_status')
            ->orderByDesc('count')
            ->get();

        $gopStatusData = $query->clone()
            ->select('gop_pre_certification_status', DB::raw('count(*) as count'))
            ->groupBy('gop_pre_certification_status')
            ->orderByDesc('count')
            ->get();

        $providerDeptRows = $query->clone()
            ->join('admission_provider', 'admissions.id', '=', 'admission_provider.admission_id')
            ->join('providers', 'admission_provider.provider_id', '=', 'providers.id')
            ->select(
                'providers.name as provider',
                'admissions.department',
                DB::raw('count(*) as cnt')
            )
            ->groupBy('providers.name', 'admissions.department')
            ->get();

        $departmentsForPivot = $providerDeptRows->pluck('department')->map(fn ($d) => $d ?? '—')->unique()->sort()->values()->all();
        $providerTotalsForPivot = $providerDeptRows
            ->groupBy('provider')
            ->map(fn ($group) => (int) $group->sum('cnt'))
            ->sortDesc();
        $providersSortedHighToLow = $providerTotalsForPivot->keys()->values()->all();
        $providerDeptPivot = [];
        foreach ($providersSortedHighToLow as $providerName) {
            $providerDeptPivot[$providerName] = array_fill_keys($departmentsForPivot, 0);
        }
        foreach ($providerDeptRows as $row) {
            $deptLabel = $row->department ?? '—';
            $providerDeptPivot[$row->provider][$deptLabel] = (int) $row->cnt;
        }

        $providerResponseRows = $query->clone()
            ->join('admission_provider', 'admissions.id', '=', 'admission_provider.admission_id')
            ->join('providers', 'admission_provider.provider_id', '=', 'providers.id')
            ->select(
                'providers.name as provider',
                DB::raw('SUM(CASE WHEN admissions.sent_out_date IS NOT NULL THEN 1 ELSE 0 END) as sent_out'),
                DB::raw('SUM(CASE WHEN admissions.sent_out_date IS NOT NULL AND admissions.initial_gop_receiving_date IS NOT NULL THEN 1 ELSE 0 END) as responded')
            )
            ->groupBy('providers.name')
            ->get();

        $providerResponseRates = [];
        foreach ($providerResponseRows as $pr) {
            $sentOut = (int) $pr->sent_out;
            $providerResponseRates[$pr->provider] = $sentOut > 0
                ? round(100 * (int) $pr->responded / $sentOut, 1)
                : null;
        }

        $dayDiffAdmissionSql = $this->dayDiffAdmissionSendOutToInitialGopSql();

        $handlingBaseline = DB::table('admission_handling_user')
            ->join('admissions', 'admission_handling_user.admission_id', '=', 'admissions.id')
            ->join('users', 'admission_handling_user.user_id', '=', 'users.id')
            ->whereBetween('admissions.admission_date', $admissionRange)
            ->whereNull('admissions.deleted_at');

        $handlingUserKpis = $handlingBaseline->clone()
            ->select(
                'users.name',
                DB::raw('COUNT(*) as total_cases'),
                DB::raw('SUM(CASE WHEN admissions.sent_out_date IS NOT NULL THEN 1 ELSE 0 END) as sent_out_total'),
                DB::raw('SUM(CASE WHEN admissions.sent_out_date IS NOT NULL AND admissions.initial_gop_receiving_date IS NOT NULL THEN 1 ELSE 0 END) as responded'),
                DB::raw('AVG(CASE WHEN admissions.initial_gop_receiving_date IS NOT NULL THEN '.$dayDiffAdmissionSql.' END) as avg_response_days')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->orderBy('users.name')
            ->get();

        $totalAdmissionSentOutAssignments = (int) $handlingUserKpis->sum('sent_out_total');

        $handlingUserKpis = $handlingUserKpis->map(function ($row) use ($totalAdmissionSentOutAssignments) {
            $sent = (int) $row->sent_out_total;
            $responded = (int) $row->responded;
            $row->response_rate = $sent > 0 ? round(100 * $responded / $sent, 1) : 0.0;
            $row->avg_response_days = $row->avg_response_days !== null ? round((float) $row->avg_response_days, 1) : null;
            $row->send_out_share_pct = $totalAdmissionSentOutAssignments > 0 ? round(100 * $sent / $totalAdmissionSentOutAssignments, 1) : 0.0;

            return $row;
        });

        $daysWaitingAdmissionSql = $this->daysWaitingAdmissionSendOutSql();

        $pendingOver7Days = Admission::query()
            ->with(['contactProviders'])
            ->whereNotNull('sent_out_date')
            ->whereNull('initial_gop_receiving_date')
            ->whereRaw($daysWaitingAdmissionSql.' > ?', [7])
            ->orderBy('sent_out_date')
            ->get();

        $pendingOver10Days = Admission::query()
            ->with(['contactProviders'])
            ->whereNotNull('sent_out_date')
            ->whereNull('initial_gop_receiving_date')
            ->whereRaw($daysWaitingAdmissionSql.' > ?', [10])
            ->orderBy('sent_out_date')
            ->get();

        return view('dashboard.admissions', compact(
            'month',
            'year',
            'startDate',
            'endDate',
            'totalAdmissions',
            'dailyCases',
            'admittingStatus',
            'caseStatusCounts',
            'gopStatusData',
            'providersSortedHighToLow',
            'departmentsForPivot',
            'providerDeptPivot',
            'providerTotalsForPivot',
            'providerResponseRates',
            'handlingUserKpis',
            'pendingOver7Days',
            'pendingOver10Days'
        ));
    }

    public function admissionsExport(Request $request): StreamedResponse
    {
        [$rangeStart, $rangeEnd, $year, $month] = $this->admissionsDashboardPeriod($request);
        $filename = sprintf('admissions_raw_%04d-%02d.csv', $year, $month);
        $admissionRange = [$rangeStart->format('Y-m-d'), $rangeEnd->format('Y-m-d')];

        return response()->streamDownload(function () use ($admissionRange): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fprintf($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'admission_id',
                'hn',
                'patient_name',
                'admission_date',
                'department',
                'admitting_status',
                'case_status',
                'sent_out_date',
                'initial_gop_receiving_date',
                'days_send_out_to_initial_gop',
                'gop_pre_certification_status',
                'gop_ref',
                'discharge_date',
                'contact_provider_names',
                'handling_user_names',
                'gop_translator_names',
                'pre_authorization_id',
                'created_at',
                'updated_at',
            ]);

            Admission::query()
                ->with([
                    'contactProviders:id,name',
                    'handlingUsers:id,name',
                    'gopTranslators:id,name',
                    'preAuthorization:id',
                ])
                ->whereBetween('admission_date', $admissionRange)
                ->orderBy('id')
                ->chunk(250, function ($rows) use ($handle): void {
                    foreach ($rows as $adm) {
                        $days = '';
                        if ($adm->sent_out_date !== null && $adm->initial_gop_receiving_date !== null) {
                            $days = (string) $adm->sent_out_date->diffInDays($adm->initial_gop_receiving_date);
                        }

                        fputcsv($handle, [
                            $adm->id,
                            $adm->hn,
                            $adm->name,
                            $adm->admission_date?->format('Y-m-d'),
                            $adm->department,
                            $adm->admitting_status,
                            $adm->case_status,
                            $adm->sent_out_date?->format('Y-m-d'),
                            $adm->initial_gop_receiving_date?->format('Y-m-d'),
                            $days,
                            $adm->gop_pre_certification_status,
                            $adm->gop_ref,
                            $adm->discharge_date?->format('Y-m-d'),
                            $adm->contactProviders->pluck('name')->join('|'),
                            $adm->handlingUsers->pluck('name')->join('|'),
                            $adm->gopTranslators->pluck('name')->join('|'),
                            $adm->pre_authorization_id,
                            $adm->created_at?->format('Y-m-d H:i:s'),
                            $adm->updated_at?->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
