<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\PreAuthorization;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function preauth(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = PreAuthorization::whereBetween('requested_date', [$startDate, $endDate.' 23:59:59']);

        // Case Status by Service Type
        $caseStatusByServiceType = $query->clone()
            ->join('service_types', 'pre_authorizations.service_type_id', '=', 'service_types.id')
            ->select('service_types.name as service_type', 'case_status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('service_types.name', 'case_status')
            ->get();

        // Coverage Decision
        $coverageDecisions = $query->clone()
            ->select('coverage_decision', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('coverage_decision')
            ->get();

        // Provider/Service Type in Graph
        $providerServiceData = $query->clone()
            ->join('providers', 'pre_authorizations.provider_id', '=', 'providers.id')
            ->select('providers.name as provider', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('providers.name')
            ->get();

        $serviceTypeData = $query->clone()
            ->join('service_types', 'pre_authorizations.service_type_id', '=', 'service_types.id')
            ->select('service_types.name as service_type', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('service_types.name')
            ->get();

        // Handling Staffs percentage
        $totalCases = $query->clone()->count();
        $staffCases = \Illuminate\Support\Facades\DB::table('pre_authorization_handling_staff')
            ->join('pre_authorizations', 'pre_authorization_handling_staff.pre_authorization_id', '=', 'pre_authorizations.id')
            ->join('users', 'pre_authorization_handling_staff.user_id', '=', 'users.id')
            ->whereBetween('pre_authorizations.requested_date', [$startDate, $endDate.' 23:59:59'])
            ->select('users.name', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('users.name')
            ->get()
            ->map(function ($staff) use ($totalCases) {
                $staff->percentage = $totalCases > 0 ? round(($staff->count / $totalCases) * 100, 2) : 0;

                return $staff;
            });

        // Returning date diff (Requested vs GOP Receiving)
        $dateDiffs = $query->clone()
            ->whereNotNull('gop_receiving_date')
            ->select(\Illuminate\Support\Facades\DB::raw('DATEDIFF(day, requested_date, gop_receiving_date) as diff'), \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy(\Illuminate\Support\Facades\DB::raw('DATEDIFF(day, requested_date, gop_receiving_date)'))
            ->orderBy('diff')
            ->get();

        return view('dashboard.preauth', compact(
            'startDate', 'endDate', 'caseStatusByServiceType', 'coverageDecisions',
            'providerServiceData', 'serviceTypeData', 'staffCases', 'dateDiffs'
        ));
    }

    public function admissions(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = Admission::whereBetween('admission_date', [$startDate, $endDate]);

        // Admitting Status
        $admittingStatus = $query->clone()
            ->select('admitting_status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('admitting_status')
            ->get();

        // Providers
        $providerData = $query->clone()
            ->join('admission_provider', 'admissions.id', '=', 'admission_provider.admission_id')
            ->join('providers', 'admission_provider.provider_id', '=', 'providers.id')
            ->select('providers.name as provider', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('providers.name')
            ->get();

        // Departments
        $departmentData = $query->clone()
            ->select('department', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        // GOP/Pre-Certification Status
        $gopStatusData = $query->clone()
            ->select('gop_pre_certification_status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('gop_pre_certification_status')
            ->get();

        // Handling User
        $userData = $query->clone()
            ->join('admission_handling_user', 'admissions.id', '=', 'admission_handling_user.admission_id')
            ->join('users', 'admission_handling_user.user_id', '=', 'users.id')
            ->select('users.name as user', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('users.name')
            ->get();

        return view('dashboard.admissions', compact(
            'startDate', 'endDate', 'admittingStatus', 'providerData',
            'departmentData', 'gopStatusData', 'userData'
        ));
    }
}
