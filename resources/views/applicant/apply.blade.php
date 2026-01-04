@extends('layouts.app')

@section('title', 'Apply for Job')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Job Application Form</h2>

        <form method="POST" action="" enctype="multipart/form-data">
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
                            <option value="" disabled selected>Select a position</option>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}">
                                    {{ $job->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <!-- EDUCATION -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Educational Background</h5>

                    <div class="mb-3">
                        <label for="higher_education" class="form-label">Highest Educational Attainment</label>
                        <input type="text" class="form-control" id="higher_education" name="higher_education"
                            placeholder="e.g., Bachelor of Science in Information Technology" required>
                    </div>

                    <div class="mb-3">
                        <label for="major" class="form-label">Major</label>
                        <input type="text" class="form-control" id="major" name="major"
                            placeholder="e.g., Computer Science" required>
                    </div>
                </div>
            </div>

            <!-- DOCUMENTS -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Attachments</h5>

                    <div class="mb-3">
                        <label for="application_letter" class="form-label">Application Letter</label>
                        <input type="file" class="form-control" id="application_letter" name="application_letter"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="resume" class="form-label">Resume / CV</label>
                        <input type="file" class="form-control" id="resume" name="resume" required>
                    </div>

                    <div class="mb-3">
                        <label for="pds" class="form-label">Personal Data Sheet (PDS)</label>
                        <input type="file" class="form-control" id="pds" name="pds" required>
                    </div>

                    <div class="mb-3">
                        <label for="certificates" class="form-label">Certificates</label>
                        <input type="file" class="form-control" id="certificates" name="certificates" multiple>
                    </div>

                    <div class="mb-3">
                        <label for="otr" class="form-label">Official Transcript of Records (OTR)</label>
                        <input type="file" class="form-control" id="otr" name="otr" required>
                    </div>
                </div>
            </div>


            <!-- RATINGS / OTR -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Additional Information</h5>

                    <div class="mb-3">
                        <label for="ratings" class="form-label">Ratings (if any)</label>
                        <input type="text" class="form-control" id="ratings" name="ratings"
                            placeholder="Enter ratings">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
        </form>
    </div>
@endsection
