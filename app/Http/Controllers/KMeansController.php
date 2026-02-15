<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\KMeansService;
use App\Models\Application;

class KMeansController extends Controller
{
    public function index()
    {
        $applications = Application::with('job')->orderByDesc('ai_score')->get();
        $kMeans = new KMeansService();

        $clusters = $kMeans->clusterApplicants(3); // 3 clusters for example

        // Prepare chart data
        $chartData = $applications->map(function ($app) use ($clusters) {
            return [
                'x' => $app->ai_score ?? 0,
                'y' => $app->qualification_match ?? 0,
                'label' => $app->full_name,
                'position' => $app->job->title ?? 'N/A',
                'recommendation' => $app->ai_recommendation ?? 'N/A',
                'cluster' => $clusters[$app->id] ?? 0
            ];
        });

        return view('admin.clusters', [
            'applications' => $applications,
            'clusters' => $clusters,
            'chartData' => $chartData
        ]);
    }
}
