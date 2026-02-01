<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\JobPost;
use App\Models\ApplicantProfile;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\Onboarding; // New Import
use App\Models\OnboardingTask; // New Import

class ApplicationController extends Controller
{
    // 1. List all Applicants
    public function index()
    {
        $applications = Application::with(['job', 'applicant'])->latest()->get();
        return view('admin.recruitment_applicants', compact('applications'));
    }

    // 2. Show Specific Applicant Details
    public function show($id)
    {
        $application = Application::with(['job', 'applicant'])->findOrFail($id);
        return view('admin.applicants_show', compact('application'));
    }

    // 3. Update Status (Auto-Hire & Auto-Onboarding Logic Included)
    public function updateStatus(Request $request, $id)
    {
        // Fetch application with job and applicant details
        $application = Application::with(['job', 'applicant.user'])->findOrFail($id);

        // ======================================================
        // AUTOMATIC HIRING LOGIC
        // ======================================================
        if ($request->status === 'Hired') {
            
            $user = $application->applicant->user;
            
            // Check if already exists to prevent duplicate employees
            $exists = Employee::where('user_id', $user->user_id)->exists();
            
            if (!$exists) {
                // 1. Resolve Department (Find or Create)
                $department = Department::firstOrCreate(
                    ['department_name' => $application->job->department],
                    ['created_at' => now(), 'updated_at' => now()]
                );

                // 2. Resolve Position (Find or Create)
                $position = \App\Models\Position::firstOrCreate(
                    ['position_name' => $application->job->job_title], // Search by Name
                    [
                        'department_id' => $department->department_id, // <--- THE FIX: Link it to the Department
                        'created_at' => now(), 
                        'updated_at' => now()
                    ]
                );

                // 3. Generate Employee Code (e.g., EMP-XXXXXX)
                $empCode = 'EMP-' . strtoupper(uniqid());

                // 4. Create Employee Record
                $newEmployee = Employee::create([
                    'user_id'         => $user->user_id,
                    'department_id'   => $department->department_id,
                    'position_id'     => $position->position_id,
                    'employee_code'   => $empCode,
                    'employee_status' => 'active',
                    'hire_date'       => now(),
                    'base_salary'     => 0.00,
                    'phone'           => $application->applicant->phone,
                    'address'         => $application->applicant->location ?? 'Not Provided',
                ]);

                // 5. Update User Role to Employee
                $user->role = 'employee';
                $user->save();

                // ======================================================
                // NEW: AUTOMATIC ONBOARDING GENERATION
                // ======================================================
                
                // A. Create the Main Onboarding Record
                $onboarding = Onboarding::create([
                    'employee_id' => $newEmployee->employee_id,
                    'assigned_by' => Auth::id(), // The Admin who clicked 'Hire'
                    'start_date'  => now(),
                    'end_date'    => now()->addDays(7), // Default: 1 week deadline
                    'status'      => 'Pending'
                ]);

                // B. Define Standard Default Tasks
                $defaultTasks = [
                    ['name' => 'Submit Identity Documents', 'cat' => 'HR Docs'],
                    ['name' => 'Sign Employment Contract', 'cat' => 'Legal'],
                    ['name' => 'Setup Corporate Email', 'cat' => 'IT Setup'],
                    ['name' => 'Attend Company Orientation', 'cat' => 'Training'],
                    ['name' => 'Meet Reporting Manager', 'cat' => 'Integration'],
                ];

                // C. Loop and Create Tasks in Database
                foreach ($defaultTasks as $task) {
                    OnboardingTask::create([
                        'onboarding_id' => $onboarding->onboarding_id,
                        'task_name'     => $task['name'],
                        'category'      => $task['cat'],
                        'is_completed'  => false,
                        'due_date'      => now()->addDays(5), // Default due date
                    ]);
                }
            }
        }

        // ======================================================
        // STANDARD STATUS UPDATE
        // ======================================================
        $application->app_stage = $request->status;
        $application->save();

        $message = ($request->status === 'Hired') 
                 ? 'Candidate Hired! Employee profile created and Onboarding Checklist generated.' 
                 : 'Applicant status updated successfully!';

        return redirect()->back()->with('success', $message);
    }

    // 4. Save Evaluation Scores
    public function saveEvaluation(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        
        $overall = ($request->test_score + $request->interview_score) / 2;

        $application->update([
            'test_score' => $request->test_score,
            'interview_score' => $request->interview_score,
            'overall_score' => $overall,
            'evaluation_notes' => $request->notes,
            'app_stage' => 'Interview'
        ]);

        return redirect()->back()->with('success', 'Evaluation saved successfully!');
    }

    // 5. Show Applicant's History
    public function myApplications()
    {
        $user = Auth::user();
        $profile = ApplicantProfile::where('user_id', $user->user_id)->first();

        if (!$profile) {
            return view('applicant.applications', ['applications' => []]);
        }

        $applications = Application::where('applicant_id', $profile->applicant_id)
                                   ->with('job')
                                   ->latest()
                                   ->get();

        return view('applicant.applications', compact('applications'));
    }
}