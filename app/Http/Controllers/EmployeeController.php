<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        // This looks for the file resources/views/employee/dashboard.blade.php
        return view('employee.dashboard');
    }
}
