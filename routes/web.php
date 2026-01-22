<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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
    Route::get('/dashboard', function () {
        return view('admin.dashboard_dashboard');
    })->name('admin.dashboard');

    // Announcements
    Route::get('/dashboard/announcement/view', function () {
        return view('admin.dashboard_view');
    })->name('admin.dashboard.announcement.view');

    Route::get('/dashboard/announcement/add', function () {
        return view('admin.dashboard_add');
    })->name('admin.dashboard.announcement.add');

    Route::get('/dashboard/announcement/detail', function () {
        return view('admin.dashboard_detail');
    })->name('admin.dashboard.announcement.detail');

    // ⭐ AI Assistant (fixed)
    Route::get('/assistant', function () {
        return view('admin.assistant');   // Path: resources/views/admin/assistant.blade.php
    })->name('admin.assistant');


    // Profile
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');


    /*
    |--------------------------------------------------------------------------
    | Recruitment
    |--------------------------------------------------------------------------
    */
    Route::get('/recruitment', function () {
        return view('admin.recruitment_admin');
    })->name('admin.recruitment');

    Route::get('/recruitment/add', function () {
        return view('admin.recruitment_add');
    })->name('admin.recruitment.add');

    Route::get('/recruitment/applicants', function () {
        return view('admin.recruitment_applicants');
    })->name('admin.recruitment.applicants');


    /*
    |--------------------------------------------------------------------------
    | Appraisal
    |--------------------------------------------------------------------------
    */
    Route::get('/appraisal', function () {
        return view('admin.appraisal_admin');
    })->name('admin.appraisal');

    Route::get('/appraisal/add-kpi', function () {
        return view('admin.appraisal_add_kpi');
    })->name('admin.appraisal.add-kpi');

    Route::get('/appraisal/reviews', function () {
        return view('admin.appraisal_reviews');
    })->name('admin.appraisal.reviews');

    Route::get('/appraisal/employee-kpis', function () {
    return view('admin.appraisal_kpi_employee');
})->name('admin.appraisal.employee-kpis');

    Route::get('/appraisal/employee-kpi-list', function () {
        return view('admin.appraisal_kpi_employee_list');
    })->name('admin.appraisal.employee-kpi-list');

    Route::view(
  '/admin/appraisal/department-kpi',
  'admin.appraisal_department_kpi'
)->name('admin.appraisal.department-kpi');



    /*
    |--------------------------------------------------------------------------
    | Training
    |--------------------------------------------------------------------------
    */
    Route::get('/training', function () {
        return view('admin.training_admin');
    })->name('admin.training');

    Route::get('/training/add', function () {
        return view('admin.training_add');
    })->name('admin.training.add');

    Route::get('/reports', function () {
        return view('admin.reports');   // <-- your tabs report page
    })->name('admin.reports.dashboard');

    Route::get('/training/show', function () {
    return view('admin.training_show'); // resources/views/admin/training_show.blade.php
})->name('admin.training.show');
    /*
    |--------------------------------------------------------------------------
    | Onboarding
    |--------------------------------------------------------------------------
    */
    Route::get('/onboarding', function () {
        return view('admin.onboarding_admin');
    })->name('admin.onboarding');

    Route::get('/onboarding/add', function () {
        return view('admin.onboarding_add');
    })->name('admin.onboarding.add');

    Route::get('/onboarding/checklist', function () {
    return view('admin.onboarding_checklist');
})->name('admin.onboarding.checklist');


   Route::get('/admin/recruitment/applicant-details', function () {
    // UI-only for now, later you can pass real data
    return view('admin.applicants_show');
})->name('admin.applicants.show');

});

Route::prefix('applicant')->middleware('auth')->group(function () {

    Route::get('/jobs', function () {
        return view('applicant.jobs');
    })->name('applicant.jobs');

    Route::get('/applications', function () {
        return view('applicant.applications');
    })->name('applicant.applications');

    Route::get('job-details', function () {
    return view('applicant.job_details');
})->name('job.details');

Route::get('apply', function () {
        return view('applicant.job_apply');
    })->name('applicant.apply');

    Route::get('applications', function () {
    return view('applicant.applications');
})->name('applicant.applications');

Route::get('profile', function () {
    return view('applicant.profile');
})->name('applicant.profile');




    // ----------------------------
    // Employee Management Module
    // ----------------------------
    Route::get('/employee/list', function () {
        return view('admin.employee_list');
    })->name('admin.employee.list');

    Route::get('/employee/add', function () {
        return view('admin.employee_add');
    })->name('admin.employee.add');

    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');


    // ----------------------------
    // Attendance Management Module
    // ----------------------------
    Route::get('/attendance/tracking', function () {
        return view('admin.attendance_tracking');
    })->name('admin.attendance.tracking');

    Route::get('/attendance/penalty', function () {
        return view('admin.attendance_penalty');
    })->name('admin.attendance.penalty');

    // ----------------------------
    // Payroll Management Module
    // ----------------------------
    Route::prefix('/payroll')->group(function () {
        Route::get('/overtime', function () {
            return view('admin.payroll_overtime');
        })->name('admin.payroll.overtime');

        Route::get('/salary', function () {
            return view('admin.payroll_salary');
        })->name('admin.payroll.salary');

        Route::get('/attendance', function () {
            return view('admin.payroll_attendance');
        })->name('admin.payroll.attendance');

        Route::get('/payslip', function () {
            return view('admin.payroll_payslip');
        })->name('admin.payroll.payslip');
    });


    // ----------------------------
    // Leave Management Module
    // ----------------------------
    Route::prefix('/leave')->group(function () {
        Route::get('/request', function () {
            return view('admin.leave_request');
        })->name('admin.leave.request');

        Route::get('/balance', function () {
            return view('admin.leave_balance');
        })->name('admin.leave.balance');
    });

    // ----------------------------
    // Assistant & Reports
    // ----------------------------
    Route::get('/assistant', function () {
        return view('admin.assistant');
    })->name('admin.assistant');

    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');

    // Dashboard alt view
    Route::get('/dashboard/view', function () {
        return view('admin.dashboard_view');
    })->name('admin.dashboard.view');

});
