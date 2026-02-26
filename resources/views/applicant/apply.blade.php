@extends('layouts.app')

@section('title', 'Apply for Job')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Job Application Form</h2>

        <form method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- PERSONAL INFORMATION -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Personal Information</h5>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required
                            placeholder="Enter your full name" value="{{ auth()->user()->name }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required
                            value="{{ auth()->user()->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number"
                            placeholder="Enter your contact number" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter your address" required></textarea>
                    </div>
                </div>
            </div>

            <!-- JOB INFORMATION -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Job Information</h5>

                    <div class="mb-3">
                        <label for="job_id" class="form-label">Job Applying</label>
                        <select class="form-select" id="job_id" name="job_id" required>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}" selected>
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Job details container -->
                        <div id="job_details" class="mt-3 p-3 border rounded bg-light" style="display:none;"></div>
                    </div>

                </div>
            </div>

            <!-- EDUCATION & EXPERIENCE -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Education & Experience</h5>

                    <div class="mb-3">
                        <label for="education" class="form-label">Education</label>
                        <input type="text" class="form-control" id="education" name="education"
                            placeholder="e.g., Bachelor of Science in IT" required>
                    </div>

                    <div class="mb-3">
                        <label for="training" class="form-label">Training</label>
                        <input type="text" class="form-control" id="training" name="training"
                            placeholder="Relevant trainings and seminars">
                    </div>

                    <div class="mb-3">
                        <label for="eligibility" class="form-label">Eligibility</label>
                        <input type="text" class="form-control" id="eligibility" name="eligibility"
                            placeholder="e.g., LET Passer, RA1080">
                    </div>

                    <div class="mb-3">
                        <label for="work_experience" class="form-label">Work Experience</label>
                        <textarea class="form-control" id="work_experience" name="work_experience" rows="3"
                            placeholder="Brief description of relevant experience"></textarea>
                    </div>
                </div>
            </div>

            <!-- DOCUMENTS / ATTACHMENTS -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Attachments</h5>

                    <div class="mb-3">
                        <label for="application_letter" class="form-label">Application Letter</label>
                        <input type="file" class="form-control" id="application_letter" name="application_letter"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="pds" class="form-label">Personal Data Sheet (PDS)</label>
                        <input type="file" class="form-control" id="pds" name="pds" required>
                    </div>

                    <div class="mb-3">
                        <label for="otr_diploma" class="form-label">Official Transcript of Records (OTR) / Diploma</label>
                        <input type="file" class="form-control" id="otr_diploma" name="otr_diploma" required>
                    </div>

                    <div class="mb-3">
                        <label for="certificate_eligibility" class="form-label">Certificate of Eligibility</label>
                        <input type="file" class="form-control" id="certificate_eligibility"
                            name="certificate_eligibility">
                    </div>

                    <div class="mb-3">
                        <label for="certificates_training" class="form-label">Certificates of Trainings and
                            Seminars</label>
                        <input type="file" class="form-control" id="certificates_training"
                            name="certificates_training[]" multiple>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
        </form>
    </div>

    @if ($noRelatedJobs)
        <div class="modal fade show" style="display:block; background:rgba(0,0,0,.6)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">

                    <i class="bi bi-robot text-primary display-4"></i>
                    <h5 class="mt-3">AI Advisory</h5>

                    <p class="text-muted">
                        No jobs matched your selected criteria. Please check back later or explore other opportunities on
                        our platform.
                    </p>

                    <div class="mt-3">
                        <a href="{{ route('apply.filter') }}" class="btn btn-outline-danger ms-2">
                            Okay, Back to Filter
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent form resubmission on reload
            window.history.replaceState({}, document.title, '/apply/form');

            // Job qualifications object
            const jobQualifications = {
                @foreach ($jobs as $job)
                    "{{ $job->id }}": {
                        education: "{{ $job->qualifications['education'] ?? 'N/A' }}",
                        experience: "{{ $job->qualifications['experience'] ?? 'N/A' }}",
                        training: "{{ $job->qualifications['training'] ?? 'N/A' }}",
                        eligibility: "{{ $job->qualifications['eligibility'] ?? 'N/A' }}",
                        campus: "{{ $job->campus ?? 'N/A' }}",
                        department: "{{ $job->department ?? 'N/A' }}",
                        course: "{{ $job->course ?? 'N/A' }}"
                    },
                @endforeach
            };

            const jobSelect = document.getElementById('job_id');
            const detailsDiv = document.getElementById('job_details');

            jobSelect.addEventListener('change', function() {
                const selectedId = this.value;
                if (selectedId && jobQualifications[selectedId]) {
                    const qual = jobQualifications[selectedId];
                    detailsDiv.innerHTML = `
                    <strong>Campus:</strong> ${qual.campus}
                    <br>
                    <strong>Department:</strong> ${qual.department}
                    <br>
                    <strong>Course:</strong> ${qual.course}
                    <hr>
                <h6>Qualifications:</h6>
                <ul class="mb-0">
                    <li><strong>Education:</strong> ${qual.education}</li>
                    <li><strong>Experience:</strong> ${qual.experience}</li>
                    <li><strong>Training:</strong> ${qual.training}</li>
                    <li><strong>Eligibility:</strong> ${qual.eligibility}</li>
                </ul>
            `;
                    detailsDiv.style.display = 'block';
                } else {
                    detailsDiv.style.display = 'none';
                }
            });
        });
    </script>
@endpush
