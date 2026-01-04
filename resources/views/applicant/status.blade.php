@extends('layouts.app')

@section('title', 'Application Status')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Your Applications</h2>

        @if ($applications->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Job Position</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $app)
                            <tr>
                                <td>{{ $app->job->title }}</td>
                                <td>{{ $app->created_at->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $status = $app->status;
                                        $badgeClass = match ($status) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Under Review' => 'bg-primary',
                                            'Approved' => 'bg-success',
                                            'Rejected' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('applications.show', $app->id) }}" class="btn btn-sm btn-info">
                                        View
                                    </a>
                                    @if ($app->status === 'Approved')
                                        <a href="{{ route('applications.download', $app->id) }}"
                                            class="btn btn-sm btn-success">
                                            Download
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">You haven’t submitted any applications yet.</p>
        @endif
    </div>
@endsection
