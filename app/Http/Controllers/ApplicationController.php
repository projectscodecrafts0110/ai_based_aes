<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Jobs\EvaluateApplicationAI;
use App\Models\JobVacancy;

class ApplicationController extends Controller
{
    public function index()
    {
        $campuses = JobVacancy::pluck('campus')->unique();
        $departments = JobVacancy::pluck('department')->unique();

        return view('applicant/apply-filter', compact('campuses', 'departments'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'job_type' => 'nullable|string',
            'employment_status' => 'nullable|string',
            'campus' => 'nullable|string',
            'department' => 'nullable|string',
        ]);



        if (!$request->hasAny(['job_type', 'employment_status', 'campus', 'department'])) {
            return redirect()->route('apply.filter')->with('error', 'Please select at least one filter option.');
        }


        $jobType = $request->input('job_type');
        $employmentStatus = $request->input('employment_status');
        $campus = $request->input('campus');
        $department = $request->input('department');

        $query = JobVacancy::query();

        if ($jobType) {
            $query->where('job_type', $jobType);
        }
        if ($employmentStatus) {
            $query->where('employment_status', $employmentStatus);
        }
        if ($campus) {
            $query->where('campus', $campus);
        }
        if ($department) {
            $query->where('department', $department);
        }

        $query->where('is_open', 1);

        $jobs = $query->get();
        $noRelatedJobs = $jobs->isEmpty();

        return view('applicant.apply', compact('jobs', 'noRelatedJobs'));
    }

    public function showForm(JobVacancy $job)
    {
        if (!$job->is_open) {
            abort(404);
        }

        $jobs = collect([$job]); // important
        $noRelatedJobs = false;

        return view('applicant.apply', compact('jobs', 'noRelatedJobs'));
    }


    public function store(Request $request)
    {
        // Store uploaded files
        $applicationLetter = $request->file('application_letter')->store('applications', 'public');
        $pds = $request->file('pds')->store('applications', 'public');
        $otrDiploma = $request->file('otr_diploma')->store('applications', 'public');
        $certificateEligibility = $request->hasFile('certificate_eligibility')
            ? $request->file('certificate_eligibility')->store('applications', 'public')
            : null;

        $certificatesTraining = [];
        if ($request->hasFile('certificates_training')) {
            foreach ($request->file('certificates_training') as $cert) {
                $certificatesTraining[] = $cert->store('applications/certificates_training', 'public');
            }
        }

        // Save application
        $application = Application::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'email' => Auth::user()->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'job_id' => $request->job_id,
            'education' => $request->education,
            'training' => $request->training,
            'eligibility' => $request->eligibility,
            'work_experience' => $request->work_experience,
            'application_letter' => $applicationLetter,
            'pds' => $pds,
            'otr_diploma' => $otrDiploma,
            'certificate_eligibility' => $certificateEligibility,
            'certificates_training' => $certificatesTraining, // store as JSON
        ]);

        // Dispatch AI evaluation job
        EvaluateApplicationAI::dispatch($application);

        return redirect()->route('dashboard')->with('success', 'Application submitted successfully!');
    }

    public function status()
    {
        $applications = auth()->user()->applications()->latest()->get();

        return view('applicant/status', compact('applications'));
    }

    public function show($id)
    {
        $application = auth()->user()->applications()->findOrFail($id);
        return view('applicant/show', compact('application'));
    }

    public function download($id)
    {
        $application = auth()->user()->applications()->findOrFail($id);

        // Example: download resume
        return Storage::download($application->resume);
    }

    public function getDepartmentsByCampus($campus)
    {
        $departments = JobVacancy::where('campus', $campus)
            ->pluck('department')
            ->unique()
            ->values();

        return response()->json($departments);
    }
}
