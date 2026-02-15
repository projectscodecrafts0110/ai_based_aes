@extends('layouts.admin')

@section('title', 'Clusters')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">K-Means Cluster</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Applicant Clusters</h5>
                <div style="max-height: 600px; width: 100%; overflow-x: auto;">
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

        const positionMap = {};
        chartData.forEach(p => {
            const pos = p.position;
            if (!positionMap[pos]) positionMap[pos] = [];
            positionMap[pos].push(p);
        });

        const colors = ['#28a745', '#ffc107', '#dc3545', '#007bff', '#6f42c1', '#fd7e14', '#20c997', '#6610f2'];

        const datasets = Object.keys(positionMap).map((position, i) => ({
            label: position,
            data: positionMap[position].map(p => ({
                x: p.x,
                y: p.y,
                label: p.label,
                recommendation: p.recommendation
            })),
            backgroundColor: colors[i % colors.length],
            pointRadius: 12,
            pointHoverRadius: 15
        }));

        const ctx = document.getElementById('clusterChart').getContext('2d');

        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const d = context.raw;
                                return `${d.label} (${d.recommendation}): AI Score ${d.x}, Match ${d.y}%`;
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'AI Score'
                        },
                        min: 0,
                        max: 100
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Qualification Match (%)'
                        },
                        min: 0,
                        max: 100
                    }
                }
            }
        });
    </script>
@endsection
