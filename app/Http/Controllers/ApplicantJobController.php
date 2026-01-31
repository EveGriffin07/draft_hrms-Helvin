<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobPost;
use App\Models\Application;
use App\Models\ApplicantProfile; 

class ApplicantJobController extends Controller
{
    // 1. Show All Open Jobs
    public function index()
    {
        // Fetch only 'Open' jobs, sorted by latest
        $jobs = JobPost::where('job_status', 'Open')
                        // ->where('closing_date', '>=', now()) // Uncomment if you want to enforce dates
                        ->latest()
                        ->get();

        return view('applicant.jobs', compact('jobs'));
    }

    // 2. Show Job Details
    public function show($id)
    {
        $job = JobPost::findOrFail($id);
        return view('applicant.job_details', compact('job'));
    }

    // 3. Show Application Form
    public function applyForm($id)
    {
        $job = JobPost::findOrFail($id);
        return view('applicant.job_apply', compact('job'));
    }

    // 4. Submit Application
    public function submitApplication(Request $request, $id)
    {
        $request->validate([
            'resume' => 'required|mimes:pdf,doc,docx|max:2048', 
            'phone' => 'required|string', 
            'cover_letter' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Create or Update Applicant Profile
        $profile = ApplicantProfile::updateOrCreate(
            ['user_id' => $user->user_id],
            [
                'full_name' => $user->name,
                'phone' => $request->phone, 
            ]
        );

        // Handle File Upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            
            // Save resume path to profile too
            $profile->resume_path = $resumePath;
            $profile->save();
        } elseif ($profile->resume_path) {
            $resumePath = $profile->resume_path;
        }

        // Create Application Record
        Application::create([
            'job_id' => $id,
            'applicant_id' => $profile->applicant_id, 
            'app_stage' => 'Applied',
            'resume_path' => $resumePath,
            'cover_letter' => $request->cover_letter,
        ]);

        return redirect()->route('applicant.jobs')
                         ->with('success', 'Application submitted successfully!');
    }

    // =========================================================
    // 5. Show Applicant's History (THIS WAS MISSING)
    // =========================================================
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

    // =========================================================
    // 6. Show Profile Page
    // =========================================================
    public function profile()
    {
        $user = Auth::user();

        // Get or Create a profile for the user (so the page never crashes)
        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->user_id],
            ['full_name' => $user->name, 'email' => $user->email]
        );

        return view('applicant.profile', compact('user', 'profile'));
    }

    // =========================================================
    // 7. Update Profile (Save Changes)
    // =========================================================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profile = ApplicantProfile::where('user_id', $user->user_id)->firstOrFail();

        $request->validate([
            'phone' => 'required|string|max:20',
            'location' => 'nullable|string|max:255',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // <--- Add Validation
        ]);

        // 1. Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists (optional cleanup)
            // if ($profile->avatar_path) Storage::delete('public/' . $profile->avatar_path);

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar_path = $avatarPath;
        }

        // 2. Handle Resume Upload
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            $profile->resume_path = $resumePath;
        }

        // 3. Update Text Fields
        $profile->phone = $request->phone;
        $profile->location = $request->location;
        $profile->save();

        return redirect()->route('applicant.profile')
                         ->with('success', 'Profile updated successfully!');
    }

    // =========================================================
    // 8. Delete Resume
    // =========================================================
    public function deleteResume()
    {
        $user = Auth::user();
        $profile = ApplicantProfile::where('user_id', $user->user_id)->firstOrFail();

        if ($profile->resume_path) {
            // Optional: Delete the actual file from storage
            // Storage::delete('public/' . $profile->resume_path);

            $profile->resume_path = null; // Remove from database
            $profile->save();
            
            return redirect()->back()->with('success', 'Resume removed successfully.');
        }

        return redirect()->back()->with('error', 'No resume to remove.');
    }
}