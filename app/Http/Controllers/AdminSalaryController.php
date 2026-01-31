<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OvertimeRecord;
use App\Models\Penalty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminSalaryController extends Controller
{
    /**
     * Show salary calculation page.
     */
    public function index()
    {
        $departments = Department::orderBy('department_name')->get();
        return view('admin.payroll_salary', compact('departments'));
    }

    /**
     * Return salary calc dataset (lightweight, not persisted).
     */
    public function data(Request $request)
    {
        $request->validate([
            'start'      => ['nullable', 'date'],
            'end'        => ['nullable', 'date', 'after_or_equal:start'],
            'department' => ['nullable', 'integer', 'exists:departments,department_id'],
        ]);

        $start = $request->input('start');
        $end   = $request->input('end');
        $deptId = $request->input('department');

        $employees = Employee::with(['department', 'user'])
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))
            ->get();

        $result = $employees->map(function ($emp) use ($start, $end) {
            $hourly = $emp->base_salary ? ($emp->base_salary / 160) : 0;

            $otHours = OvertimeRecord::where('employee_id', $emp->employee_id)
                ->where('ot_status', 'approved')
                ->when($start, fn($q) => $q->whereDate('date', '>=', $start))
                ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
                ->sum('hours');

            $otRateType = OvertimeRecord::where('employee_id', $emp->employee_id)
                ->where('ot_status', 'approved')
                ->when($start, fn($q) => $q->whereDate('date', '>=', $start))
                ->when($end, fn($q) => $q->whereDate('date', '<=', $end))
                ->avg('rate_type') ?? 1.5;

            $otPay = $otHours * $hourly * $otRateType;

            $penalty = Penalty::where('employee_id', $emp->employee_id)
                ->where('status', 'approved')
                ->when($start, fn($q) => $q->whereDate('assigned_at', '>=', $start))
                ->when($end, fn($q) => $q->whereDate('assigned_at', '<=', $end))
                ->sum('default_amount');

            $base   = (float) $emp->base_salary;
            $allow  = 0.0; // placeholder; extend once allowance structure exists
            $epfTax = round($base * 0.11, 2); // simple 11% demo
            $gross  = $base + $allow + $otPay;
            $ded    = $penalty + $epfTax;
            $net    = $gross - $ded;

            return [
                'id'        => $emp->employee_code ?? ('EMP-' . $emp->employee_id),
                'name'      => $emp->user->name ?? 'Unknown',
                'dept'      => $emp->department->department_name ?? 'N/A',
                'base'      => round($base, 2),
                'allow'     => round($allow, 2),
                'allowItems'=> [],
                'otHrs'     => round($otHours, 2),
                'otRate'    => round($hourly * $otRateType, 2),
                'penalty'   => round($penalty, 2),
                'epfTax'    => round($epfTax, 2),
                'last'      => now()->toDateString(),
            ];
        });

        return response()->json(['data' => $result]);
    }
}
