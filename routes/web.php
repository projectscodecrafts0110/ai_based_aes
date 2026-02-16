<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KMeansController;
use App\Http\Controllers\LandingController;

Route::get(
    '/api/departments/{campus}',
    [ApplicationController::class, 'getDepartmentsByCampus']
);


Route::middleware('guest')->group(function () {

    Route::get('/', [LandingController::class, 'index'])->name('landing');

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::get('/signup', function () {
        return view('signup');
    })->name('signup');

    Route::post('/signup', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {

    // Dashboard Page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // About Page
    Route::get('/about', function () {
        return view('applicant/about');
    })->name('about');

    // Contact Page
    Route::get('/contact', function () {
        return view('applicant/contact');
    })->name('contact');

    // Application Routes

    Route::get('/apply/filter', [ApplicationController::class, 'index'])->name('apply.filter');
    Route::post('/apply/form', [ApplicationController::class, 'create'])->name('apply');
    Route::post('/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/status', [ApplicationController::class, 'status'])->name('applications.status');
    Route::get('/applications/status/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/status/{id}/download', [ApplicationController::class, 'download'])->name('applications.download');


    // Logout Route
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
        ->name('logout');

    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/applications', [AdminController::class, 'manageApplications'])->name('admin.applications');
    Route::delete('/admin/applications/{application}', [AdminController::class, 'deleteApplication'])->name('admin.applications.destroy');
    Route::get('/admin/ai-evaluations', [AdminController::class, 'aiEvaluations'])->name('admin.ai-evaluations');
    Route::post('/applications/{application}/status', [AdminController::class, 'updateApplicationStatus'])
        ->name('admin.update_application_status');

    Route::get('/admin/job-vacancies', [App\Http\Controllers\Admin\JobVacancyController::class, 'index'])->name('admin.job_vacancies.index');
    Route::get('/admin/job-vacancies/create', [App\Http\Controllers\Admin\JobVacancyController::class, 'create'])->name('admin.job_vacancies.create');
    Route::post('/admin/job-vacancies', [App\Http\Controllers\Admin\JobVacancyController::class, 'store'])->name('admin.job_vacancies.store');
    Route::get('/admin/job-vacancies/{jobVacancy}/edit', [App\Http\Controllers\Admin\JobVacancyController::class, 'edit'])->name('admin.job_vacancies.edit');
    Route::put('/admin/job-vacancies/{jobVacancy}', [App\Http\Controllers\Admin\JobVacancyController::class, 'update'])->name('admin.job_vacancies.update');
    Route::delete('/admin/job-vacancies/{jobVacancy}', [App\Http\Controllers\Admin\JobVacancyController::class, 'destroy'])->name('admin.job_vacancies.destroy');
    Route::put('/admin/job_vacancies/{id}/toggle-status', [App\Http\Controllers\Admin\JobVacancyController::class, 'toggleStatus'])
        ->name('admin.job_vacancies.toggle_status');


    Route::get('/admin/k-means', [KMeansController::class, 'index'])->name('admin.kmeans.index');
});
