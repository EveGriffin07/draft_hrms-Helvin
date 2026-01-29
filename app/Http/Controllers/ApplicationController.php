<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\JobPost;

class ApplicationController extends Controller
{
    // 1. List all Applicants (recruitment_applicants.blade.php)
    public function index()
    {
        // Fetch applications with their related Job and Applicant data
        // Order by latest applied
        $applications = Application::with(['job', 'applicant'])
                        ->latest()
                        ->get();

        return view('admin.recruitment_applicants', compact('applications'));
    }

    // 2. Show Specific Applicant Details (applicants_show.blade.php)
    public function show($id)
    {
        $application = Application::with(['job', 'applicant'])->findOrFail($id);
        return view('admin.applicants_show', compact('application'));
    }

    // 3. Update Status (e.g., Move to Interview) - Optional for now
    public function updateStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->app_stage = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Applicant status updated successfully!');
    }

    public function saveEvaluation(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        
        // Calculate Overall Score automatically
        $overall = ($request->test_score + $request->interview_score) / 2;

        $application->update([
            'test_score' => $request->test_score,
            'interview_score' => $request->interview_score,
            'overall_score' => $overall,
            'evaluation_notes' => $request->notes, // Form input "notes" maps to DB "evaluation_notes"
            'app_stage' => 'Interview' // Update stage to reflect progress
        ]);

        return redirect()->back()->with('success', 'Evaluation saved successfully!');
    }

    // 5. Show Applicant's History
    public function myApplications()
    {
        $user = Auth::user();

        // Get the applicant profile ID for this user
        $profile = ApplicantProfile::where('user_id', $user->user_id)->first();

        // If no profile exists yet, they haven't applied to anything
        if (!$profile) {
            return view('applicant.applications', ['applications' => []]);
        }

        // Fetch applications linked to this profile, including Job details
        $applications = Application::where('applicant_id', $profile->applicant_id)
                                   ->with('job') // Load job details (title, dept)
                                   ->latest()
                                   ->get();

        return view('applicant.applications', compact('applications'));
    }
}