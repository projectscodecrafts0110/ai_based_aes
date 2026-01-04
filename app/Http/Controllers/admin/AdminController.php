<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Services\KMeansService;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Summary counts
        $accepted    = Application::where('status', 'Approved')->count();
        $rejected    = Application::where('status', 'Rejected')->count();
        $underReview = Application::where('status', 'Under Review')->count();
        $pending     = Application::where('status', 'Pending')->count();
        $totalApplicants = Application::count();

        // Applicants per job vacancy
        $jobs = Application::select('job_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('job_id')
            ->get();

        $jobLabels = $jobs->pluck('job_id');
        $jobCounts = $jobs->pluck('count');

        return view('admin.dashboard', compact(
            'accepted',
            'rejected',
            'underReview',
            'pending',
            'totalApplicants',
            'jobLabels',
            'jobCounts'
        ));
    }

    public function manageApplications()
    {
        $applications = \App\Models\Application::orderBy('created_at', 'desc')->get();
        return view('admin.manage_applications', compact('applications'));
    }

    public function deleteApplication(Application $application)
    {
        $application->delete();
        return redirect()->route('admin.applications')->with('success', 'Application deleted successfully.');
    }

    public function aiEvaluations(KMeansService $kMeans)
    {
        $applications = Application::with('job')->orderByDesc('ai_score')->get();

        return view('admin.ai_evaluations', [
            'applications' => $applications
        ]);
    }



    // Update application status from action buttons (Accept, Review, Reject)
    public function updateApplicationStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:Approved,Under Review,Rejected',
        ]);

        $application->status = $request->status;
        $application->save();

        return back()->with('success', 'Application status updated successfully!');
    }
}
