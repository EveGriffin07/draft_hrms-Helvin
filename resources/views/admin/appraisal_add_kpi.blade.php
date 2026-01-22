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
    <div class="user-info"><i class="fa-regular fa-bell"></i> &nbsp; HR Admin</div>
  </header>

  <div class="container">
    
    @include('partials.sidebar')

    <main>
      <div class="breadcrumb">Home > Appraisal > Add KPI Goals</div>
      <h2>Add KPI Goals</h2>
      <p class="subtitle">Define measurable performance objectives for departments or employees.</p>

      <div class="form-container">
        <form action="#" method="POST" class="form-card">
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
              <option value="Human Resources">Human Resources</option>
              <option value="Finance">Finance</option>
              <option value="IT">Information Technology</option>
              <option value="Sales">Sales</option>
              <option value="Marketing">Marketing</option>
            </select>
          </div>

          {{-- EMPLOYEE KPI TARGET (shown when "Specific Employee" is selected) --}}
          <div class="form-group" id="employeeGroup" style="display:none;">
            <label for="employee">Employee <span>*</span></label>
            <select id="employee" name="employee">
              <option value="" disabled selected>Select Employee</option>
              {{-- Static examples for UI only, can be replaced with dynamic data later --}}
              <option value="EMP0021">John Lim (IT)</option>
              <option value="EMP0134">Nur Aisyah (Finance)</option>
              <option value="EMP0099">Zainal Abidin (Human Resources)</option>
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
            <input type="text" id="targetValue" name="targetValue" placeholder="e.g., 90% project completion" required>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label for="startDate">Start Date</label>
              <input type="date" id="startDate" name="startDate">
            </div>
            <div class="form-group half">
              <label for="endDate">End Date</label>
              <input type="date" id="endDate" name="endDate">
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
    // Assigned To toggle: Department vs Specific Employee
    const assignedTo = document.getElementById('assignedTo');
    const departmentGroup = document.getElementById('departmentGroup');
    const employeeGroup = document.getElementById('employeeGroup');
    const departmentSelect = document.getElementById('department');
    const employeeSelect = document.getElementById('employee');

    function toggleAssignFields() {
      if (assignedTo.value === 'Employee') {
        // KPI for specific employee
        employeeGroup.style.display = 'block';
        departmentGroup.style.display = 'none';

        employeeSelect.required = true;
        departmentSelect.required = false;
      } else if (assignedTo.value === 'Department') {
        // KPI for department
        departmentGroup.style.display = 'block';
        employeeGroup.style.display = 'none';

        departmentSelect.required = true;
        employeeSelect.required = false;
      } else {
        // Nothing selected yet
        departmentGroup.style.display = 'none';
        employeeGroup.style.display = 'none';

        departmentSelect.required = false;
        employeeSelect.required = false;
      }
    }

    assignedTo.addEventListener('change', toggleAssignFields);
    toggleAssignFields(); // run on page load
  });
  </script>
</body>
</html>
