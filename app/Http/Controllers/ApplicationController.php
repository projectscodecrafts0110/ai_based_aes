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

        return view('applicant/apply', compact('jobs', 'noRelatedJobs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:50',
            'address' => 'required|string',
            'job_id' => 'required|exists:job_vacancies,id',
            'higher_education' => 'required|string',
            'major' => 'required|string',
            'application_letter' => 'required|file|mimes:pdf,doc,docx',
            'resume' => 'required|file|mimes:pdf,doc,docx',
            'pds' => 'required|file|mimes:pdf',
            'otr' => 'required|file|mimes:pdf',
            'certificates.*' => 'nullable|file|mimes:pdf,doc,docx',
            'ratings' => 'nullable|string|max:50',
        ]);

        // Store uploaded files in the public disk
        $applicationLetter = $request->file('application_letter')->store('applications', 'public');
        $resume = $request->file('resume')->store('applications', 'public');
        $pds = $request->file('pds')->store('applications', 'public');
        $otr = $request->file('otr')->store('applications', 'public');

        $certificates = [];
        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $cert) {
                $certificates[] = $cert->store('applications/certificates', 'public');
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
            'higher_education' => $request->higher_education,
            'major' => $request->major,
            'application_letter' => $applicationLetter,
            'resume' => $resume,
            'pds' => $pds,
            'otr' => $otr,
            'certificates' => $certificates, // store as JSON
            'ratings' => $request->ratings,
        ]);

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
