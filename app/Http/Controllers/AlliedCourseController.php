<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlliedCourse;

class AlliedCourseController extends Controller
{
    public function index()
    {
        $courses = AlliedCourse::orderBy('course')->get();
        return view('admin.job_vacancies.courses', compact('courses'));
    }

    public function create()
    {
        return view('admin.job_vacancies.create_course');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course' => 'required|string|max:255',
            'allied' => 'nullable|array',
            'allied.*' => 'nullable|string|max:255',
        ]);

        AlliedCourse::create([
            'course' => $request->course,
            'allied' => array_filter($request->allied), // remove empty values
        ]);

        return redirect()
            ->route('admin.courses')
            ->with('success', 'Course created successfully!');
    }

    public function editPage(AlliedCourse $course)
    {
        return view('admin.job_vacancies.edit_course', compact('course'));
    }

    public function update(Request $request, AlliedCourse $course)
    {
        $request->validate([
            'course' => 'required|string|max:255',
            'allied' => 'nullable|array',
            'allied.*' => 'nullable|string|max:255',
        ]);

        $course->update([
            'course' => $request->course,
            'allied' => array_filter($request->allied), // remove empty values
        ]);

        return redirect()
            ->route('admin.courses')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(AlliedCourse $course)
    {
        $course->delete();

        return redirect()
            ->route('admin.courses')
            ->with('success', 'Course deleted successfully!');
    }
}
