public function store(Request $request)
{
    // 1. Validation
    $request->validate([
        'kpiTitle'       => 'required|string|max:255',
        'kpiType'        => 'required|string',
        'targetValue'    => 'required|numeric',
        'assignedTo'     => 'required|in:Employee,Department',
        'employee'       => 'required_if:assignedTo,Employee',
        'department'     => 'required_if:assignedTo,Department',
        'startDate'      => 'required|date',
        'endDate'        => 'required|date|after_or_equal:startDate',
        'kpiDescription' => 'nullable|string',
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

        // B. Assign KPI
        if ($request->assignedTo === 'Employee') {

            EmployeeKpi::create([
                'employee_id'   => $request->employee,     // <select name="employee">
                'kpi_id'        => $template->kpi_id,

                'assigned_date' => $request->startDate,
                'deadline'      => $request->endDate,

                'kpi_status'    => 'pending',
            ]);

        } elseif ($request->assignedTo === 'Department') {

            DepartmentKpi::create([
                'department_id' => $request->department,
                'kpi_id'        => $template->kpi_id,

                'period_start'  => $request->startDate,
                'period_end'    => $request->endDate,
                'deadline'      => $request->endDate,

                'target'        => $request->targetValue,
                'status'        => 'active',

                // If your User PK is user_id (not id), use Auth::user()->user_id instead.
                'user_id'       => Auth::id() ?? 1,
            ]);
        }
    });

    return redirect()->route('admin.appraisal')->with('success', 'KPI Goal Created Successfully!');
}
