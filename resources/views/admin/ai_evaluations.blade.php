@extends('layouts.admin')

@section('title', 'AI Evaluation Results')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Initial Evaluation Results</h2>
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.ai-evaluations') }}" class="row g-3">

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
                        <a href="{{ route('admin.ai-evaluations') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>

                </form>
            </div>
        </div>
        <table class="table table-bordered table-hover table-fixed">
            <thead>
                <tr class="text-center">
                    <th>Applicant Name</th>
                    <th>Job Applied</th>
                    <th>Campus</th>
                    <th>Department</th>
                    <th>Recommendation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applications as $index => $app)
                    <tr>
                        <td>{{ $app->full_name }}</td>
                        <td>{{ $app->job->title }}</td>
                        <td>{{ $app->job->campus }}</td>
                        <td>{{ $app->job->department }}</td>
                        <td>{{ $app->ai_recommendation ?? 'N/A' }}</td>
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
                        <td>
                            <!-- View AI Summary Modal Trigger -->
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#aiSummaryModal{{ $app->id }}">
                                <i class="bi bi-eye" data-toggle="tooltip" data-placement="top"
                                    title="View More Details"></i>
                            </button>

                            <!-- Actions -->
                            <form action="{{ route('admin.update_application_status', $app->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button name="status" value="Approved" class="btn btn-sm btn-success"><i
                                        class="bi bi-check-circle" data-toggle="tooltip" data-placement="top"
                                        title="Approve"></i></button>
                                <button name="status" value="Under Review" class="btn btn-sm btn-primary"><i
                                        class="bi bi-clock" data-toggle="tooltip" data-placement="top"
                                        title="Mark as Under Review"></i></button>
                                <button name="status" value="Rejected" class="btn btn-sm btn-danger"><i
                                        class="bi bi-x-circle" data-toggle="tooltip" data-placement="top"
                                        title="Reject"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- AI Summary Modal -->
                    <div class="modal fade" id="aiSummaryModal{{ $app->id }}" tabindex="-1"
                        aria-labelledby="aiSummaryLabel{{ $app->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="aiSummaryLabel{{ $app->id }}">
                                        AI Evaluation Summary - {{ $app->full_name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    {{-- AI Info --}}
                                    <p><strong>Qualification Match:</strong>
                                        {{ $app->qualification_match ? $app->qualification_match . '%' : 'N/A' }}
                                    </p>
                                    <p><strong>Recommendation:</strong> {{ $app->ai_recommendation ?? 'N/A' }}</p>
                                    <p><strong>Evaluated at:</strong> {{ $app->ai_evaluated_at }}</p>
                                    <hr>

                                    <p><strong>Justification:</strong></p>
                                    <p>{!! nl2br(e($app->ai_summary ?? 'No summary available.')) !!}</p>

                                    <hr>
                                    <p><strong>Uploaded Documents:</strong></p>

                                    @php
                                        $files = [];

                                        // Single file fields (only if not empty)
                                        if (!empty($app->application_letter)) {
                                            $files['Application Letter'] = $app->application_letter;
                                        }

                                        if (!empty($app->pds)) {
                                            $files['PDS'] = $app->pds;
                                        }

                                        if (!empty($app->otr_diploma)) {
                                            $files['OTR/Diploma'] = $app->otr_diploma;
                                        }

                                        if (!empty($app->certificate_eligibility)) {
                                            $files['Certificate Eligibility'] = $app->certificate_eligibility;
                                        }

                                        // Multiple certificates (array safe)
                                        if (
                                            !empty($app->certificates_training) &&
                                            is_array($app->certificates_training)
                                        ) {
                                            foreach ($app->certificates_training as $index => $cert) {
                                                if (!empty($cert)) {
                                                    $files['Certificate ' . ($index + 1)] = $cert;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if (count($files) > 0)
                                        <div id="carousel{{ $app->id }}" class="carousel slide"
                                            data-bs-ride="carousel">
                                            <div class="carousel-inner">

                                                @foreach ($files as $label => $file)
                                                    @php
                                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                        $fileUrl = asset('storage/' . $file);
                                                    @endphp

                                                    <div
                                                        class="carousel-item @if ($loop->first) active @endif">
                                                        <h6 class="text-center">{{ $label }}</h6>

                                                        @if ($ext === 'pdf')
                                                            <embed src="{{ $fileUrl }}" type="application/pdf"
                                                                width="100%" height="500px">
                                                        @elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                                                            <img src="{{ $fileUrl }}" class="d-block w-100"
                                                                alt="{{ $label }}">
                                                        @else
                                                            <p class="text-center">
                                                                <a href="{{ $fileUrl }}" target="_blank">
                                                                    View {{ $label }}
                                                                </a>
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach

                                            </div>

                                            {{-- Controls --}}
                                            @if (count($files) > 1)
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#carousel{{ $app->id }}" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon"></span>
                                                </button>

                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#carousel{{ $app->id }}" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon"></span>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted text-center">No documents uploaded.</p>
                                    @endif

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No job vacancies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
