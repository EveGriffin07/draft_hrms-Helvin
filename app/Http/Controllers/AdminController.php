<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\JobPost;
use App\Models\TrainingProgram;
use App\Models\EmployeeKpi;
use App\Models\Department;
use App\Models\Application;
use App\Models\TrainingEnrollment;
use App\Models\Announcement;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Needed for file deletion

class AdminController extends Controller
{
    /**
     * Admin Dashboard Index
     */
    public function index()
    {
        // --- 1. Top Metrics Cards ---
        $totalEmployees = Employee::where('employee_status', 'active')->count();
        
        $newEmployeesThisMonth = Employee::whereMonth('hire_date', Carbon::now()->month)
                                         ->whereYear('hire_date', Carbon::now()->year)
                                         ->count();

        $activeJobPosts = JobPost::where('job_status', 'open')->count();
        $ongoingTraining = TrainingProgram::whereIn('tr_status', ['active', 'ongoing'])->count();
        $pendingReviews = EmployeeKpi::whereIn('kpi_status', ['pending', 'in_progress'])->count();

        // --- 2. Module Overview Data ---
        $newApplicants = Application::where('created_at', '>=', Carbon::now()->startOfWeek())->count();
        $interviewsScheduled = Application::where('app_stage', 'Interview')->count();

        $completedReviews = EmployeeKpi::where('kpi_status', 'completed')->count();
        $avgKpiScore = EmployeeKpi::where('kpi_status', 'completed')->avg('actual_score') ?? 0;

        $completedTrainings = TrainingProgram::where('tr_status', 'completed')->count();
        $totalParticipants  = TrainingEnrollment::count();

        // --- 3. Charts Data ---
        $employeeGrowth = Employee::selectRaw('DATE_FORMAT(hire_date, "%M") as month, COUNT(*) as count')
            ->where('hire_date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('hire_date')
            ->get();
        
        $growthLabels = $employeeGrowth->pluck('month');
        $growthData   = $employeeGrowth->pluck('count');

        $deptDist = Department::withCount('employees')->get();
        $deptLabels = $deptDist->pluck('department_name');
        $deptData   = $deptDist->pluck('employees_count');

        // --- 4. Sidebar Data ---
        $upcomingInterviews = Application::with(['applicant', 'job'])
            ->where('app_stage', 'Interview')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        $announcements = Announcement::latest('publish_at')->take(5)->get();

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

    /**
     * Show Profile Page
     */
    public function profile()
    {
        $user = Auth::user();
        
        // Calculate Stats for the Sidebar
        $stats = [
            'announcements' => Announcement::count(), 
            'employees'     => Employee::count(),
            'users'         => User::count(), 
        ];

        return view('admin.profile', compact('user', 'stats'));
    }

    /**
     * Update Profile (Including Avatar)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validation
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone'    => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB image
            'password' => 'nullable|min:6|confirmed',
        ]);

        // 2. Update Basic User Info
        $user->name = $request->name;
        $user->email = $request->email;

        // 3. Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Optional: Delete old avatar if it exists and isn't a default one
            if ($user->avatar_path && Storage::exists('public/' . $user->avatar_path)) {
                Storage::delete('public/' . $user->avatar_path);
            }

            // Save new file to 'storage/app/public/avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        // 4. Update Password (if provided)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 5. Update Employee Details (Phone)
        // This ensures the phone number is saved to the 'employees' table
        $employee = Employee::where('user_id', $user->user_id)->first();
        
        if ($employee) {
            $employee->phone = $request->phone;
            $employee->save();
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}