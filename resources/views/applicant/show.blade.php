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

            <h5 class="fw-semibold mb-3">Education & Experience</h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Education:</strong> {{ $application->education }}</div>
                <div class="col-md-6"><strong>Experience:</strong> {{ $application->work_experience }}</div>
                <div class="col-md-6"><strong>Training:</strong> {{ $application->training }}</div>
                <div class="col-md-6"><strong>Eligibility:</strong> {{ $application->eligibility }}</div>
            </div>

            <hr>

            <!-- JOB INFORMATION -->
            <h5 class="fw-semibold mb-3">Job Applying</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-2"><strong>Title:</strong> {{ $application->job->title }}</div>
                <div class="col-md-6 mb-2"><strong>Description:</strong> {{ $application->job->description }}</div>
                <div class="col-md-6 mb-2"><strong>Type:</strong> {{ ucfirst($application->job->job_type) }}</div>
                <div class="col-md-6 mb-2"><strong>Employment Status:</strong>
                    {{ ucfirst($application->job->employment_status) }}</div>
                <div class="col-md-6 mb-2"><strong>Campus:</strong> {{ $application->job->campus }}</div>
                <div class="col-md-6 mb-2"><strong>Department:</strong> {{ $application->job->department }}</div>
                <div class="col-md-12 mt-2">
                    <div class="card bg-light">
                        <div class="card-body">
                            <strong>Qualifications:</strong>
                            <ul>
                                @php
                                    $qualifications = $application->job->qualifications;
                                @endphp
                                <li><strong>Education:</strong> {{ $qualifications['education'] }}</li>
                                <li><strong>Experience:</strong> {{ $qualifications['experience'] }}</li>
                                <li><strong>Training:</strong> {{ $qualifications['training'] }}</li>
                                <li><strong>Eligibility:</strong> {{ $qualifications['eligibility'] }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- UPLOADED DOCUMENTS -->
            <h5 class="fw-semibold mb-3">Uploaded Documents</h5>
            <ul class="list-group list-group-flush">
                @php
                    $files = [
                        'Application Letter' => $application->application_letter,
                        'PDS' => $application->pds,
                        'OTR (Transcript) / Diploma' => $application->otr_diploma,
                        'Certificate Eligibility' => $application->certificate_eligibility,
                        'Certificate Training' => $application->certificates_training,
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

            <!-- AI EVALUATION -->
            <h5 class="fw-semibold mb-3">Evaluation Summary</h5>

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
                        <strong>Recommendation:</strong>
                        <span class="badge {{ $aiBadge }} ms-2">
                            {{ $application->ai_recommendation }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <strong>Qualification Match:</strong>
                        <span class="badge {{ $aiBadge }} ms-2">
                            {{ $application->qualification_match }}%
                        </span>
                    </div>

                    <div class="col-md-4">
                        <strong>Evaluated At:</strong>
                        {{ $application->ai_evaluated_at }}
                    </div>
                </div>

                @if ($application->ai_summary)
                    <div class="alert alert-light border">
                        <strong>Justification</strong>
                        <p class="mb-0 mt-2 text-muted">
                            {!! nl2br(e($application->ai_summary)) !!}
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
