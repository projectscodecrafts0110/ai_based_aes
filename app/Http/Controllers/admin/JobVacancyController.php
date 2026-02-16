<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values from query string
        $jobType = $request->query('job_type');
        $employmentStatus = $request->query('employment_status');
        $campus = $request->query('campus');
        $department = $request->query('department');

        // Start query
        $query = JobVacancy::query();

        // Apply filters if present
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

        // Order by newest first
        $vacancies = $query->orderBy('created_at', 'desc')->paginate(15);

        // Pass filters back to view so dropdowns can stay selected
        return view('admin.job_vacancies.index', [
            'vacancies' => $vacancies,
            'filters' => [
                'job_type' => $jobType,
                'employment_status' => $employmentStatus,
                'campus' => $campus,
                'department' => $department,
            ]
        ]);
    }

    public function create()
    {
        return view('admin.job_vacancies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'course' => 'required|string',
            'job_type' => 'required|string',
            'employment_status' => 'required|string',
            'campus' => 'required|string',
            'department' => 'required|string',
        ]);

        JobVacancy::create($request->only('title', 'description', 'qualifications', 'course', 'job_type', 'employment_status', 'campus', 'department'));

        return redirect()->route('admin.job_vacancies.index')->with('success', 'Job vacancy created successfully!');
    }

    public function edit(JobVacancy $jobVacancy)
    {
        return view('admin.job_vacancies.edit', compact('jobVacancy'));
    }

    public function update(Request $request, JobVacancy $jobVacancy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'course' => 'required|string',
            'job_type' => 'required|string',
            'employment_status' => 'required|string',
            'campus' => 'required|string',
            'department' => 'required|string',
        ]);

        $jobVacancy->update($request->only('title', 'description', 'qualifications', 'course', 'job_type', 'employment_status', 'campus', 'department'));

        return redirect()->route('admin.job_vacancies.index')->with('success', 'Job vacancy updated successfully!');
    }

    public function destroy(JobVacancy $jobVacancy)
    {
        $jobVacancy->delete();

        return redirect()->route('admin.job_vacancies.index')->with('success', 'Job vacancy deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $vacancy = JobVacancy::findOrFail($id);
        $vacancy->is_open = !$vacancy->is_open;
        $vacancy->save();

        return back()->with('success', 'Job status updated successfully!');
    }
}
