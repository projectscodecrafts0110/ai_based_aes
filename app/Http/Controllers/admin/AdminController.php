<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Services\KMeansService;
use App\Models\JobVacancy;

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

        // Applicants per job vacancy with job titles
        $jobs = Application::select('job_vacancies.title', 'applications.job_id')
            ->join('job_vacancies', 'job_vacancies.id', '=', 'applications.job_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('applications.job_id', 'job_vacancies.title')
            ->get();

        $jobLabels = $jobs->pluck('title');
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

    public function manageApplications(Request $request)
    {
        $query = Application::with('job');

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Job
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        // Filter by Campus (from related job)
        if ($request->filled('campus')) {
            $query->whereHas('job', function ($q) use ($request) {
                $q->where('campus', $request->campus);
            });
        }

        // Filter by Department (from related job)
        if ($request->filled('department')) {
            $query->whereHas('job', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        $applications = $query->latest()->get();

        // For dropdowns
        $jobs = JobVacancy::select('id', 'title')->get();
        $campuses = JobVacancy::pluck('campus')->unique();
        $departments = JobVacancy::pluck('department')->unique();

        return view('admin.manage_applications', compact(
            'applications',
            'jobs',
            'campuses',
            'departments'
        ));
    }

    public function deleteApplication(Application $application)
    {
        $application->delete();
        return redirect()->route('admin.applications')->with('success', 'Application deleted successfully.');
    }

    public function aiEvaluations(Request $request)
    {
        $query = Application::with('job');

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Job
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        // Filter by Campus (from related job)
        if ($request->filled('campus')) {
            $query->whereHas('job', function ($q) use ($request) {
                $q->where('campus', $request->campus);
            });
        }

        // Filter by Department (from related job)
        if ($request->filled('department')) {
            $query->whereHas('job', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        $applications = $query->latest()->get();

        // For dropdowns
        $jobs = JobVacancy::select('id', 'title')->get();
        $campuses = JobVacancy::pluck('campus')->unique();
        $departments = JobVacancy::pluck('department')->unique();

        return view('admin.ai_evaluations', compact(
            'applications',
            'jobs',
            'campuses',
            'departments'
        ));
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
