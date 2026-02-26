@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Manage Applicants</h2>
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.applications') }}" class="row g-3">

                    {{-- Application Status --}}
                    <div class="col-md-3">
                        <label class="form-label">Application Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            @foreach (['Pending', 'Under Review', 'Approved', 'Rejected'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Job Applied --}}
                    <div class="col-md-3">
                        <label class="form-label">Job Applied</label>
                        <select name="job_id" class="form-select">
                            <option value="">All</option>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Campus --}}
                    <div class="col-md-3">
                        <label class="form-label">Campus</label>
                        <select name="campus" class="form-select">
                            <option value="">All</option>
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>
                                    {{ $campus }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Department --}}
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department" class="form-select">
                            <option value="">All</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept }}"
                                    {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.applications') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>

                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Job Applied</th>
                        <th>Campus</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $index => $app)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $app->full_name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->job->title }}</td>
                            <td>{{ $app->job->campus }}</td>
                            <td>{{ $app->job->department }}</td>
                            <td>
                                @php
                                    $badgeClass = match ($app->status) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Under Review' => 'bg-primary',
                                        'Approved' => 'bg-success',
                                        'Rejected' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $app->status }}</span>
                            </td>
                            <td>
                                {{-- <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                    data-bs-target="#aiModal{{ $app->id }}">AI Details</button> --}}
                                <form action="{{ route('admin.applications.destroy', $app->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"
                                        data-toggle="tooltip" data-placement="top" title="Delete"><i
                                            class="bi bi-trash"></i></button>
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
    </div>
@endsection
