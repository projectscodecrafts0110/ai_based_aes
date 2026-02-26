@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid h-100">
        <div class="row h-100">

            <!-- LEFT: WELCOME / STATUS -->
            <div class="col-lg-8 py-4">
                <h2 class="fw-bold mb-1">
                    Welcome back, {{ auth()->user()->name }} 👋
                </h2>
                <p class="text-muted mb-4">
                    Track your application progress and explore new job opportunities.
                </p>

                <!-- Application Status -->
                <div class="mb-4">
                    <strong>Latest Application Status:</strong>
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
                    <div class="text-muted small mt-1">
                        @switch($status)
                            @case('Pending')
                                Your application has been submitted and is awaiting review.
                            @break

                            @case('Under Review')
                                The HR team is currently evaluating your application.
                            @break

                            @case('Approved')
                                Congratulations! You have been accepted.
                            @break

                            @case('Rejected')
                                Unfortunately, your application was not successful.
                            @break

                            @default
                                You haven’t submitted any applications yet.
                        @endswitch
                    </div>
                </div>

                <hr>

                <!-- How It Works -->
                <h5 class="fw-semibold mb-3">Application Process</h5>
                <ol class="text-muted">
                    <li>Choose an available position</li>
                    <li>Complete the online application form</li>
                    <li>Upload your required documents</li>
                    <li>Wait for HR evaluation and feedback</li>
                </ol>
            </div>

            <!-- RIGHT: AVAILABLE POSITIONS -->
            <div class="col-lg-4 border-start d-flex flex-column h-100">
                <div class="py-4 px-3 flex-grow-1 overflow-auto">
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-10">
                                <label class="form-label small">Filter by School Year</label>
                                <select name="school_year" class="form-select form-select-sm">
                                    <option value="">All School Years</option>
                                    @foreach ($schoolYears as $sy)
                                        <option value="{{ $sy }}"
                                            {{ request('school_year') == $sy ? 'selected' : '' }}>
                                            {{ $sy }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-sm btn-success w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <h5 class="fw-semibold mb-1">Available Positions</h5>
                    <p class="text-muted small mb-3">
                        Click on a position to view full details and apply.
                    </p>

                    <div class="row g-3">
                        @foreach ($positions as $position)
                            <div class="col-12">
                                <!-- Card triggers modal -->
                                <div class="card shadow-sm position-card h-100" data-bs-toggle="modal"
                                    data-bs-target="#jobModal{{ $position->id }}">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold mb-1">{{ $position->title }}</h6>
                                        <p class="text-muted small mb-1">
                                            {{ $position->department ?? 'N/A' }} ||
                                            {{ $position->campus ?? 'N/A' }}
                                        </p>
                                        @php
                                            $qualifications = is_array($position->qualifications)
                                                ? $position->qualifications
                                                : json_decode($position->qualifications, true);
                                        @endphp

                                        <p class="card-text text-muted small mb-2">
                                            {{ Str::limit($qualifications['education'] ?? 'N/A', 60) }}
                                        </p>
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
                                                <p><strong>Department:</strong>
                                                    {{ $position->department ?? 'N/A' }}</p>
                                                <p><strong>Campus:</strong>
                                                    {{ $position->campus ?? 'N/A' }}</p>
                                                <p><strong>Qualifications:</strong></p>
                                                <ul>
                                                    <li><strong>Education:</strong>
                                                        {{ $qualifications['education'] ?? 'N/A' }}</li>
                                                    <li><strong>Experience:</strong>
                                                        {{ $qualifications['experience'] ?? 'N/A' }}</li>
                                                    <li><strong>Training:</strong>
                                                        {{ $qualifications['training'] ?? 'N/A' }}</li>
                                                    <li><strong>Eligibility:</strong>
                                                        {{ $qualifications['eligibility'] ?? 'N/A' }}</li>
                                                </ul>

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
                                                    <a href="{{ route('apply.form', $position->id) }}"
                                                        class="btn btn-primary">
                                                        Apply Now
                                                    </a>
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
