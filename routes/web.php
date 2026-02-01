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
use App\Http\Controllers\TrainingController; 
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\EmployeeTrainingController;
use App\Http\Controllers\KpiController;

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

    Route::post('/recruitment/applicants/{id}/onboard', [ApplicationController::class, 'onboard'])
         ->name('admin.applicants.onboard');

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
    Route::get('/appraisal', [KpiController::class, 'index'])->name('admin.appraisal');

    // 2. Add KPI
    Route::get('/appraisal/add-kpi', [KpiController::class, 'create'])->name('admin.appraisal.add-kpi');
    Route::post('/appraisal/store-kpi', [KpiController::class, 'store'])->name('admin.appraisal.store');

    // 3. Employee List (Select Employee)
    Route::get('/appraisal/employee-list', [KpiController::class, 'employeeList'])->name('admin.appraisal.employee-kpi-list');

    // 4. View Specific Employee KPIs (The Detail Page)
    Route::get('/appraisal/employee-kpis', [KpiController::class, 'showEmployeeKpis'])->name('admin.appraisal.employee-kpis');

    Route::get('/appraisal/department-kpi/{id}', [KpiController::class, 'showDepartmentKpi'])
     ->name('admin.appraisal.department-kpi');
    // 5. Update KPI (Review Modal)
    Route::post('/appraisal/update-score/{id}', [KpiController::class, 'updateScore'])->name('admin.appraisal.update-score');
Route::get('/employee/my-kpis', [KpiController::class, 'myKpis'])->name('employee.kpis');

// Self Evaluation Page
    Route::get('/employee/appraisal/reviews', [KpiController::class, 'selfEvaluationList'])
         ->name('employee.kpis.self-eval');
    
    // Submit Evaluation Logic
    Route::post('/employee/appraisal/reviews/submit/{id}', [KpiController::class, 'submitSelfEval'])
         ->name('employee.kpis.store-eval');
    // Training (Admin Side)
    Route::get('/training', [TrainingController::class, 'index'])->name('admin.training');
    Route::get('/training/add', [TrainingController::class, 'create'])->name('admin.training.add');
    Route::post('/training/store', [TrainingController::class, 'store'])->name('admin.training.store');
    Route::get('/training/show/{id}', [TrainingController::class, 'show'])->name('admin.training.show');
    Route::get('/training/events', [TrainingController::class, 'getEvents'])->name('admin.training.events');
    Route::post('/training/{id}/enroll', [TrainingController::class, 'storeEnrollment'])->name('admin.training.enroll');
    Route::post('/training/enrollment/{id}/update', [TrainingController::class, 'updateEnrollmentStatus'])->name('admin.training.updateStatus');
    Route::get('/training/edit/{id}', [TrainingController::class, 'edit'])->name('admin.training.edit');
    Route::post('/training/update/{id}', [TrainingController::class, 'update'])->name('admin.training.update');
    Route::delete('/training/delete/{id}', [TrainingController::class, 'destroy'])->name('admin.training.delete');

    /*
    |--------------------------------------------------------------------------
    | Onboarding (CORRECTED)
    |--------------------------------------------------------------------------
    */
    // 1. Dashboard (Lists all onboardings + Stats)
    Route::get('/onboarding', [OnboardingController::class, 'index'])
         ->name('admin.onboarding');

    // 2. Checklist Details
    Route::get('/onboarding/checklist/{id}', [OnboardingController::class, 'showChecklist'])
         ->name('admin.onboarding.checklist.show');
    
    // 3. Add New (Static View)
    Route::get('/onboarding/add', [OnboardingController::class, 'create'])
         ->name('admin.onboarding.add');

     Route::get('/onboarding/add', [OnboardingController::class, 'create'])
     ->name('admin.onboarding.add');

     // 4. Store New (Form Submission) - NEW LINE
     Route::post('/onboarding/store', [OnboardingController::class, 'store'])
     ->name('admin.onboarding.store');
     
     Route::get('/employee/onboarding', [App\Http\Controllers\EmployeeOnboardingController::class, 'index'])
         ->name('employee.onboarding.index');

    Route::post('/employee/onboarding/task/{id}/complete', [App\Http\Controllers\EmployeeOnboardingController::class, 'completeTask'])
         ->name('employee.onboarding.complete');

    // Assistant & Reports
    Route::get('/assistant', function () { return view('admin.assistant'); })->name('admin.assistant');
    Route::get('/reports', function () { return view('admin.reports'); })->name('admin.reports.dashboard');

    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])
         ->name('admin.profile');

    // Profile (Update)
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])
         ->name('admin.profile.update');
    
    
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
Route::prefix('applicant')->middleware('auth')->group(function () {

    Route::get('/jobs', [ApplicantJobController::class, 'index'])->name('applicant.jobs');
    Route::get('/jobs/{id}', [ApplicantJobController::class, 'show'])->name('applicant.jobs.show');
    Route::get('/jobs/{id}/apply', [ApplicantJobController::class, 'applyForm'])->name('applicant.jobs.apply');
    Route::post('/jobs/{id}/apply', [ApplicantJobController::class, 'submitApplication'])->name('applicant.jobs.submit');
    Route::get('/applications', [ApplicantJobController::class, 'myApplications'])->name('applicant.applications');
    Route::get('/profile', [ApplicantJobController::class, 'profile'])->name('applicant.profile');
    Route::post('/profile/update', [ApplicantJobController::class, 'updateProfile'])->name('applicant.profile.update');
    Route::get('/profile/resume/delete', [ApplicantJobController::class, 'deleteResume'])->name('applicant.resume.delete');

});


/*
|--------------------------------------------------------------------------
| EMPLOYEE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/employee/dashboard', [EmployeeController::class, 'index'])
         ->name('employee.dashboard');

    // Training Plans
    Route::get('/employee/training/my-plans', [EmployeeTrainingController::class, 'index'])
         ->name('employee.training.index');

    // Training Details
    Route::get('/employee/training/{id}', [EmployeeTrainingController::class, 'show'])
         ->name('employee.training.show');
});