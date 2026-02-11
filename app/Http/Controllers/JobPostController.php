<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\Department;
use App\Models\Position; // <--- ADDED THIS
use Illuminate\Support\Facades\Auth;

class JobPostController extends Controller
{
    // 1. List all active jobs
    public function index()
    {
        $jobPosts = JobPost::latest()->get(); 
        return view('admin.recruitment_admin', compact('jobPosts'));
    }

    // 2. Show the Create Form
    public function create()
    {
        $departments = Department::all(); 
        return view('admin.recruitment_add', compact('departments'));
    }

    // 3. Store the new Job Post (AND Update Position Description)
    public function store(Request $request)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'job_type' => 'required|string',
            'department' => 'required|string',
            'location' => 'required|string',
            'closing_date' => 'required|date|after:today',
            'salary_range' => 'nullable|string',
            'job_description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        // A. Find the Department ID (since form sends the Name)
        $dept = Department::where('department_name', $request->department)->firstOrFail();

        // B. Update or Create the Position with the new Description
        Position::updateOrCreate(
            [
                'position_name' => $request->job_title,      // Search Condition 1
                'department_id' => $dept->department_id      // Search Condition 2
            ],
            [
                'pos_description' => $request->job_description // <--- UPDATE THIS COLUMN
            ]
        );

        // C. Create the Job Post
        JobPost::create([
            'job_title' => $request->job_title,
            'job_type' => $request->job_type,
            'department' => $request->department,
            'location' => $request->location,
            'closing_date' => $request->closing_date,
            'salary_range' => $request->salary_range,
            'job_description' => $request->job_description,
            'requirements' => $request->requirements,
            'job_status' => 'Open',
            'posted_by' => Auth::id() ?? 1,
        ]);

        return redirect()->route('admin.recruitment.index')
                         ->with('success', 'Job Posted and Position Description Updated!');
    }

    // 4. Show Edit Form
    public function edit($id)
    {
        $job = JobPost::findOrFail($id);
        $departments = Department::all();
        return view('admin.recruitment_add', compact('job', 'departments')); 
    }

    // 5. Save the Changes (Update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'job_type' => 'required|string',
            'location' => 'required|string',
            'closing_date' => 'required|date',
            'salary_range' => 'nullable|string',
            'job_description' => 'required|string',
            'requirements' => 'required|string',
            'job_status' => 'required|string',
        ]);

        $job = JobPost::findOrFail($id);

        // Optional: We can also update the Position Description on Edit if you want
        $dept = Department::where('department_name', $request->department)->first();
        if ($dept) {
            Position::updateOrCreate(
                ['position_name' => $request->job_title, 'department_id' => $dept->department_id],
                ['pos_description' => $request->job_description]
            );
        }

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