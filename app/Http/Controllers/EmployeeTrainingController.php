<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\TrainingEnrollment;
use Carbon\Carbon;

class EmployeeTrainingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Find the Employee record linked to this User
        $employee = Employee::where('user_id', $user->user_id)->first();

        // Safety check: If user is not in employee table yet
        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Employee record not found.');
        }

        // 2. Fetch Enrollments with Training Program details
        // We use the 'training' relationship we fixed earlier
        $enrollments = TrainingEnrollment::where('employee_id', $employee->employee_id)
                        ->with('training') 
                        ->get();

        // 3. Separate into "Upcoming/Active" and "History"
        $upcoming = $enrollments->filter(function ($enrollment) {
            return $enrollment->completion_status === 'enrolled';
        });

        $history = $enrollments->filter(function ($enrollment) {
            return in_array($enrollment->completion_status, ['completed', 'failed']);
        });

        return view('employee.training_my_plans', compact('upcoming', 'history'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->user_id)->first();

        // Fetch the specific enrollment, ensuring it belongs to this employee
        $enrollment = TrainingEnrollment::where('training_id', $id)
                        ->where('employee_id', $employee->employee_id)
                        ->with('training')
                        ->firstOrFail();

        return view('employee.training_show', compact('enrollment'));
    }
}