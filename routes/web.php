<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicantJobController; 

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Default page → redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// LOGIN
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// REGISTER
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// FORGOT PASSWORD (UI only)
Route::get('/forgot-password', function () {
    return view('auth.forgot');
})->name('forgot');

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Announcements
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard/announcement/view', [AnnouncementController::class, 'index'])
         ->name('admin.announcements.index');

    Route::get('/dashboard/announcement/add', [AnnouncementController::class, 'create'])
         ->name('admin.announcements.create');

    Route::post('/dashboard/announcement/store', [AnnouncementController::class, 'store'])
         ->name('admin.announcements.store');

    Route::get('/dashboard/announcement/detail/{id}', [AnnouncementController::class, 'show'])
         ->name('admin.announcements.show');

    /*
    |--------------------------------------------------------------------------
    | Recruitment Module (Admin Side)
    |--------------------------------------------------------------------------
    */
    // Job Posts
    Route::get('/recruitment', [JobPostController::class, 'index'])
         ->name('admin.recruitment.index');

    Route::get('/recruitment/create', [JobPostController::class, 'create'])
         ->name('admin.recruitment.create');

    Route::post('/recruitment/store', [JobPostController::class, 'store'])
         ->name('admin.recruitment.store');

         Route::post('/recruitment/update/{id}', [JobPostController::class, 'update'])
         ->name('admin.recruitment.update');

    // Applicants
    Route::get('/recruitment/applicants', [ApplicationController::class, 'index'])
         ->name('admin.applicants.index');

    Route::get('/recruitment/applicants/{id}', [ApplicationController::class, 'show'])
         ->name('admin.applicants.show');
         
    Route::post('/recruitment/applicants/{id}/evaluate', [ApplicationController::class, 'saveEvaluation'])
         ->name('admin.applicants.evaluate');

    Route::post('/recruitment/applicants/{id}/status', [ApplicationController::class, 'updateStatus'])
         ->name('admin.applicants.updateStatus');

         // Edit Job Form
    Route::get('/recruitment/edit/{id}', [JobPostController::class, 'edit'])
         ->name('admin.recruitment.edit');
         
    // Update Job Data
    Route::post('/recruitment/update/{id}', [JobPostController::class, 'update'])
         ->name('admin.recruitment.update');

    // Delete Job
    Route::delete('/recruitment/delete/{id}', [JobPostController::class, 'destroy'])
         ->name('admin.recruitment.destroy');

    /*
    |--------------------------------------------------------------------------
    | Other Modules (Appraisal, Training, Etc.)
    |--------------------------------------------------------------------------
    */
    
    // Appraisal
    Route::get('/appraisal', function () { return view('admin.appraisal_admin'); })->name('admin.appraisal');
    Route::get('/appraisal/add-kpi', function () { return view('admin.appraisal_add_kpi'); })->name('admin.appraisal.add-kpi');
    Route::get('/appraisal/reviews', function () { return view('admin.appraisal_reviews'); })->name('admin.appraisal.reviews');
    Route::get('/appraisal/employee-kpis', function () { return view('admin.appraisal_kpi_employee'); })->name('admin.appraisal.employee-kpis');
    Route::get('/appraisal/employee-kpi-list', function () { return view('admin.appraisal_kpi_employee_list'); })->name('admin.appraisal.employee-kpi-list');
    Route::view('/admin/appraisal/department-kpi', 'admin.appraisal_department_kpi')->name('admin.appraisal.department-kpi');

    // Training
    Route::get('/training', function () { return view('admin.training_admin'); })->name('admin.training');
    Route::get('/training/add', function () { return view('admin.training_add'); })->name('admin.training.add');
    Route::get('/training/show', function () { return view('admin.training_show'); })->name('admin.training.show');

    // Onboarding
    Route::get('/onboarding', function () { return view('admin.onboarding_admin'); })->name('admin.onboarding');
    Route::get('/onboarding/add', function () { return view('admin.onboarding_add'); })->name('admin.onboarding.add');
    Route::get('/onboarding/checklist', function () { return view('admin.onboarding_checklist'); })->name('admin.onboarding.checklist');

    // Assistant & Reports
    Route::get('/assistant', function () { return view('admin.assistant'); })->name('admin.assistant');
    Route::get('/reports', function () { return view('admin.reports'); })->name('admin.reports.dashboard');

    // Profile
    Route::get('/profile', function () { return view('admin.profile'); })->name('admin.profile');
    
    // Employee Management
    Route::get('/employee/list', function () { return view('admin.employee_list'); })->name('admin.employee.list');
    Route::get('/employee/add', function () { return view('admin.employee_add'); })->name('admin.employee.add');

    // Attendance
    Route::get('/attendance/tracking', function () { return view('admin.attendance_tracking'); })->name('admin.attendance.tracking');
    Route::get('/attendance/penalty', function () { return view('admin.attendance_penalty'); })->name('admin.attendance.penalty');

    // Payroll
    Route::prefix('/payroll')->group(function () {
        Route::get('/overtime', function () { return view('admin.payroll_overtime'); })->name('admin.payroll.overtime');
        Route::get('/salary', function () { return view('admin.payroll_salary'); })->name('admin.payroll.salary');
        Route::get('/attendance', function () { return view('admin.payroll_attendance'); })->name('admin.payroll.attendance');
        Route::get('/payslip', function () { return view('admin.payroll_payslip'); })->name('admin.payroll.payslip');
    });

    // Leave
    Route::prefix('/leave')->group(function () {
        Route::get('/request', function () { return view('admin.leave_request'); })->name('admin.leave.request');
        Route::get('/balance', function () { return view('admin.leave_balance'); })->name('admin.leave.balance');
    });

});


/*
|--------------------------------------------------------------------------
| APPLICANT ROUTES (User Side)
|--------------------------------------------------------------------------
*/
// 2. THIS IS THE CORRECT BLOCK using the Controller
Route::prefix('applicant')->middleware('auth')->group(function () {

    // Job List (Uses Controller to get $jobs)
    Route::get('/jobs', [ApplicantJobController::class, 'index'])
         ->name('applicant.jobs');

    // Job Details (Uses Controller to get $job)
    Route::get('/jobs/{id}', [ApplicantJobController::class, 'show'])
         ->name('applicant.jobs.show');

    // Apply Form (Uses Controller to get $job)
    Route::get('/jobs/{id}/apply', [ApplicantJobController::class, 'applyForm'])
         ->name('applicant.jobs.apply');

    // Submit Application (POST)
    Route::post('/jobs/{id}/apply', [ApplicantJobController::class, 'submitApplication'])
         ->name('applicant.jobs.submit');

    // My Applications 
    Route::get('/applications', [ApplicantJobController::class, 'myApplications'])
         ->name('applicant.applications');

    // Profile
    Route::get('/profile', [ApplicantJobController::class, 'profile'])
         ->name('applicant.profile');

    // Profile (Update)
    Route::post('/profile/update', [ApplicantJobController::class, 'updateProfile'])
         ->name('applicant.profile.update');

         Route::get('/profile/resume/delete', [ApplicantJobController::class, 'deleteResume'])
         ->name('applicant.resume.delete');

});


/*
|--------------------------------------------------------------------------
| EMPLOYEE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'index'])
         ->name('employee.dashboard');
});