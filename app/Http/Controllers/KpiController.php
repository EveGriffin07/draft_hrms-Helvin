<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KpiTemplate;
use App\Models\DepartmentKpi;
use App\Models\EmployeeKpi;
use App\Models\Department;
use App\Models\Employee;

class KpiController extends Controller
{
    // --- 1. DASHBOARD PAGE (Dynamic Data) ---
    public function index()
    {
        // Counts for the Summary Cards
        $totalEmployees = Employee::count();
        $totalKpis      = KpiTemplate::count();
        $underReview    = EmployeeKpi::where('kpi_status', 'in_progress')->count();
        $completed      = EmployeeKpi::where('kpi_status', 'completed')->count();

        // Fetch Department KPIs for the "Current KPI Goals" table
        // We use 'with' to load the department name and template title
        $deptKpis = DepartmentKpi::with(['department', 'template'])->take(5)->get();

        return view('admin.appraisal_admin', compact('totalEmployees', 'totalKpis', 'underReview', 'completed', 'deptKpis'));
    }

    // --- 2. CREATE FORM (Already Done) ---
    public function create()
    {
        $departments = Department::all();
        // Eager load 'user' and 'department' to avoid "Attempt to read property on null"
        $employees   = Employee::with(['user', 'department'])->get();
        
        return view('admin.appraisal_add_kpi', compact('departments', 'employees'));
    }

    // --- 3. STORE LOGIC (Already Done) ---
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'kpiTitle' => 'required|string|max:255',
            'kpiType'  => 'required',
            'targetValue' => 'required|numeric',
            'assignedTo' => 'required',
            'startDate' => 'required|date',
            'endDate'   => 'required|date|after_or_equal:startDate',
        ]);

        // 2. Use Transaction to ensure both Template and Assignment are saved
        DB::transaction(function () use ($request) {
            
            // A. Create the Master Template
            $template = KpiTemplate::create([
                'kpi_title'       => $request->kpiTitle,
                'kpi_description' => $request->kpiDescription,
                'kpi_type'        => $request->kpiType,
                'default_target'  => $request->targetValue,
            ]);

            // B. Check who it is assigned to
            if ($request->assignedTo === 'Employee') {
                
                // Create PERSONAL KPI
                EmployeeKpi::create([
                    'employee_id'   => $request->employee, // Value comes from the <select name="employee">
                    'kpi_id'        => $template->kpi_id,
                    
                    // Map Form Dates to Database Columns
                    'assigned_date' => $request->startDate, 
                    'deadline'      => $request->endDate,
                    
                    'kpi_status'    => 'pending',
                ]);

            } elseif ($request->assignedTo === 'Department') {
                
                // Create DEPARTMENT KPI
                DepartmentKpi::create([
                    'department_id' => $request->department,
                    'kpi_id'        => $template->kpi_id,
                    
                    // Map Form Dates
                    'period_start'  => $request->startDate,
                    'period_end'    => $request->endDate,
                    'deadline'      => $request->endDate,
                    
                    'target'        => $request->targetValue,
                    'status'        => 'active',
                    'user_id'       => Auth::id() ?? 1, // The Admin who created it
                ]);
            }
        });

        return redirect()->route('admin.appraisal')->with('success', 'KPI Goal Created Successfully!');
    }

    // --- 4. EMPLOYEE LIST PAGE ---
    public function employeeList()
    {
        // Get employees and count how many KPIs each has
        $employees = Employee::with(['department', 'user'])->withCount('employeeKpis')->get();
        
        return view('admin.appraisal_kpi_employee_list', compact('employees'));
    }

    // --- 5. INDIVIDUAL EMPLOYEE KPI DETAILS ---
    public function showEmployeeKpis(Request $request)
    {
        // Get employee ID from the URL (?emp=1)
        $empId = $request->query('emp');

        // Find the employee or fail
        $employee = Employee::with(['department', 'user'])->findOrFail($empId);

        // Get their KPIs
        $kpis = EmployeeKpi::where('employee_id', $empId)->with('template')->get();

        return view('admin.appraisal_kpi_employee', compact('employee', 'kpis'));
    }
    
    // --- 6. UPDATE SCORE (For the Review Modal) ---
    public function updateScore(Request $request, $id)
    {
        $kpi = EmployeeKpi::findOrFail($id);
        $kpi->update([
            'actual_score' => $request->score,
            'kpi_status'   => $request->status,
            'comments'     => $request->comment,
        ]);

        return back()->with('success', 'KPI Updated Successfully!');
    }

    public function showDepartmentKpi($id)
    {
        // 1. Fetch the Department KPI with its relationships
        $deptKpi = DepartmentKpi::with(['department', 'template', 'creator'])->findOrFail($id);

        // 2. (Optional) Fetch related Employee KPIs if your logic links them
        // For now, we will just fetch employees in that same department to show the list
        $employees = Employee::where('department_id', $deptKpi->department_id)
                             ->with('user')
                             ->withCount('employeeKpis')
                             ->get();

        return view('admin.appraisal_department_kpi', compact('deptKpi', 'employees'));
    }

    public function myKpis()
    {
        // 1. Get the currently logged-in User
        $user = Auth::user();

        // 2. Find their Employee Record
        // (We assume the User model has an 'employee' relationship, or we query manually)
        $employee = Employee::where('user_id', $user->user_id)->first();

        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Employee profile not found.');
        }

        // 3. Fetch their KPIs
        $kpis = EmployeeKpi::where('employee_id', $employee->employee_id)
                           ->with('template')
                           ->get();

        // 4. Return the view (we will create this next)
        return view('employee.kpi_my_list', compact('employee', 'kpis'));
    }

    // --- 9. EMPLOYEE: Show Self-Evaluation Form ---
    public function selfEvaluationList()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        // Assuming User links to Employee via 'user_id'
        $employee = \App\Models\Employee::where('user_id', $user->user_id)->first();

        if (!$employee) {
            return redirect()->route('employee.dashboard')->with('error', 'Employee record not found.');
        }

        // Fetch all KPIs assigned to this employee
        $kpis = \App\Models\EmployeeKpi::where('employee_id', $employee->employee_id)
                           ->with('template')
                           ->get();

        return view('employee.kpi_self_eval', compact('kpis'));
    }

    // --- 10. EMPLOYEE: Submit Self-Evaluation ---
    public function submitSelfEval(Request $request, $id)
    {
        $request->validate([
            'self_rating' => 'required|numeric|min:0|max:100',
            'employee_comments' => 'nullable|string'
        ]);

        $kpi = \App\Models\EmployeeKpi::findOrFail($id);
        
        // Update the record
        $kpi->update([
            'self_rating' => $request->self_rating,
            'employee_comments' => $request->employee_comments,
            // Optionally set status to 'In Progress' so Manager sees it's active
            'kpi_status' => 'in_progress' 
        ]);

        return back()->with('success', 'Self-evaluation saved successfully!');
    }
}