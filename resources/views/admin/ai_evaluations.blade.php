@extends('layouts.admin')

@section('title', 'AI Evaluation Results')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">AI Evaluation Results</h2>

        <table class="table table-bordered table-hover table-fixed">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Applicant Name</th>
                    <th>Job Applied</th>
                    <th>Recommendation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $index => $app)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $app->full_name }}</td>
                        <td>{{ $app->job->title }}</td>
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
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- Actions -->
                            <form action="{{ route('admin.update_application_status', $app->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button name="status" value="Approved" class="btn btn-sm btn-success"><i
                                        class="bi bi-check-circle"></i></button>
                                <button name="status" value="Under Review" class="btn btn-sm btn-primary"><i
                                        class="bi bi-clock"></i></button>
                                <button name="status" value="Rejected" class="btn btn-sm btn-danger"><i
                                        class="bi bi-x-circle"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- AI Summary Modal -->
                    <div class="modal fade" id="aiSummaryModal{{ $app->id }}" tabindex="-1"
                        aria-labelledby="aiSummaryLabel{{ $app->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="aiSummaryLabel{{ $app->id }}">AI Evaluation Summary -
                                        {{ $app->full_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- AI Evaluation Info -->
                                    <p><strong>AI Score:</strong> {{ $app->ai_score ?? 'N/A' }}</p>
                                    <p><strong>Qualification Match:</strong> {{ $app->qualification_match ?? 'N/A' }}%</p>
                                    <p><strong>AI Recommendation:</strong> {{ $app->ai_recommendation ?? 'N/A' }}</p>
                                    <hr>
                                    <p><strong>Justification / AI Summary:</strong></p>
                                    <p>{{ $app->ai_summary ?? 'No summary available.' }}</p>

                                    <hr>
                                    <p><strong>Uploaded Documents:</strong></p>

                                    <!-- Carousel -->
                                    <div id="carousel{{ $app->id }}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">

                                            @php
                                                $files = [
                                                    'Application Letter' => $app->application_letter,
                                                    'Resume' => $app->resume,
                                                    'PDS' => $app->pds,
                                                    'OTR' => $app->otr,
                                                ];

                                                if ($app->certificates) {
                                                    foreach ($app->certificates as $index => $cert) {
                                                        $files['Certificate ' . ($index + 1)] = $cert;
                                                    }
                                                }
                                            @endphp

                                            @foreach ($files as $label => $file)
                                                <div class="carousel-item @if ($loop->first) active @endif">
                                                    <h6 class="text-center">{{ $label }}</h6>

                                                    @php
                                                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                                                        $fileUrl = asset('storage/' . $file);
                                                    @endphp

                                                    @if (in_array($ext, ['pdf']))
                                                        <embed src="{{ $fileUrl }}" type="application/pdf"
                                                            width="100%" height="500px">
                                                    @elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img src="{{ $fileUrl }}" class="d-block w-100"
                                                            alt="{{ $label }}">
                                                    @else
                                                        <p class="text-center">
                                                            <a href="{{ $fileUrl }}" target="_blank">View
                                                                {{ $label }}</a>
                                                        </p>
                                                    @endif
                                                </div>
                                            @endforeach

                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carousel{{ $app->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carousel{{ $app->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
