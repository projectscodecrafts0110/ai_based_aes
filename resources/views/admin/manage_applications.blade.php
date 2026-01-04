@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Manage Applicants</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Job Applied</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $index => $app)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $app->full_name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->job->title }}</td>
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
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- AI Details Modal -->
                        {{-- <div class="modal fade" id="aiModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">AI Evaluation for {{ $app->full_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Score:</strong> {{ $app->ai_score ?? '-' }}</p>
                                        <p><strong>Recommendation:</strong> {{ $app->ai_recommendation ?? '-' }}</p>
                                        <p><strong>Justification:</strong> {{ $app->ai_summary ?? '-' }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
