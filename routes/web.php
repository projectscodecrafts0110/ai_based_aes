<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KMeansController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\JobVacancyController;
use App\Http\Controllers\AlliedCourseController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\OTPVerified;

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

Route::middleware(['auth', OTPVerified::class])->prefix('user')->group(function () {

    // Dashboard Page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // OTP
    Route::get('/otp/verify', function () {
        return view('otp');
    })->name('otp.verify');

    // Resend OTP
    Route::post('/otp/resend', [App\Http\Controllers\AuthController::class, 'resendOtp'])->name('otp.resend');

    // Verify OTP
    Route::post('/otp/verify', [App\Http\Controllers\AuthController::class, 'verifyOtp'])->name('otp.verify.post');

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
    Route::get('/apply/form/{job}', [ApplicationController::class, 'showForm'])
        ->name('apply.form');
    Route::post('/apply/form', [ApplicationController::class, 'create'])->name('apply');
    Route::post('/apply/store', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/status', [ApplicationController::class, 'status'])->name('applications.status');
    Route::get('/applications/status/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/status/{id}/download', [ApplicationController::class, 'download'])->name('applications.download');
});

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    // Admin Routes
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/applications', [AdminController::class, 'manageApplications'])->name('admin.applications');
    Route::delete('/applications/{application}', [AdminController::class, 'deleteApplication'])->name('admin.applications.destroy');
    Route::get('/ai-evaluations', [AdminController::class, 'aiEvaluations'])->name('admin.ai-evaluations');
    Route::post('/applications/{application}/status', [AdminController::class, 'updateApplicationStatus'])
        ->name('admin.update_application_status');

    Route::get('/job-vacancies', [JobVacancyController::class, 'index'])->name('admin.job_vacancies.index');
    Route::get('/job-vacancies/create', [JobVacancyController::class, 'create'])->name('admin.job_vacancies.create');
    Route::post('/job-vacancies', [JobVacancyController::class, 'store'])->name('admin.job_vacancies.store');
    Route::get('/job-vacancies/{jobVacancy}/edit', [JobVacancyController::class, 'edit'])->name('admin.job_vacancies.edit');
    Route::put('/job-vacancies/{jobVacancy}', [JobVacancyController::class, 'update'])->name('admin.job_vacancies.update');
    Route::delete('/job-vacancies/{jobVacancy}', [JobVacancyController::class, 'destroy'])->name('admin.job_vacancies.destroy');
    Route::put('/job_vacancies/{id}/toggle-status', [JobVacancyController::class, 'toggleStatus'])
        ->name('admin.job_vacancies.toggle_status');


    Route::get('/job_vacancies/courses', [AlliedCourseController::class, 'index'])->name('admin.courses');
    Route::get('/job_vacancies/courses/create', [AlliedCourseController::class, 'create'])->name('admin.courses.create');
    Route::post('/job_vacancies/courses/store', [AlliedCourseController::class, 'store'])->name('admin.courses.store');
    Route::get('/job_vacancies/courses/update/{course}', [AlliedCourseController::class, 'editPage'])->name('admin.courses.show.update');
    Route::put('/job_vacancies/courses/{course}', [AlliedCourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('/job_vacancies/courses/{course}/delete', [AlliedCourseController::class, 'destroy'])->name('admin.courses.destroy');

    Route::get('/rankings', [KMeansController::class, 'index'])->name('admin.kmeans.index');
});


// Logout Route
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
    ->name('logout');
