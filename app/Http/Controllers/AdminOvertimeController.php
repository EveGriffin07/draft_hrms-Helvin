<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRecord;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminOvertimeController extends Controller
{
    /**
     * Show the overtime claim review page.
     */
    public function index()
    {
        $departments = Department::orderBy('department_name')->get();
        return view('admin.payroll_overtime', compact('departments'));
    }

    /**
     * Return filtered overtime claims as JSON for the datatable.
     */
    public function data(Request $request)
    {
        $request->validate([
            'start'      => ['nullable', 'date'],
            'end'        => ['nullable', 'date', 'after_or_equal:start'],
            'status'     => ['nullable', 'in:pending,approved,rejected'],
            'department' => ['nullable', 'integer', 'exists:departments,department_id'],
            'q'          => ['nullable', 'string', 'max:255'],
        ]);

        $query = OvertimeRecord::with(['employee.department', 'employee.user']);

        if ($request->filled('start')) {
            $query->whereDate('date', '>=', $request->input('start'));
        }
        if ($request->filled('end')) {
            $query->whereDate('date', '<=', $request->input('end'));
        }
        if ($request->filled('status')) {
            $query->where('ot_status', $request->input('status'));
        }
        if ($request->filled('department')) {
            $deptId = $request->input('department');
            $query->whereHas('employee', function ($q) use ($deptId) {
                $q->where('department_id', $deptId);
            });
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

        $records = $query
            ->orderBy('date', 'desc')
            ->orderBy('ot_id', 'desc')
            ->get();

        $data = $records->map(function ($r) {
            $emp  = $r->employee;
            $user = $emp?->user;
            $dept = $emp?->department;
            return [
                'ot_id'    => $r->ot_id,
                'employee' => $user->name ?? 'Unknown',
                'code'     => $emp?->employee_code ?? ('EMP-' . $r->employee_id),
                'dept'     => $dept->department_name ?? 'N/A',
                'date'     => Carbon::parse($r->date)->format('Y-m-d'),
                'hours'    => (float) $r->hours,
                'reason'   => $r->reason ?? 'N/A',
                'status'   => ucfirst($r->ot_status),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Approve or reject an overtime claim.
     */
    public function updateStatus(Request $request, OvertimeRecord $overtime)
    {
        $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        if ($overtime->ot_status !== 'pending') {
            return response()->json(['message' => 'Already decided'], 422);
        }

        $overtime->ot_status = $request->input('action') === 'approve' ? 'approved' : 'rejected';
        $overtime->approved_by = Auth::id();
        $overtime->save();

        return response()->json(['message' => 'Overtime updated']);
    }
}
