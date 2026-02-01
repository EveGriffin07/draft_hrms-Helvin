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
use App\Http\Controllers\AdminEmployeeController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminPenaltyController;
use App\Http\Controllers\AdminOvertimeController;
use App\Http\Controllers\AdminSalaryController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\AdminLeaveBalanceController;


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

    // Announcements
    Route::get('/dashboard/announcement/view', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::get('/dashboard/announcement/add', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/dashboard/announcement/store', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/dashboard/announcement/detail/{id}', [AnnouncementController::class, 'show'])->name('admin.announcements.show');

    // Recruitment
    Route::get('/recruitment', [JobPostController::class, 'index'])->name('admin.recruitment.index');
    Route::get('/recruitment/create', [JobPostController::class, 'create'])->name('admin.recruitment.create');
    Route::post('/recruitment/store', [JobPostController::class, 'store'])->name('admin.recruitment.store');
    Route::get('/recruitment/edit/{id}', [JobPostController::class, 'edit'])->name('admin.recruitment.edit');
    Route::post('/recruitment/update/{id}', [JobPostController::class, 'update'])->name('admin.recruitment.update');
    Route::delete('/recruitment/delete/{id}', [JobPostController::class, 'destroy'])->name('admin.recruitment.destroy');

    Route::get('/recruitment/applicants', [ApplicationController::class, 'index'])->name('admin.applicants.index');
    Route::get('/recruitment/applicants/{id}', [ApplicationController::class, 'show'])->name('admin.applicants.show');
    Route::post('/recruitment/applicants/{id}/evaluate', [ApplicationController::class, 'saveEvaluation'])->name('admin.applicants.evaluate');
    Route::post('/recruitment/applicants/{id}/status', [ApplicationController::class, 'updateStatus'])->name('admin.applicants.updateStatus');
    Route::post('/recruitment/applicants/{id}/onboard', [ApplicationController::class, 'onboard'])->name('admin.applicants.onboard');

    // Appraisal (static views)
    Route::get('/appraisal', fn() => view('admin.appraisal_admin'))->name('admin.appraisal');
    Route::get('/appraisal/add-kpi', fn() => view('admin.appraisal_add_kpi'))->name('admin.appraisal.add-kpi');
    Route::get('/appraisal/reviews', fn() => view('admin.appraisal_reviews'))->name('admin.appraisal.reviews');
    Route::get('/appraisal/employee-kpis', fn() => view('admin.appraisal_kpi_employee'))->name('admin.appraisal.employee-kpis');
    Route::get('/appraisal/employee-kpi-list', fn() => view('admin.appraisal_kpi_employee_list'))->name('admin.appraisal.employee-kpi-list');
    Route::view('/appraisal/department-kpi', 'admin.appraisal_department_kpi')->name('admin.appraisal.department-kpi');

    // Training
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

    // Onboarding
    Route::get('/onboarding', fn() => view('admin.onboarding_admin'))->name('admin.onboarding');
    Route::get('/onboarding/add', fn() => view('admin.onboarding_add'))->name('admin.onboarding.add');
    Route::get('/onboarding/checklist', fn() => view('admin.onboarding_checklist'))->name('admin.onboarding.checklist');

    // Assistant & Reports
    Route::get('/assistant', fn() => view('admin.assistant'))->name('admin.assistant');
    Route::get('/reports', fn() => view('admin.reports'))->name('admin.reports.dashboard');

    // Profile
    Route::get('/profile', fn() => view('admin.profile'))->name('admin.profile');

    // Employee Management
    Route::get('/employee/list', [AdminEmployeeController::class, 'index'])->name('admin.employee.list');
    Route::get('/employee/add', [AdminEmployeeController::class, 'create'])->name('admin.employee.add');
    Route::post('/employee/add', [AdminEmployeeController::class, 'store'])->name('admin.employee.store');

    // Attendance
    Route::get('/attendance/tracking', [AdminAttendanceController::class, 'tracking'])->name('admin.attendance.tracking');
    Route::get('/attendance/tracking/data', [AdminAttendanceController::class, 'data'])->name('admin.attendance.data');
    Route::get('/attendance/penalty', [AdminPenaltyController::class, 'index'])->name('admin.attendance.penalty');
    Route::get('/attendance/penalty/data', [AdminPenaltyController::class, 'data'])->name('admin.attendance.penalty.data');
    Route::post('/attendance/penalty/{penalty}/status', [AdminPenaltyController::class, 'updateStatus'])->name('admin.attendance.penalty.status');

    // Payroll
    Route::prefix('/payroll')->group(function () {
        Route::get('/overtime', [AdminOvertimeController::class, 'index'])->name('admin.payroll.overtime');
        Route::get('/overtime/data', [AdminOvertimeController::class, 'data'])->name('admin.payroll.overtime.data');
        Route::post('/overtime/{overtime}/status', [AdminOvertimeController::class, 'updateStatus'])->name('admin.payroll.overtime.status');
        Route::get('/salary', [AdminSalaryController::class, 'index'])->name('admin.payroll.salary');
        Route::get('/salary/data', [AdminSalaryController::class, 'data'])->name('admin.payroll.salary.data');
        Route::get('/attendance', fn() => view('admin.payroll_attendance'))->name('admin.payroll.attendance');
        Route::get('/payslip', fn() => view('admin.payroll_payslip'))->name('admin.payroll.payslip');
    });

    // Leave
    Route::prefix('/leave')->group(function () {
        Route::get('/request', [AdminLeaveController::class, 'index'])->name('admin.leave.request');
        Route::get('/request/data', [AdminLeaveController::class, 'data'])->name('admin.leave.request.data');
        Route::post('/request/{leave}/status', [AdminLeaveController::class, 'updateStatus'])->name('admin.leave.request.status');
        Route::get('/balance', [AdminLeaveBalanceController::class, 'index'])->name('admin.leave.balance');
        Route::get('/balance/data', [AdminLeaveBalanceController::class, 'data'])->name('admin.leave.balance.data');
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
