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
                <label for="description" class="form-label">Job Description</label>
                <textarea class="form-control" name="description" id="description" rows="4">{{ old('description', $jobVacancy->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="qualifications" class="form-label">Qualifications (comma-separated)</label>
                <input type="text" class="form-control" name="qualifications" id="qualifications"
                    value="{{ old('qualifications', $jobVacancy->qualifications) }}">
                <small class="text-muted">E.g., Bachelor’s Degree, PHP, Laravel</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Job Vacancy</button>
            <a href="{{ route('admin.job_vacancies.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
