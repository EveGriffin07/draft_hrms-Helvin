<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingProgram;
use App\Models\TrainingEnrollment;
use App\Models\Department;
use App\Models\Employee; 
use Carbon\Carbon;

class TrainingController extends Controller
{
    public function index()
    {
        $programs = TrainingProgram::with('department')->orderBy('start_date', 'desc')->get();
        
        $total = $programs->count();
        $ongoing = $programs->where('tr_status', 'active')->count();
        $completed = $programs->where('tr_status', 'completed')->count();
        $upcoming = $programs->where('tr_status', 'planned')->count();

        return view('admin.training_admin', compact('programs', 'total', 'ongoing', 'completed', 'upcoming'));
    }

    // --- UPDATED: Fetch Departments for Dropdown ---
    public function create()
    {
        $departments = Department::all(); // Fetch all departments
        return view('admin.training_add', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainingTitle' => 'required|string|max:255',
            'trainerName'   => 'required|string|max:255',
            'department'    => 'required|string', 
            'startDate'     => 'required|date',
            'endDate'       => 'required|date|after_or_equal:startDate',
            'mode'          => 'required|string',
            'location'      => 'required|string',
            'description'   => 'nullable|string',
        ]);

        // Find department by name (since the value in option is the name)
        $dept = Department::where('department_name', $request->department)->first();
        $deptId = $dept ? $dept->department_id : null; 

        $today = Carbon::today();
        $start = Carbon::parse($request->startDate);
        $end = Carbon::parse($request->endDate);
        
        $status = 'planned';
        if ($today->between($start, $end)) {
            $status = 'active';
        } elseif ($today->gt($end)) {
            $status = 'completed';
        }

        TrainingProgram::create([
            'training_name'  => $request->trainingTitle,
            'provider'       => $request->trainerName,
            'department_id'  => $deptId,
            'start_date'     => $request->startDate,
            'end_date'       => $request->endDate,
            'mode'           => $request->mode,
            'location'       => $request->location,
            'tr_description' => $request->description,
            'tr_status'      => $status,
        ]);

        return redirect()->route('admin.training')->with('success', 'Training program created successfully!');
    }

    public function show($id)
    {
        $program = TrainingProgram::with(['enrollments.employee.user', 'department'])
                     ->findOrFail($id);

        $enrolledEmployeeIds = $program->enrollments->pluck('employee_id')->toArray();
        
        $potentialTrainees = Employee::with('user')
                             ->whereNotIn('employee_id', $enrolledEmployeeIds)
                             ->where('employee_status', 'active')
                             ->get()
                             ->sortBy(function($employee) {
                                 return $employee->user->name ?? '';
                             });

        return view('admin.training_show', compact('program', 'potentialTrainees'));
    }

    public function storeEnrollment(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
        ]);

        TrainingEnrollment::create([
            'training_id'       => $id,
            'employee_id'       => $request->employee_id,
            'enrollment_date'   => now(),
            'completion_status' => 'enrolled',
            'remarks'           => null
        ]);

        return redirect()->back()->with('success', 'Employee enrolled successfully!');
    }

    public function getEvents()
    {
        $programs = TrainingProgram::all();

        $events = [];

        foreach ($programs as $program) {
            $color = '#3b82f6'; // Default Blue
            if ($program->tr_status == 'completed') $color = '#22c55e'; // Green
            if ($program->tr_status == 'planned')   $color = '#f97316'; // Orange

            $events[] = [
                'title' => $program->training_name . ' (' . $program->mode . ')',
                'start' => $program->start_date,
                'end'   => \Carbon\Carbon::parse($program->end_date)->addDay()->format('Y-m-d'),
                'url'   => route('admin.training.show', $program->training_id),
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        }

        return response()->json($events);
    }

    public function updateEnrollmentStatus(Request $request, $id)
    {
        $request->validate([
            'completion_status' => 'required|in:enrolled,completed,failed',
            'remarks'           => 'nullable|string|max:255'
        ]);

        $enrollment = TrainingEnrollment::findOrFail($id);
        
        $enrollment->update([
            'completion_status' => $request->completion_status,
            'remarks'           => $request->remarks
        ]);

        return redirect()->back()->with('success', 'Participant status updated successfully!');
    }

    // --- UPDATED: Fetch Departments for Edit Dropdown too ---
    public function edit($id)
    {
        $program = TrainingProgram::with('department')->findOrFail($id);
        $departments = Department::all(); // Fetch all departments for the edit list
        return view('admin.training_edit', compact('program', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'trainingTitle' => 'required|string|max:255',
            'trainerName'   => 'required|string|max:255',
            'department'    => 'required|string', 
            'startDate'     => 'required|date',
            'endDate'       => 'required|date|after_or_equal:startDate',
            'mode'          => 'required|string',
            'location'      => 'required|string',
            'description'   => 'nullable|string',
        ]);

        $program = TrainingProgram::findOrFail($id);

        $dept = Department::where('department_name', $request->department)->first();
        $deptId = $dept ? $dept->department_id : null;

        $today = Carbon::today();
        $start = Carbon::parse($request->startDate);
        $end = Carbon::parse($request->endDate);
        
        $status = 'planned';
        if ($today->between($start, $end)) {
            $status = 'active';
        } elseif ($today->gt($end)) {
            $status = 'completed';
        }

        $program->update([
            'training_name'  => $request->trainingTitle,
            'provider'       => $request->trainerName,
            'department_id'  => $deptId,
            'start_date'     => $request->startDate,
            'end_date'       => $request->endDate,
            'mode'           => $request->mode,
            'location'       => $request->location,
            'tr_description' => $request->description,
            'tr_status'      => $status,
        ]);

        return redirect()->route('admin.training.show', $id)->with('success', 'Training program updated successfully!');
    }

    public function destroy($id)
    {
        $program = TrainingProgram::findOrFail($id);

        // Delete all student enrollments first to prevent Foreign Key Error
        $program->enrollments()->delete();

        // Now delete the program
        $program->delete();

        return redirect()->route('admin.training')->with('success', 'Training program deleted successfully.');
    }
}