<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\JobPost;
use App\Models\ApplicantProfile;
// Import User model if needed, though we access it via relationship
use App\Models\User;

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

    // 3. Update Status (Auto-Hire Logic Included)
    public function updateStatus(Request $request, $id)
    {
        // Fetch application with job and applicant details
        $application = Application::with(['job', 'applicant.user'])->findOrFail($id);

        // ======================================================
        // AUTOMATIC ONBOARDING LOGIC
        // If status is becoming 'Hired', create the Employee record immediately.
        // ======================================================
        if ($request->status === 'Hired') {
            
            $user = $application->applicant->user;
            
            // Check if already exists to prevent duplicate employees
            $exists = \App\Models\Employee::where('user_id', $user->user_id)->exists();
            
            if (!$exists) {
                // 1. Resolve Department (Find or Create)
                $department = \App\Models\Department::firstOrCreate(
                    ['department_name' => $application->job->department],
                    ['created_at' => now(), 'updated_at' => now()]
                );

                // 2. Resolve Position (Find or Create)
                $position = \App\Models\Position::firstOrCreate(
                    ['position_name' => $application->job->job_title],
                    ['created_at' => now(), 'updated_at' => now()]
                );

                // 3. Generate Employee Code (e.g., EMP-XXXXXX)
                $empCode = 'EMP-' . strtoupper(uniqid());

                // 4. Create Employee Record
                \App\Models\Employee::create([
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

                // ======================================================
                // CRITICAL FIX: UPDATE USER ROLE TO EMPLOYEE
                // ======================================================
                // This ensures next time they login, they go to Employee Dashboard
                $user->role = 'employee';
                $user->save(); 
            }
        }

        // ======================================================
        // STANDARD STATUS UPDATE
        // ======================================================
        $application->app_stage = $request->status;
        $application->save();

        $message = ($request->status === 'Hired') 
                 ? 'Candidate has been Hired, User Role updated to Employee, and added to Database!' 
                 ? 'Candidate has been Hired and added to Employee Database!' 
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
