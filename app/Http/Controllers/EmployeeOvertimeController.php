<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRecord;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeOvertimeController extends Controller
{
    /**
     * Show overtime submission form and history for the logged-in employee.
     */
    public function index()
    {
        $employee = Auth::user()->employee;
        abort_unless($employee, 403, 'Employee profile not found');

        $records = OvertimeRecord::where('employee_id', $employee->employee_id)
            ->orderBy('date', 'desc')
            ->orderBy('ot_id', 'desc')
            ->get();

        return view('employee.overtime', compact('employee', 'records'));
    }

    /**
     * Store a new overtime request for the logged-in employee.
     */
    public function store(Request $request)
    {
        $employee = Auth::user()->employee;
        abort_unless($employee, 403, 'Employee profile not found');

        $validated = $request->validate([
            'date'      => ['required', 'date', 'before_or_equal:today'],
            'hours'     => ['required', 'numeric', 'min:0.25', 'max:24'],
            'rate_type' => ['nullable', 'numeric', 'min:1', 'max:3'],
            'reason'    => ['nullable', 'string', 'max:255'],
        ]);

        // Find payroll period covering the selected date
        $period = PayrollPeriod::whereDate('start_date', '<=', $validated['date'])
            ->whereDate('end_date', '>=', $validated['date'])
            ->orderBy('start_date', 'desc')
            ->first();

        if (!$period) {
            return back()->withErrors(['date' => 'No payroll period covers the selected date. Please contact HR.'])->withInput();
        }

        OvertimeRecord::create([
            'employee_id' => $employee->employee_id,
            'period_id'   => $period->period_id,
            'date'        => $validated['date'],
            'hours'       => $validated['hours'],
            'rate_type'   => $validated['rate_type'] ?? 1.5,
            'reason'      => $validated['reason'] ?? null,
            'ot_status'   => 'pending',
        ]);

        return back()->with('success', 'Overtime request submitted and pending approval.');
    }

    /**
     * Allow employee to delete their own pending overtime record.
     */
    public function destroy(OvertimeRecord $overtime)
    {
        $employee = Auth::user()->employee;
        abort_unless($employee, 403, 'Employee profile not found');

        if ($overtime->employee_id !== $employee->employee_id || $overtime->ot_status !== 'pending') {
            abort(403, 'You can only delete your own pending overtime.');
        }

        $overtime->delete();

        return back()->with('success', 'Overtime request removed.');
    }
}
