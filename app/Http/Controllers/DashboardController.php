<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->otp_verified) {
            return redirect()->route('otp.verify');
        }

        $applications = Application::where('user_id', Auth::id())
            ->latest()
            ->get();

        // Get distinct school years for dropdown
        $schoolYears = JobVacancy::select('school_year')
            ->distinct()
            ->orderByDesc('school_year')
            ->pluck('school_year');

        $query = JobVacancy::where('is_open', 1);

        // Apply SY filter if selected
        if ($request->filled('school_year')) {
            $query->where('school_year', $request->school_year);
        }

        $positions = $query->latest()->get();

        return view('applicant.dashboard', compact(
            'applications',
            'positions',
            'schoolYears'
        ));
    }
}
