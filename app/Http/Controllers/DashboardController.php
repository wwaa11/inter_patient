<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\PreAuthorization;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Pre-authorization summaries
        $totalPreauth = PreAuthorization::count();
        $pendingPreauth = PreAuthorization::where('case_status', 'Data Entered')->count(); // Assuming 'Data Entered' is pending
        $approvedPreauth = PreAuthorization::where('coverage_decision', 'Approved')->count();
        $rejectedPreauth = PreAuthorization::where('coverage_decision', 'Rejected')->count();

        // Admission summaries
        $totalAdmissions = Admission::count();
        $inProgressAdmissions = Admission::where('case_status', 'In Progress')->count(); // Assuming 'In Progress' for admissions
        $dischargedAdmissions = Admission::where('admitting_status', 'Discharged')->count();
        $canceledAdmissions = Admission::where('case_status', 'Canceled')->count(); // Assuming 'Canceled' for admissions

        return view('dashboard', compact('totalPreauth', 'pendingPreauth', 'approvedPreauth', 'rejectedPreauth',
            'totalAdmissions', 'inProgressAdmissions', 'dischargedAdmissions', 'canceledAdmissions'));
    }
}
