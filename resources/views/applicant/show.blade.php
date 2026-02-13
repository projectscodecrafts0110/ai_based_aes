@extends('layouts.app')

@section('title', 'View Application')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Application Details</h2>

        <div class="card shadow-sm mb-4 p-4">
            <!-- PERSONAL INFORMATION -->
            <h5 class="fw-semibold mb-3">Personal Information</h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Full Name:</strong> {{ $application->full_name }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $application->email }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Contact Number:</strong> {{ $application->contact_number }}</div>
                <div class="col-md-6"><strong>Address:</strong> {{ $application->address }}</div>
            </div>

            <hr>

            <!-- JOB INFORMATION -->
            <h5 class="fw-semibold mb-3">Job Information</h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Job Applying:</strong> {{ $application->job->title }}</div>
                <div class="col-md-6"><strong>Qualifications:</strong> {{ $application->job->qualifications }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Higher Education:</strong> {{ $application->higher_education }}</div>
                <div class="col-md-6"><strong>Major:</strong> {{ $application->major }}</div>
            </div>

            <hr>

            <!-- UPLOADED DOCUMENTS -->
            <h5 class="fw-semibold mb-3">Uploaded Documents</h5>
            <ul class="list-group list-group-flush">
                @php
                    $files = [
                        'Application Letter' => $application->application_letter,
                        'Resume' => $application->resume,
                        'PDS' => $application->pds,
                        'OTR (Transcript)' => $application->otr,
                        'Certificates' => $application->certificates,
                    ];
                @endphp

                @foreach ($files as $label => $file)
                    @if ($file)
                        @if (is_array($file))
                            @foreach ($file as $f)
                                <li class="list-group-item">
                                    <strong>{{ $label }}:</strong>
                                    <a href="{{ asset('storage/' . $f) }}" target="_blank">View / Download</a>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item">
                                <strong>{{ $label }}:</strong>
                                <a href="{{ asset('storage/' . $file) }}" target="_blank">View / Download</a>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>

            <hr>

            <!-- EVALUATION -->
            <h5 class="fw-semibold mb-3">Evaluation</h5>
            <div class="row">
                <div class="col-md-6">
                    <strong>Ratings:</strong> {{ $application->ratings ?? 'Not Evaluated' }}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    @php
                        $status = $application->status;
                        $badgeClass = match ($status) {
                            'Pending' => 'bg-warning text-dark',
                            'Under Review' => 'bg-primary',
                            'Approved' => 'bg-success',
                            'Rejected' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                </div>
            </div>

            <hr>

            <!-- AI EVALUATION -->
            <h5 class="fw-semibold mb-3">AI Evaluation</h5>

            @if ($application->ai_score !== null)
                @php
                    $aiBadge = match ($application->ai_recommendation) {
                        'Accept' => 'bg-success',
                        'Consider' => 'bg-warning text-dark',
                        'Reject' => 'bg-danger',
                        default => 'bg-secondary',
                    };
                @endphp

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>AI Score:</strong>
                        <span class="badge bg-info text-dark ms-2">
                            {{ $application->ai_score }}/100
                        </span>
                    </div>

                    <div class="col-md-4">
                        <strong>Recommendation:</strong>
                        <span class="badge {{ $aiBadge }} ms-2">
                            {{ $application->ai_recommendation }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <strong>Evaluated At:</strong>
                        {{ $application->ai_evaluated_at }}
                    </div>
                </div>

                @if ($application->ai_summary)
                    <div class="alert alert-light border">
                        <strong>AI Summary:</strong>
                        <p class="mb-0 mt-2 text-muted">
                            {{ $application->ai_summary }}
                        </p>
                    </div>
                @endif
            @else
                <div class="alert alert-secondary">
                    <strong>AI Evaluation:</strong> Pending
                </div>
            @endif

        </div>

        <a href="{{ route('applications.status') }}" class="btn btn-secondary">Back to Applications</a>
    </div>
@endsection
