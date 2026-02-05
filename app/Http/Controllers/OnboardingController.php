<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Onboarding;
use App\Models\OnboardingTask;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index(Request $request)
    {
        $query = Onboarding::with(['employee.user', 'employee.department', 'tasks']);

        // Filter: Department
        if ($request->has('department') && $request->department != 'All Departments') {
            $query->whereHas('employee.department', function($q) use ($request) {
                $q->where('department_name', $request->department);
            });
        }

        // Filter: Status (Handle lowercase mapping if filter sends 'In Progress')
        if ($request->has('status') && $request->status != 'All Status') {
            // Map the filter input to DB format if needed, or assume value is passed correctly
            $status = match($request->status) {
                'In Progress' => 'in_progress',
                'Completed' => 'completed',
                'Pending' => 'pending',
                default => $request->status
            };
            $query->where('status', $status);
        }

        // Date Filters
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $onboardings = $query->latest()->get();

        // --- STATS CALCULATION (FIXED) ---
        // Use lowercase values to match database
        $allOnboardings = Onboarding::all();
        
        $stats = [
            'total'       => $allOnboardings->count(),
            'in_progress' => $allOnboardings->where('status', 'in_progress')->count(),
            'completed'   => $allOnboardings->where('status', 'completed')->count(),
            'pending'     => $allOnboardings->where('status', 'pending')->count(),
        ];

        return view('admin.onboarding_admin', compact('onboardings', 'stats'));
    }

    public function showChecklist($id)
    {
        $onboarding = Onboarding::with(['employee.user', 'employee.position', 'employee.department', 'tasks'])
                                ->findOrFail($id);

        $totalTasks = $onboarding->tasks->count();
        $completedTasks = $onboarding->tasks->where('is_completed', true)->count();
        $pendingTasks = $totalTasks - $completedTasks;
        
        $overdueTasks = $onboarding->tasks->where('is_completed', false)
                                          ->where('due_date', '<', now())
                                          ->count();

        return view('admin.onboarding_checklist', compact(
            'onboarding', 'totalTasks', 'completedTasks', 'pendingTasks', 'overdueTasks'
        ));
    }

    public function create()
    {
        $employees = Employee::with(['user', 'department', 'position'])->get();
        return view('admin.onboarding_add', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'startDate'   => 'required|date',
            'deadline'    => 'required|date|after_or_equal:startDate',
        ]);

        // FIXED: Use 'pending' (lowercase)
        $onboarding = Onboarding::create([
            'employee_id' => $request->employee_id,
            'assigned_by' => Auth::id(),
            'start_date'  => $request->startDate,
            'end_date'    => $request->deadline,
            'status'      => 'pending' 
        ]);

        if ($request->has('default_tasks')) {
            foreach ($request->default_tasks as $taskCode) {
                $taskName = match($taskCode) {
                    'documents'   => 'Submit required documents',
                    'orientation' => 'Attend company orientation',
                    'system'      => 'Setup system credentials',
                    'buddy'       => 'Meet assigned buddy / mentor',
                    'policies'    => 'Review and acknowledge HR policies',
                    default       => 'General Task'
                };

                OnboardingTask::create([
                    'onboarding_id' => $onboarding->onboarding_id,
                    'task_name'     => $taskName,
                    'category'      => 'General',
                    'is_completed'  => false,
                    'due_date'      => $request->deadline,
                ]);
            }
        }

        if ($request->filled('customTask')) {
            OnboardingTask::create([
                'onboarding_id' => $onboarding->onboarding_id,
                'task_name'     => 'Custom: ' . substr($request->customTask, 0, 20) . '...',
                'remarks'       => $request->customTask,
                'category'      => 'Custom',
                'is_completed'  => false,
                'due_date'      => $request->deadline,
            ]);
        }

        return redirect()->route('admin.onboarding')
                         ->with('success', 'Onboarding assigned successfully!');
    }
}