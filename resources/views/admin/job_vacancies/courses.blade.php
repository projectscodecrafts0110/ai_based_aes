@extends('layouts.admin')

@section('content')
    <div class="container py-5">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Manage Courses</h2>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                + Add Course
            </a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control" placeholder="Search course..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Courses Table --}}
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Course</th>
                            <th>Allied Courses</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $course->course }}</td>
                                <td>
                                    @if (!empty($course->allied))
                                        @foreach ($course->allied as $allied)
                                            <span class="badge bg-info text-dark">
                                                {{ $allied }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.courses.show.update', $course->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this course?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No courses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
