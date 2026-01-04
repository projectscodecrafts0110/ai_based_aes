@extends('layouts.admin')

@section('title', 'Clusters')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">K-Means Cluster</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Applicant Clusters (AI Score vs Qualification Match)</h5>
                <canvas id="clusterChart" height="400"></canvas>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        // Assign colors for clusters
        const clusterColors = ['#28a745', '#ffc107', '#dc3545', '#007bff', '#6f42c1'];

        const datasets = [];

        // Group points by cluster
        const clustersMap = {};
        chartData.forEach(point => {
            const c = point.cluster;
            if (!clustersMap[c]) clustersMap[c] = [];
            clustersMap[c].push({
                x: point.x,
                y: point.y,
                label: point.label
            });
        });

        Object.keys(clustersMap).forEach(cluster => {
            datasets.push({
                label: 'Cluster ' + cluster,
                data: clustersMap[cluster],
                backgroundColor: clusterColors[cluster % clusterColors.length],
            });
        });

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
                                return `${d.label}: AI Score ${d.x}, Match ${d.y}%`;
                            }
                        }
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
