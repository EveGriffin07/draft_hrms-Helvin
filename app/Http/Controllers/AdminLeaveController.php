<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminLeaveController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('department_name')->get();
        $leaveTypes  = LeaveType::orderBy('leave_name')->get();
        return view('admin.leave_request', compact('departments', 'leaveTypes'));
    }

    public function data(Request $request)
    {
        $request->validate([
            'department' => ['nullable', 'integer', 'exists:departments,department_id'],
            'type'       => ['nullable', 'integer', 'exists:leave_types,leave_type_id'],
            'status'     => ['nullable', 'in:pending,approved,rejected'],
            'q'          => ['nullable', 'string', 'max:255'],
        ]);

        $query = LeaveRequest::with(['employee.department', 'employee.user', 'leaveType']);

        if ($request->filled('department')) {
            $deptId = $request->input('department');
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        if ($request->filled('type')) {
            $query->where('leave_type_id', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('leave_status', $request->input('status'));
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
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

        $rows = $query->orderBy('start_date', 'desc')->orderBy('leave_request_id', 'desc')->get();

        $data = $rows->map(function ($r) {
            $emp  = $r->employee;
            $user = $emp?->user;
            $dept = $emp?->department;
            $type = $r->leaveType;
            return [
                'id'        => $r->leave_request_id,
                'employee'  => $user->name ?? 'Unknown',
                'code'      => $emp?->employee_code ?? ('EMP-' . $r->employee_id),
                'dept_id'   => $dept->department_id ?? null,
                'dept'      => $dept->department_name ?? 'N/A',
                'type_id'   => $type->leave_type_id ?? null,
                'type'      => $type->leave_name ?? 'N/A',
                'start'     => Carbon::parse($r->start_date)->format('Y-m-d'),
                'end'       => Carbon::parse($r->end_date)->format('Y-m-d'),
                'days'      => (int) $r->total_days,
                'reason'    => $r->reason ?? '-',
                'status'    => ucfirst($r->leave_status),
            ];
        });

        $summary = [
            'total'    => $rows->count(),
            'pending'  => $rows->where('leave_status', 'pending')->count(),
            'approved' => $rows->where('leave_status', 'approved')->count(),
            'rejected' => $rows->where('leave_status', 'rejected')->count(),
        ];

        return response()->json(['data' => $data, 'summary' => $summary]);
    }

    public function updateStatus(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $leave->leave_status = $request->input('status');
        $leave->approved_by  = Auth::id();
        $leave->save();

        return response()->json(['message' => 'Leave status updated']);
    }
}
