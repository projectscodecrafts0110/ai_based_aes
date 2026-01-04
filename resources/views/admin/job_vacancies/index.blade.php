@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Manage Job Vacancies</h2>

        <a href="{{ route('admin.job_vacancies.create') }}" class="btn btn-primary mb-3">+ Add Job Vacancy</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-fixed table-hover">
            <thead>
                <tr class="text-center">
                    <th>Title</th>
                    <th>Qualifications</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vacancies as $vacancy)
                    <tr class="text-center">
                        <td>{{ $vacancy->title }}</td>
                        <td>{{ $vacancy->qualifications }}</td>
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
