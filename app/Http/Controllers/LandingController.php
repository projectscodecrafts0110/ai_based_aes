<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;

class LandingController extends Controller
{
    public function index()
    {
        $jobs = JobVacancy::all();
        return view('index', compact('jobs'));
    }
}
