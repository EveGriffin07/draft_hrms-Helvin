<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add KPI Goals - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>
<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
        <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
            <i class="fa-regular fa-bell"></i> &nbsp; HR Admin
        </a>
    </div>
  </header>

  <div class="container">
    
    @include('admin.layout.sidebar')

    <main>
      <div class="breadcrumb">Home > Appraisal > Add KPI Goals</div>
      <h2>Add KPI Goals</h2>
      <p class="subtitle">Define measurable performance objectives for departments or employees.</p>

      {{-- Success Message --}}
      @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
      @endif

      {{-- Validation Errors --}}
      @if($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif

      <div class="form-container">
        {{-- Updated Action Route --}}
        <form action="{{ route('admin.appraisal.store') }}" method="POST" class="form-card">
          @csrf {{-- CSRF Token is mandatory for POST requests --}}
          
          <h3><i class="fa-solid fa-bullseye"></i> KPI Information</h3>

          <div class="form-group">
            <label for="kpiTitle">KPI Title <span>*</span></label>
            <input type="text" id="kpiTitle" name="kpiTitle" placeholder="e.g., Improve System Efficiency" required>
          </div>

          <div class="form-group full-width">
            <label for="kpiDescription">KPI Description</label>
            <textarea id="kpiDescription" name="kpiDescription" rows="4" placeholder="Describe the KPI objectives and expected outcomes"></textarea>
          </div>

          {{-- ASSIGNMENT TYPE --}}
          <div class="form-group">
            <label for="assignedTo">Assigned To <span>*</span></label>
            <select id="assignedTo" name="assignedTo" required>
              <option value="" disabled selected>Select Target</option>
              <option value="Department">Department</option>
              <option value="Employee">Specific Employee</option>
            </select>
          </div>

          {{-- DEPARTMENT KPI TARGET --}}
          <div class="form-group" id="departmentGroup">
  <label for="department">Department <span>*</span></label>
  <select id="department" name="department">
    <option value="" disabled selected>Select Department</option>
    
    @if(isset($departments) && count($departments) > 0)
        @foreach($departments as $dept)
            {{-- 
               CRITICAL: 
               1. Value is 'department_id' (Required by your database Relation)
               2. Text is 'department_name' (Visible to the user)
            --}}
            <option value="{{ $dept->department_id }}" {{ old('department') == $dept->department_id ? 'selected' : '' }}>
                {{ $dept->department_name }}
            </option>
        @endforeach
    @else
        <option value="" disabled>No Departments Found</option>
    @endif

  </select>
</div>

          {{-- EMPLOYEE KPI TARGET --}}
          <div class="form-group" id="employeeGroup" style="display:none;">
    <label for="employee">Employee <span>*</span></label>
    <select id="employee" name="employee">
        <option value="" disabled selected>Select Employee</option>

        @if(isset($employees) && count($employees) > 0)
            @foreach($employees as $emp)
                <option value="{{ $emp->employee_id }}">
                    {{-- 
                       CORRECTED LOGIC:
                       1. Name: Access via the 'user' relationship ($emp->user->name)
                       2. Department: Access via the 'department' relationship ($emp->department->department_name)
                    --}}
                    {{ $emp->user->name ?? 'Unknown Name' }} - {{ $emp->department->department_name ?? 'N/A' }}
                </option>
            @endforeach
        @else
            <option value="" disabled>No Employees Found</option>
        @endif
    </select>
</div>

          <div class="form-group">
            <label for="kpiType">KPI Type <span>*</span></label>
            <select id="kpiType" name="kpiType" required>
              <option value="" disabled selected>Select Type</option>
              <option value="Quantitative">Quantitative (Numeric)</option>
              <option value="Qualitative">Qualitative (Descriptive)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="targetValue">Target Value <span>*</span></label>
            <input type="number" step="0.01" id="targetValue" name="targetValue" placeholder="e.g., 90" required>
            <small style="color:#666; font-size:12px;">Enter numeric value (e.g., 100 for 100%)</small>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label for="startDate">Start Date</label>
              <input type="date" id="startDate" name="startDate" required>
            </div>
            <div class="form-group half">
              <label for="endDate">End Date</label>
              <input type="date" id="endDate" name="endDate" required>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-floppy-disk"></i> Save KPI Goal
            </button>
            <a href="{{ route('admin.appraisal') }}" class="btn btn-secondary">
              <i class="fa-solid fa-arrow-left"></i> Back to Appraisal
            </a>
          </div>
        </form>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const assignedTo = document.getElementById('assignedTo');
    const departmentGroup = document.getElementById('departmentGroup');
    const employeeGroup = document.getElementById('employeeGroup');
    const departmentSelect = document.getElementById('department');
    const employeeSelect = document.getElementById('employee');

    function toggleAssignFields() {
      if (assignedTo.value === 'Employee') {
        employeeGroup.style.display = 'block';
        departmentGroup.style.display = 'none';
        employeeSelect.required = true;
        departmentSelect.required = false;
      } else if (assignedTo.value === 'Department') {
        departmentGroup.style.display = 'block';
        employeeGroup.style.display = 'none';
        departmentSelect.required = true;
        employeeSelect.required = false;
      } else {
        departmentGroup.style.display = 'none';
        employeeGroup.style.display = 'none';
        departmentSelect.required = false;
        employeeSelect.required = false;
      }
    }

    assignedTo.addEventListener('change', toggleAssignFields);
    toggleAssignFields(); 
  });
  </script>
</body>
</html>