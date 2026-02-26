@extends('layouts.admin')

@section('title', 'Clusters')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Rankings</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Applicant Clusters</h5>
                <div style="height: 500px; width: 100%; overflow-x: auto;">
                    <canvas id="clusterChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        // 1. Get unique job titles for X axis
        const jobTitles = [...new Set(chartData.map(p => p.position))];

        // 2. Map job title to numeric index
        const jobIndexMap = {};
        jobTitles.forEach((title, index) => {
            jobIndexMap[title] = index;
        });

        // 3. Color mapping based on AI recommendation
        const recommendationColors = {
            'Highly Recommended': '#28a745', // green
            'Consider': '#ffc107', // yellow
            'Rejected': '#dc3545' // red
        };

        // 4. Group data by recommendation
        const recommendationMap = {
            'Highly Recommended': [],
            'Consider': [],
            'Rejected': []
        };

        chartData.forEach(p => {
            if (recommendationMap[p.recommendation]) {
                recommendationMap[p.recommendation].push(p);
            }
        });

        // 5. Build datasets
        const datasets = Object.keys(recommendationMap).map(label => ({
            label: label,
            data: recommendationMap[label].map(p => ({
                x: jobIndexMap[p.position], // numeric index
                y: p.y, // qualification match
                name: p.label,
                recommendation: p.recommendation,
                ai_score: p.ai_score,
                position: p.position,
                campus: p.campus,
                department: p.department
            })),
            backgroundColor: recommendationColors[label],
            pointRadius: 12,
            pointHoverRadius: 16,
        }));

        // 6. Render chart
        const ctx = document.getElementById('clusterChart').getContext('2d');

        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const d = context.raw;
                                return [
                                    `Name: ${d.name}`,
                                    `Job: ${d.position}`,
                                    `Campus: ${d.campus}`,
                                    `Department: ${d.department}`,
                                    `Recommendation: ${d.recommendation}`,
                                    `Match: ${d.y}%`
                                ];
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        ticks: {
                            callback: function(value) {
                                return jobTitles[value] || '';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Job Title'
                        },
                        min: -0.5,
                        max: jobTitles.length - 0.5
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Qualification Match (%)'
                        },
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 10,
                            autoSkip: false
                        }
                    }
                }
            }
        });
    </script>

@endsection
