<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminPenaltyController extends Controller
{
    /**
     * Show the Penalty Removal & Tracking page.
     */
    public function index()
    {
        $departments = Department::orderBy('department_name')->get();
        return view('admin.attendance_penalty', compact('departments'));
    }

    /**
     * JSON: list penalties with filters + summary.
     */
    public function data(Request $request)
    {
        $request->validate([
            'start'      => ['nullable', 'date'],
            'end'        => ['nullable', 'date', 'after_or_equal:start'],
            'status'     => ['nullable', 'in:pending,approved,rejected'],
            'department' => ['nullable', 'integer', 'exists:departments,department_id'],
            'reason'     => ['nullable', 'string', 'max:255'],
            'q'          => ['nullable', 'string', 'max:255'],
        ]);

        $query = Penalty::with(['employee.department', 'employee.user']);

        if ($request->filled('start')) {
            $query->whereDate('assigned_at', '>=', $request->input('start'));
        }
        if ($request->filled('end')) {
            $query->whereDate('assigned_at', '<=', $request->input('end'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('reason')) {
            $reason = $request->input('reason');
            $query->where('penalty_name', 'like', "%{$reason}%");
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

        $penalties = $query
            ->orderBy('assigned_at', 'desc')
            ->orderBy('penalty_id', 'desc')
            ->get();

        $data = $penalties->map(function ($p) {
            $emp  = $p->employee;
            $user = $emp?->user;
            $dept = $emp?->department;
            return [
                'penalty_id' => $p->penalty_id,
                'pid'     => 'P-' . str_pad($p->penalty_id, 4, '0', STR_PAD_LEFT),
                'id'      => $emp?->employee_code ?? ('EMP-' . $p->employee_id),
                'name'    => $user->name ?? 'Unknown',
                'dept'    => $dept->department_name ?? 'N/A',
                'reason'  => $p->penalty_name,
                'points'  => (float) $p->default_amount,
                'date'    => Carbon::parse($p->assigned_at)->format('Y-m-d'),
                'status'  => ucfirst($p->status),
            ];
        });

        $summary = [
            'total'    => $penalties->count(),
            'pending'  => $penalties->where('status', 'pending')->count(),
            'approved' => $penalties->where('status', 'approved')->count(),
            'rejected' => $penalties->where('status', 'rejected')->count(),
        ];

        return response()->json([
            'data'    => $data,
            'summary' => $summary,
        ]);
    }

    /**
     * Approve or reject a penalty.
     */
    public function updateStatus(Request $request, Penalty $penalty)
    {
        $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        if ($penalty->status !== 'pending') {
            return response()->json(['message' => 'Penalty already decided.'], 422);
        }

        $penalty->status = $request->input('action') === 'approve' ? 'approved' : 'rejected';
        $penalty->removed_at = $penalty->status === 'approved' ? now()->toDateString() : null;
        $penalty->save();

        return response()->json(['message' => 'Penalty updated.']);
    }
}
