<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Training;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalWorkers'  => User::where('role', 'worker')->count(),
            'totalAdmins'   => User::where('role', 'admin')->count(),
            'totalProjects' => Project::count(),
            'totalTraining' => Training::count(),
        ]);
    }
}
