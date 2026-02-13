@extends('layouts.public')

@section('title', 'AI-Based Applicant Evaluation System')

@section('content')

    <!-- HERO -->
    <section class="hero container min-vh-100">
        <div class="row align-items-center w-100">

            <div class="col-md-6">
                <div class="">
                    <img src="{{ asset('images/ispsc_logo.png') }}" alt="" class="mb-2 img-fluid" style="width:120px;">
                </div>
                <h1 class="fw-bold display-5">
                    AI-Based Applicant <br> Evaluation System
                </h1>
                <p class="text-muted mt-3">
                    A smart recruitment platform that leverages Artificial Intelligence
                    to evaluate, rank, and shortlist applicants efficiently and fairly.
                </p>

                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4 ms-2">
                        Apply Now
                    </a>
                </div>
            </div>

            <div class="col-md-6 text-center">
                <img src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?q=80&w=1120&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="img-fluid rounded">
            </div>

        </div>
    </section>

    <!-- CAROUSEL -->
    <div id="campusCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner" style="max-height:450px;">
            <div class="carousel-item active">
                <img src="https://plus.unsplash.com/premium_photo-1676666379051-383ed1b005e8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1487528278747-ba99ed528ebc?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="d-block w-100">
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1762330463032-06f873b3277c?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="d-block w-100">
            </div>
        </div>
    </div>

    <!-- AVAILABLE POSITIONS -->
    <section class="container pb-5">
        <div class="card">
            <div class="card-body m">
                <h3 class="fw-bold text-center mb-4">
                    <i class="bi bi-briefcase"></i> Available Positions
                </h3>

                <!-- FILTER BUTTONS -->
                <div class="mb-4 text-center">
                    <button class="btn btn-outline-primary btn-sm filter-btn active" data-filter="all">
                        All Courses
                    </button>
                    @foreach ($jobs->pluck('course')->unique() as $course)
                        <button class="btn btn-outline-primary btn-sm filter-btn"
                            data-filter="{{ strtolower(str_replace(' ', '-', $course)) }}">
                            {{ $course }}
                        </button>
                    @endforeach
                </div>

                <div class="row g-4" id="jobsContainer">
                    @foreach ($jobs as $job)
                        <div class="col-md-4 job-item" data-filter="{{ strtolower(str_replace(' ', '-', $job->course)) }}">
                            <div class="card h-100 job-card" data-bs-toggle="modal"
                                data-bs-target="#jobModal{{ $job->id }}" style="cursor:pointer;">
                                <div class="card-body">
                                    <h5>{{ $job->title }}</h5>
                                    <p class="text-muted small">
                                        {{ Str::limit($job->qualifications, 90) }}
                                    </p>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Open
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL -->
                        <div class="modal fade" id="jobModal{{ $job->id }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>{{ $job->title }}</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6><i class="bi bi-list-check"></i> Qualifications</h6>
                                        <ul>
                                            @foreach (explode(',', $job->qualifications) as $qualification)
                                                <li>{{ trim($qualification) }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('register') }}" class="btn btn-primary">
                                            Apply Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                document.querySelectorAll('.job-item').forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-filter') === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <!-- ABOUT -->
    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-6 text-center">
                <img src="https://source.unsplash.com/600x400/?data,analysis,computer" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h3 class="fw-bold">
                    <i class="bi bi-info-circle"></i> About the System
                </h3>
                <p class="text-muted mt-3">
                    The AI-Based Applicant Evaluation System is designed to assist
                    organizations in making data-driven recruitment decisions.
                    It uses intelligent algorithms to analyze applicant profiles,
                    compare them against job requirements, and generate rankings.
                </p>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="bg-light py-5">
        <div class="container">
            <h3 class="fw-bold text-center mb-4">
                <i class="bi bi-stars"></i> Key Features
            </h3>

            <div class="row text-center">
                <div class="col-md-4">
                    <i class="bi bi-cpu display-5 text-primary"></i>
                    <h5 class="mt-2">AI-Powered Evaluation</h5>
                    <p class="text-muted">
                        Automatically assesses applicants using intelligent scoring models.
                    </p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-shield-check display-5 text-primary"></i>
                    <h5 class="mt-2">Fair & Unbiased</h5>
                    <p class="text-muted">
                        Minimizes human bias and ensures objective screening.
                    </p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-speedometer2 display-5 text-primary"></i>
                    <h5 class="mt-2">Efficient Process</h5>
                    <p class="text-muted">
                        Reduces recruitment time and manual workload.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="container py-5">
        <h3 class="fw-bold text-center mb-4">
            <i class="bi bi-diagram-3"></i> How It Works
        </h3>

        <div class="row text-center">
            <div class="col-md-3">
                <i class="bi bi-person-plus display-6 text-primary"></i>
                <h6 class="mt-2">1. Register</h6>
                <p class="text-muted">Create your applicant account.</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-file-earmark-text display-6 text-primary"></i>
                <h6 class="mt-2">2. Apply</h6>
                <p class="text-muted">Submit personal and academic details.</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-robot display-6 text-primary"></i>
                <h6 class="mt-2">3. AI Screening</h6>
                <p class="text-muted">System evaluates and scores profiles.</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-bar-chart-line display-6 text-primary"></i>
                <h6 class="mt-2">4. Results</h6>
                <p class="text-muted">Applicants are ranked and shortlisted.</p>
            </div>
        </div>
    </section>

@endsection
