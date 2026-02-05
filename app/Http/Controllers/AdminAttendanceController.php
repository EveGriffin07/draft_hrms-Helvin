<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminAttendanceController extends Controller
{
    /**
     * Show the attendance tracking page.
     */
    public function tracking(Request $request)
    {
        $departments = Department::orderBy('department_name')->get();

        // Optional pre-selected dates (fallback handled in JS)
        $start = $request->input('start');
        $end   = $request->input('end');

        return view('admin.attendance_tracking', compact('departments', 'start', 'end'));
    }

    /**
     * Return filtered attendance data for the table + summary widgets.
     */
    public function data(Request $request)
    {
        $request->validate([
            'start'      => ['nullable', 'date'],
            'end'        => ['nullable', 'date', 'after_or_equal:start'],
            'status'     => ['nullable', 'in:present,absent,late,leave'],
            'department' => ['nullable', 'integer', 'exists:departments,department_id'],
            'q'          => ['nullable', 'string', 'max:255'],
        ]);

        $start      = $request->input('start');
        $end        = $request->input('end');
        $status     = $request->input('status');
        $department = $request->input('department');
        $search     = $request->input('q');

        $query = Attendance::with(['employee.department', 'employee.user']);

        if ($start) {
            $query->whereDate('date', '>=', $start);
        }
        if ($end) {
            $query->whereDate('date', '<=', $end);
        }
        if ($status) {
            $query->where('at_status', $status);
        }
        if ($department) {
            $query->whereHas('employee', function ($q) use ($department) {
                $q->where('department_id', $department);
            });
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($e) use ($search) {
                    $e->where('employee_code', 'like', "%{$search}%")
                      ->orWhere('employee_id', $search);
                })->orWhereHas('employee.user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $records = $query
            ->orderBy('date', 'desc')
            ->orderBy('employee_id')
            ->get();

        $data = $records->map(function ($row) {
            $employee   = $row->employee;
            $user       = $employee?->user;
            $department = $employee?->department;

            return [
                'attendance_id' => $row->attendance_id,
                'date'          => Carbon::parse($row->date)->format('Y-m-d'),
                'id'            => $employee?->employee_code ?? ('EMP-'.$row->employee_id),
                'name'          => $user->name ?? 'Unknown',
                'dept'          => $department->department_name ?? 'N/A',
                'in'            => $row->clock_in_time ? Carbon::parse($row->clock_in_time)->format('H:i') : '-',
                'out'           => $row->clock_out_time ? Carbon::parse($row->clock_out_time)->format('H:i') : '-',
                'status'        => ucfirst($row->at_status),
            ];
        });

        // Summaries on the already-filtered set
        $summary = [
            'total'   => $records->count(),
            'present' => $records->where('at_status', 'present')->count(),
            'late'    => $records->where('at_status', 'late')->count(),
            'absent'  => $records->where('at_status', 'absent')->count(),
            'leave'   => $records->where('at_status', 'leave')->count(),
        ];

        return response()->json([
            'data'    => $data,
            'summary' => $summary,
        ]);
    }
}
