@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Edit Job Vacancy</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.job_vacancies.update', $jobVacancy->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Job Title</label>
                <input type="text" class="form-control" name="title" id="title"
                    value="{{ old('title', $jobVacancy->title) }}" required>
            </div>

            <div class="mb-3">
                <label for="job_type" class="form-label">Job Type</label>
                <select class="form-control" name="job_type" id="job_type" required>
                    <option value="">Select Job Type</option>
                    <option value="teaching" {{ old('job_type', $jobVacancy->job_type) == 'teaching' ? 'selected' : '' }}>
                        Teaching</option>
                    <option value="non_teaching"
                        {{ old('job_type', $jobVacancy->job_type) == 'non_teaching' ? 'selected' : '' }}>Non-Teaching
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="employment_status" class="form-label">Employment Status</label>
                <select class="form-control" name="employment_status" id="employment_status" required>
                    <option value="">Select Employment Status</option>
                    <option value="permanent"
                        {{ old('employment_status', $jobVacancy->employment_status) == 'permanent' ? 'selected' : '' }}>
                        Permanent
                    </option>
                    <option value="part_time"
                        {{ old('employment_status', $jobVacancy->employment_status) == 'part_time' ? 'selected' : '' }}>
                        Part-Time
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="campus" class="form-label">Campus</label>
                <input type="text" class="form-control" name="campus" id="campus"
                    value="{{ old('campus', $jobVacancy->campus) }}" required>
            </div>

            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" name="department" id="department"
                    value="{{ old('department', $jobVacancy->department) }}" required>
            </div>

            <div class="mb-3">
                <label for="course" class="form-label">Course</label>
                <select name="course" id="course" class="form-control" required>
                    <option value="">Select Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->course }}"
                            {{ old('course', $jobVacancy->course) == $course->course ? 'selected' : '' }}>
                            {{ $course->course }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="available_positions" class="form-label">Available Positions</label>
                <input type="number" class="form-control" name="available_positions" id="available_positions"
                    min="1" value="{{ old('available_positions', $jobVacancy->available_positions) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Job Description</label>
                <textarea class="form-control" name="description" id="description" rows="4">{{ old('description', $jobVacancy->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Qualifications</label>
                @php
                    $qualifications = $jobVacancy->qualifications;
                @endphp
                <div class="mb-3">
                    <label for="education" class="form-label">Education</label>
                    <input type="text" class="form-control" name="education" id="education"
                        value="{{ old('education', $qualifications['education'] ?? '') }}" required>
                    <small class="text-muted">E.g., Bachelor's Degree in Computer Science</small>
                </div>

                <div class="mb-3">
                    <label for="experience" class="form-label">Experience</label>
                    <input type="text" class="form-control" name="experience" id="experience"
                        value="{{ old('experience', $qualifications['experience'] ?? '') }}" required>
                    <small class="text-muted">E.g., 2+ years teaching experience</small>
                </div>

                <div class="mb-3">
                    <label for="training" class="form-label">Training</label>
                    <input type="text" class="form-control" name="training" id="training"
                        value="{{ old('training', $qualifications['training'] ?? '') }}">
                    <small class="text-muted">E.g., Relevant seminars and certifications</small>
                </div>

                <div class="mb-3">
                    <label for="eligibility" class="form-label">Eligibility</label>
                    <input type="text" class="form-control" name="eligibility" id="eligibility"
                        value="{{ old('eligibility', $qualifications['eligibility'] ?? '') }}">
                    <small class="text-muted">E.g., Licensed Professional Teacher (LET Passer)</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Job Vacancy</button>
            <a href="{{ route('admin.job_vacancies.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
