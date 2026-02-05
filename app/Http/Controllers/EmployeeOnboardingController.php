<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\OnboardingTask;

class EmployeeOnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Employee record not found.');
        }

        $onboarding = $employee->onboarding()->with('tasks')->first();

        return view('employee.onboarding_view', compact('onboarding'));
    }

    public function completeTask($id)
    {
        $task = OnboardingTask::with('onboarding')->findOrFail($id);

        // 1. Mark the specific task as completed
        $task->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // 2. CHECK: Are there any remaining incomplete tasks?
        $parentOnboarding = $task->onboarding;
        
        $remainingTasks = $parentOnboarding->tasks()->where('is_completed', false)->count();

        if ($remainingTasks === 0) {
            // FIXED: Use 'completed' (lowercase) to match database enum
            $parentOnboarding->update(['status' => 'completed']);
            $message = 'Task completed. Congratulations! You have finished your onboarding.';
        } else {
            // FIXED: Use 'in_progress' (lowercase, underscore) to match database enum
            $parentOnboarding->update(['status' => 'in_progress']);
            $message = 'Task marked as completed!';
        }

        return redirect()->back()->with('success', $message);
    }
}