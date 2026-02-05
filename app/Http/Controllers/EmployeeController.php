<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\TrainingEnrollment;
use App\Models\Payslip;

class EmployeeController extends Controller
{
    public function index()
    {
        // 1. Get the Logged-in Employee
        $user = Auth::user();
        $employee = $user->employee; // Uses the relationship we just added

        // Safety Check: If login is 'admin' or 'applicant', they won't have an employee profile
        if (!$employee) {
            abort(403, 'User does not have an Employee Profile.');
        }

        // 2. Fetch Attendance for Today
        $todayAttendance = Attendance::where('employee_id', $employee->employee_id)
                            ->whereDate('date', Carbon::today())
                            ->first();

        // 3. Calculate Leave Balance (Simplified Logic)
        // Assuming every employee gets 14 days Annual Leave. 
        // In the future, you can fetch this from $employee->position->leave_entitlement
        $totalEntitlement = 14; 
        $leaveUsed = LeaveRequest::where('employee_id', $employee->employee_id)
                        ->where('leave_status', 'approved')
                        ->sum('total_days');
        $leaveBalance = $totalEntitlement - $leaveUsed;

        // 4. Count Upcoming Trainings
        // We use whereHas to filter trainings that start in the future
        $upcomingTrainings = TrainingEnrollment::where('employee_id', $employee->employee_id)
                            ->whereHas('training', function($query) {
                                $query->where('start_date', '>', now());
                            })->count();

        // 5. Get Latest Payslip
        $latestPayslip = Payslip::where('employee_id', $employee->employee_id)
                            ->orderBy('generated_at', 'desc')
                            ->first();

        // 6. Pass everything to the View
        return view('employee.dashboard', compact(
            'employee', 
            'todayAttendance', 
            'leaveBalance', 
            'upcomingTrainings', 
            'latestPayslip'
        ));
    }
}