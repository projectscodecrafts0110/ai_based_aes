<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $applications = Application::where('user_id', Auth::id())->latest()->get();
        $positions = JobVacancy::all();
        return view('applicant/dashboard', compact('applications', 'positions'));
    }
}
