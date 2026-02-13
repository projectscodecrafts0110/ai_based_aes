<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    public function index()
    {
        $vacancies = JobVacancy::orderBy('created_at', 'desc')->get();
        return view('admin.job_vacancies.index', compact('vacancies'));
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
        ]);

        JobVacancy::create($request->only('title', 'description', 'qualifications', 'course'));

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
        ]);

        $jobVacancy->update($request->only('title', 'description', 'qualifications'));

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
