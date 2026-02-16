@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Manage Job Vacancies</h2>

        <a href="{{ route('admin.job_vacancies.create') }}" class="btn btn-primary mb-3">+ Add Job Vacancy</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <!-- Filters Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.job_vacancies.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Job Type</label>
                        <select name="job_type" class="form-select">
                            <option value="">All</option>
                            <option value="Teaching" {{ ($filters['job_type'] ?? '') == 'Teaching' ? 'selected' : '' }}>
                                Teaching</option>
                            <option value="Non-Teaching"
                                {{ ($filters['job_type'] ?? '') == 'Non-Teaching' ? 'selected' : '' }}>Non-Teaching</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Employment Status</label>
                        <select name="employment_status" class="form-select">
                            <option value="">All</option>
                            <option value="Permanent"
                                {{ ($filters['employment_status'] ?? '') == 'Permanent' ? 'selected' : '' }}>Permanent
                            </option>
                            <option value="Part_Time"
                                {{ ($filters['employment_status'] ?? '') == 'Part_Time' ? 'selected' : '' }}>Part-Time
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Campus</label>
                        <select name="campus" class="form-select">
                            <option value="">All</option>
                            @foreach (\App\Models\JobVacancy::pluck('campus')->unique() as $campus)
                                <option value="{{ $campus }}"
                                    {{ ($filters['campus'] ?? '') == $campus ? 'selected' : '' }}>
                                    {{ $campus }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Department / College</label>
                        <select name="department" class="form-select">
                            <option value="">All</option>
                            @foreach (\App\Models\JobVacancy::pluck('department')->unique() as $dept)
                                <option value="{{ $dept }}"
                                    {{ ($filters['department'] ?? '') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.job_vacancies.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered table-fixed table-hover">
            <thead>
                <tr class="text-center">
                    <th>Title</th>
                    <th>Campus</th>
                    <th>Department / College</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vacancies as $vacancy)
                    <tr class="text-center">
                        <td>{{ $vacancy->title }}</td>
                        <td>{{ $vacancy->campus }}</td>
                        <td>{{ $vacancy->department }}</td>
                        <td>{{ $vacancy->created_at->format('Y-m-d') }}</td>

                        <!-- STATUS -->
                        <td>
                            <span class="badge {{ $vacancy->is_open ? 'bg-success' : 'bg-secondary' }}">
                                {{ $vacancy->is_open ? 'Open' : 'Closed' }}
                            </span>
                        </td>

                        <!-- ACTIONS -->
                        <td>
                            <a href="{{ route('admin.job_vacancies.edit', $vacancy) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <!-- Toggle Status Form -->
                            <form action="{{ route('admin.job_vacancies.toggle_status', $vacancy->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn btn-sm {{ $vacancy->is_open ? 'btn-secondary' : 'btn-success' }}">
                                    <i class="bi {{ $vacancy->is_open ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.job_vacancies.destroy', $vacancy) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this job?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No job vacancies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
