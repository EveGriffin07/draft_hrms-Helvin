<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class AdminLeaveBalanceController extends Controller
{
    public function index()
    {
        $departments = \App\Models\Department::orderBy('department_name')->get();
        $types = LeaveType::orderBy('leave_name')->get();
        return view('admin.leave_balance', compact('departments', 'types'));
    }

    public function data(Request $request)
    {
        $request->validate([
            'department' => ['nullable', 'integer', 'exists:departments,department_id'],
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $employees = Employee::with(['department', 'user'])
            ->when($request->department, fn($q) => $q->where('department_id', $request->department))
            ->get();

        // Stub: using static balances until balance table exists
        $data = $employees->map(function ($e) {
            $annual   = 14;
            $sick     = 8;
            $unpaid   = 999;
            $usedAnnual = 3;
            $usedSick   = 1;
            return [
                'id'      => $e->employee_code ?? ('EMP-' . $e->employee_id),
                'name'    => $e->user->name ?? 'Unknown',
                'dept'    => $e->department->department_name ?? 'N/A',
                'annual'  => $annual - $usedAnnual,
                'sick'    => $sick - $usedSick,
                'unpaid'  => $unpaid,
                'detail'  => [
                    ['type' => 'Annual', 'total' => $annual, 'used' => $usedAnnual],
                    ['type' => 'Sick',   'total' => $sick,   'used' => $usedSick],
                    ['type' => 'Unpaid', 'total' => $unpaid, 'used' => 0],
                ],
            ];
        });

        return response()->json(['data' => $data]);
    }
}
