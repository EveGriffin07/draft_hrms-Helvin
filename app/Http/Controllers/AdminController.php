<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
// Importing all necessary Models
use App\Models\Employee;
use App\Models\JobPost;
use App\Models\TrainingProgram;
use App\Models\EmployeeKpi;
use App\Models\Department;
use App\Models\Application;
use App\Models\TrainingEnrollment;
use App\Models\Announcement;

class AdminController extends Controller
{
    public function index()
    {
        // --- 1. Top Metrics Cards ---
        $totalEmployees = Employee::where('employee_status', 'active')->count();
        
        $newEmployeesThisMonth = Employee::whereMonth('hire_date', Carbon::now()->month)
                                        ->whereYear('hire_date', Carbon::now()->year)
                                        ->count();

        $activeJobPosts = JobPost::where('job_status', 'open')->count();
        
        // Count active OR ongoing training programs
        $ongoingTraining = TrainingProgram::whereIn('tr_status', ['active', 'ongoing'])->count();
        
        // Count pending OR in-progress reviews
        $pendingReviews = EmployeeKpi::whereIn('kpi_status', ['pending', 'in_progress'])->count();

        // --- 2. Module Overview Data ---
        $newApplicants = Application::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $interviewsScheduled = Application::where('app_stage', 'Interview')->count();

        $completedReviews = EmployeeKpi::where('kpi_status', 'completed')->count();
        $avgKpiScore = EmployeeKpi::where('kpi_status', 'completed')->avg('actual_score') ?? 0;

        $completedTrainings = TrainingProgram::where('tr_status', 'completed')->count();
        $totalParticipants  = TrainingEnrollment::count();

        // --- 3. Charts Data ---
        // Group new hires by Month (for the Line Chart)
        $employeeGrowth = Employee::selectRaw('DATE_FORMAT(hire_date, "%M") as month, COUNT(*) as count')
            ->where('hire_date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('hire_date')
            ->get();
        
        $growthLabels = $employeeGrowth->pluck('month');
        $growthData   = $employeeGrowth->pluck('count');

        // Group employees by Department (for the Donut Chart)
        $deptDist = Department::withCount('employees')->get();
        $deptLabels = $deptDist->pluck('department_name');
        $deptData   = $deptDist->pluck('employees_count');

        // --- 4. Sidebar Data ---
        $upcomingInterviews = Application::with(['applicant', 'job']) // Eager load relationships
            ->where('app_stage', 'Interview')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        // ⭐ UPDATED: Use 'publish_at' for sorting based on your new Schema
        $announcements = Announcement::latest('publish_at')->take(5)->get();

        // Returning your specific dashboard view
        return view('admin.dashboard_dashboard', compact(
            'totalEmployees', 'newEmployeesThisMonth',
            'activeJobPosts', 'ongoingTraining', 'pendingReviews',
            'growthLabels', 'growthData',
            'deptLabels', 'deptData',
            'newApplicants', 'interviewsScheduled',
            'completedReviews', 'avgKpiScore',
            'completedTrainings', 'totalParticipants',
            'upcomingInterviews', 'announcements'
        ));
    }
}