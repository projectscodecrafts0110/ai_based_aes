<?php

namespace App\Services;

use App\Models\Application;

class KMeansService
{
    /**
     * Apply simple K-Means clustering on applicants
     *
     * @param int $k Number of clusters
     * @return array
     */
    public function clusterApplicants(int $k = 3): array
    {
        $applications = Application::whereNotNull('ai_score')
            ->whereNotNull('qualification_match')
            ->get(['id', 'ai_score', 'qualification_match', 'job_id']);

        if ($applications->isEmpty()) {
            return [];
        }

        // Prepare data points
        $points = $applications->map(fn($app) => [
            'id' => $app->id,
            'features' => [(float)$app->ai_score, (float)$app->qualification_match],
        ])->values()->all();

        // Randomly initialize centroids
        $centroids = [];
        for ($i = 0; $i < $k; $i++) {
            $centroids[] = $points[array_rand($points)]['features'];
        }

        $assignments = [];
        $changed = true;
        $maxIterations = 100;
        $iteration = 0;

        while ($changed && $iteration < $maxIterations) {
            $changed = false;
            $iteration++;

            // Assign points to nearest centroid
            foreach ($points as $index => $point) {
                $distances = array_map(fn($centroid) => $this->distance($point['features'], $centroid), $centroids);
                $cluster = array_search(min($distances), $distances);

                if (!isset($assignments[$index]) || $assignments[$index] !== $cluster) {
                    $changed = true;
                    $assignments[$index] = $cluster;
                }
            }

            // Update centroids
            for ($i = 0; $i < $k; $i++) {
                $clusterPoints = array_filter(array_values($points), fn($p, $idx) => $assignments[$idx] === $i, ARRAY_FILTER_USE_BOTH);

                if (empty($clusterPoints)) continue;

                $centroids[$i] = [
                    array_sum(array_column(array_column($clusterPoints, 'features'), 0)) / count($clusterPoints),
                    array_sum(array_column(array_column($clusterPoints, 'features'), 1)) / count($clusterPoints),
                ];
            }
        }

        // Return assignments as [app_id => cluster_number]
        return collect($points)->mapWithKeys(fn($p, $idx) => [$p['id'] => $assignments[$idx]])->toArray();
    }

    private function distance(array $a, array $b): float
    {
        return sqrt(pow($a[0] - $b[0], 2) + pow($a[1] - $b[1], 2));
    }
}
