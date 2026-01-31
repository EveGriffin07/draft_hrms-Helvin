<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminEmployeeController extends Controller
{
    /**
     * Display the employee overview with filters and counts.
     */
    public function index(Request $request)
    {
        $search       = $request->input('q');
        $departmentId = $request->input('department');
        $status       = $request->input('status');

        $query = Employee::with(['user', 'department', 'position']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($status) {
            $query->where('employee_status', $status);
        }

        $employees = $query
            ->orderBy('hire_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $totalEmployees   = Employee::count();
        $activeEmployees  = Employee::where('employee_status', 'active')->count();
        $departmentsCount = Department::count();

        $today = now()->toDateString();
        $onLeave = LeaveRequest::where('leave_status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $departments = Department::orderBy('department_name')->get();

        return view('admin.employee_list', compact(
            'employees',
            'totalEmployees',
            'activeEmployees',
            'departmentsCount',
            'onLeave',
            'departments',
            'search',
            'departmentId',
            'status'
        ));
    }

    /**
     * Show the create employee form.
     */
    public function create()
    {
        $departments = Department::orderBy('department_name')->get();
        $positions   = Position::orderBy('position_name')->get();

        return view('admin.employee_add', compact('departments', 'positions'));
    }

    /**
     * Store a newly created employee + user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'department_id'  => ['required', Rule::exists('departments', 'department_id')],
            'position_id'    => ['required', Rule::exists('positions', 'position_id')],
            'hire_date'      => ['required', 'date'],
            'employee_status'=> ['required', Rule::in(['active', 'inactive', 'terminated'])],
            'address'        => ['nullable', 'string'],
            'base_salary'    => ['required', 'numeric', 'min:0'],
        ]);

        $tempPassword = Str::random(10);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($tempPassword),
            'role'     => 'employee',
        ]);

        Employee::create([
            'user_id'         => $user->user_id,
            'department_id'   => $validated['department_id'],
            'position_id'     => $validated['position_id'],
            'employee_code'   => $this->generateEmployeeCode(),
            'employee_status' => $validated['employee_status'],
            'hire_date'       => $validated['hire_date'],
            'base_salary'     => $validated['base_salary'],
            'phone'           => $validated['phone'] ?? null,
            'address'         => $validated['address'] ?? null,
        ]);

        return redirect()
            ->route('admin.employee.list')
            ->with('success', 'Employee created. Temporary password: '.$tempPassword);
    }

    private function generateEmployeeCode(): string
    {
        $next = (Employee::max('employee_id') ?? 0) + 1;
        return 'EMP-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
