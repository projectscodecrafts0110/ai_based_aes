@extends('layouts.admin')

@section('title', 'Admin - Dashboard')

@section('content')
    <div class="container py-5">
        <!-- Welcome Message -->
        <div class="mb-4">
            <h2 class="fw-bold">Welcome, {{ Auth::user()->name }}!</h2>
            <p class="text-muted">Here’s a quick overview of the applicant evaluations.</p>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-2">
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <h5>Accepted</h5>
                    <h3 class="fw-bold text-success">{{ $accepted ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <h5>Rejected</h5>
                    <h3 class="fw-bold text-danger">{{ $rejected ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <h5>Under Review</h5>
                    <h3 class="fw-bold text-primary">{{ $underReview ?? 0 }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <h5>Pending</h5>
                    <h3 class="fw-bold text-warning">{{ $pending ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Applicants Text -->
        <p class="text-muted mt-2 text-center">Total Applicants: <b>{{ $totalApplicants ?? 0 }}</b></p>

        <!-- Charts -->
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h5 class="mb-3">Applicants per Job Vacancy</h5>
                    <canvas id="jobApplicantsChart" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h5 class="mb-3">Application Status Distribution</h5>
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Applicants per Job Vacancy
        const jobCtx = document.getElementById('jobApplicantsChart').getContext('2d');
        const jobApplicantsChart = new Chart(jobCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($jobLabels ?? []) !!},
                datasets: [{
                    label: 'Applicants',
                    data: {!! json_encode($jobCounts ?? []) !!},
                    backgroundColor: '#4A90E2'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Application Status Distribution
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Accepted', 'Rejected', 'Under Review', 'Pending'],
                datasets: [{
                    data: [
                        {{ $accepted ?? 0 }},
                        {{ $rejected ?? 0 }},
                        {{ $underReview ?? 0 }},
                        {{ $pending ?? 0 }}
                    ],
                    backgroundColor: ['#28a745', '#dc3545', '#007bff', '#ffc107']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
