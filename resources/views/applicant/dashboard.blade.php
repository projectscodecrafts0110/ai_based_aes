@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid h-100">
        <div class="row h-100">

            <!-- LEFT: WELCOME / STATUS -->
            <div class="col-lg-8 py-4">
                <h2 class="fw-bold mb-1">
                    Welcome, {{ auth()->user()->name }} 👋
                </h2>

                <p class="text-muted mb-4">
                    Browse available positions and submit your application.
                </p>

                <!-- Application Status -->
                <div class="mb-4">
                    <strong>Application Status:</strong>
                    @php
                        $latestApplication = $applications->first();
                        $status = $latestApplication->status ?? 'Not Submitted';
                        $badgeClass = match ($status) {
                            'Pending' => 'bg-warning text-dark',
                            'Under Review' => 'bg-primary',
                            'Approved' => 'bg-success',
                            'Rejected' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} ms-2">{{ $status }}</span>
                </div>

                <hr>

                <!-- How It Works -->
                <h5 class="fw-semibold mb-3">How It Works</h5>
                <ol class="text-muted">
                    <li>Select a position</li>
                    <li>Fill out the application form</li>
                    <li>Upload required documents</li>
                    <li>Wait for evaluation</li>
                </ol>
            </div>

            <!-- RIGHT: AVAILABLE POSITIONS -->
            <div class="col-lg-4 border-start d-flex flex-column h-100">
                <div class="py-4 px-3 flex-grow-1 overflow-auto">
                    <h5 class="fw-semibold mb-3">Available Positions</h5>

                    <div class="row g-3">
                        @foreach ($positions as $position)
                            <div class="col-12">
                                <!-- Card triggers modal -->
                                <div class="card shadow-sm position-card h-100" data-bs-toggle="modal"
                                    data-bs-target="#jobModal{{ $position->id }}">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold mb-1">{{ $position->title }}</h6>
                                        <p class="card-text text-muted small mb-2">
                                            {{ Str::limit($position->qualifications, 60) }}</p>
                                        <span class="badge {{ $position->is_open ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $position->is_open ? 'Open' : 'Closed' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="jobModal{{ $position->id }}" tabindex="-1"
                                    aria-labelledby="jobModalLabel{{ $position->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="jobModalLabel{{ $position->id }}">
                                                    {{ $position->title }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Qualifications:</strong></p>
                                                <p>{{ $position->qualifications }}</p>

                                                @if (!empty($position->description))
                                                    <p><strong>Description:</strong></p>
                                                    <p>{{ $position->description }}</p>
                                                @endif

                                                <p><strong>Status:</strong>
                                                    <span
                                                        class="badge {{ $position->is_open ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $position->is_open ? 'Open' : 'Closed' }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                @if ($position->is_open)
                                                    <a href="{{ route('apply') }}" class="btn btn-primary">Apply Now</a>
                                                @endif
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .position-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .position-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .overflow-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
        }

        .overflow-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }
    </style>
@endsection
