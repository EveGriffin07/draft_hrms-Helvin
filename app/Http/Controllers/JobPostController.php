<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class JobPostController extends Controller
{
    // 1. List all active jobs (recruitment_admin.blade.php)
    public function index()
    {
        // Fetch jobs, latest first
        $jobPosts = JobPost::latest()->get(); 
        
        return view('admin.recruitment_admin', compact('jobPosts'));
    }

    // 2. Show the Create Form (recruitment_add.blade.php)
    public function create()
    {
        // Make sure this matches your actual filename 'recruitment_add.blade.php'
        return view('admin.recruitment_add');
    }

    // 3. Store the new Job Post
    public function store(Request $request)
    {
        // Validate inputs match the form fields
        $request->validate([
            'job_title' => 'required|string|max:255',
            'job_type' => 'required|string',
            'department' => 'required|string',
            'location' => 'required|string',
            'closing_date' => 'required|date|after:today',
            'salary_range' => 'nullable|string', // Made nullable in case user leaves empty
            'job_description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        JobPost::create([
            'job_title' => $request->job_title,
            'job_type' => $request->job_type,
            'department' => $request->department,
            'location' => $request->location,
            'closing_date' => $request->closing_date,
            'salary_range' => $request->salary_range,
            'job_description' => $request->job_description,
            'requirements' => $request->requirements,
            // 'closing_date' removed because your form doesn't have this input yet
            'job_status' => 'Open',
            'posted_by' => Auth::id() ?? 1, // Fallback to 1 for testing
        ]);

        return redirect()->route('admin.recruitment.index')
                         ->with('success', 'Job Posted Successfully!');
    }

    // 4. Show Edit Form (Reusing the add view logic, but passing the job)
    public function edit($id)
    {
        $job = JobPost::findOrFail($id);
        
        // FIXED: Changed from 'recruitment_create' to 'recruitment_add'
        return view('admin.recruitment_add', compact('job')); 
    }

    // 5. Save the Changes (Update)
    public function update(Request $request, $id)
    {
        // Validate all fields
        $request->validate([
            'job_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'job_type' => 'required|string',
            'location' => 'required|string',
            'closing_date' => 'required|date',
            'salary_range' => 'nullable|string',
            'job_description' => 'required|string',
            'requirements' => 'required|string',
            'job_status' => 'required|string', // Status is now editable
        ]);

        $job = JobPost::findOrFail($id);

        // Update every field in the database
        $job->update([
            'job_title' => $request->job_title,
            'department' => $request->department,
            'job_type' => $request->job_type,
            'location' => $request->location,
            'closing_date' => $request->closing_date,
            'salary_range' => $request->salary_range,
            'job_description' => $request->job_description,
            'requirements' => $request->requirements,
            'job_status' => $request->job_status,
        ]);

        return redirect()->route('admin.recruitment.index')
                         ->with('success', 'Job post updated successfully!');
    }

    // 6. Delete Job
    public function destroy($id)
    {
        $job = JobPost::findOrFail($id);
        $job->delete();
        
        return redirect()->route('admin.recruitment.index')
                         ->with('success', 'Job post deleted successfully!');
    }
}