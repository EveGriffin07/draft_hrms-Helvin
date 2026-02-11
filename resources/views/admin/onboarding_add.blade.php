<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Onboarding - HRMS</title>
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
    <div class="breadcrumb">Home > Onboarding > Add New Onboarding</div>
    <h2>Add New Onboarding</h2>
    <p class="subtitle">Register a new employee onboarding process and assign tasks or checklists.</p>

    <div class="form-container">
      <form action="{{ route('admin.onboarding.store') }}" method="POST" class="form-card">
        @csrf
        <h3><i class="fa-solid fa-user-plus"></i> Employee Information</h3>

        <div class="form-group">
          <label for="employee_id">Employee Name <span>*</span></label>
          <select id="employee_id" name="employee_id" required onchange="updateEmployeeDetails()">
            <option value="" disabled selected>Select an Employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->employee_id }}" 
                        data-dept="{{ $emp->department->department_name ?? 'N/A' }}"
                        data-role="{{ $emp->position->position_name ?? 'N/A' }}">
                    {{ $emp->user->name ?? 'Unknown' }} ({{ $emp->employee_code }})
                </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="department">Department</label>
          <input type="text" id="department" name="department" placeholder="Auto-filled" readonly style="background:#f3f4f6;">
        </div>

        <div class="form-group">
          <label for="assignedRole">Assigned Role</label>
          <input type="text" id="assignedRole" name="assignedRole" placeholder="Auto-filled" readonly style="background:#f3f4f6;">
        </div>

        <div class="form-group">
          <label for="manager">Reporting Manager</label>
          <input type="text" id="manager" name="manager" placeholder="Enter Manager Name (Optional)">
        </div>

        <div class="form-row">
          <div class="form-group half">
            <label for="startDate">Start Date <span>*</span></label>
            <input type="date" id="startDate" name="startDate" required>
          </div>
          <div class="form-group half">
            <label for="deadline">Deadline <span>*</span></label>
            <input type="date" id="deadline" name="deadline" required>
          </div>
        </div>

        <h3 style="margin-top:24px;"><i class="fa-solid fa-list-check"></i> Welcome Checklist</h3>
        <p class="subtitle" style="margin-bottom:10px;">
          Select the tasks that should be completed during the onboarding period.
        </p>

        <div class="onboarding-tasks-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:8px 24px;margin-bottom:16px;">
          <label class="checkbox-inline">
            <input type="checkbox" name="default_tasks[]" value="documents" checked>
            <span>Submit required documents</span>
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="default_tasks[]" value="orientation" checked>
            <span>Attend company orientation</span>
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="default_tasks[]" value="system">
            <span>Setup system credentials</span>
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="default_tasks[]" value="buddy">
            <span>Meet assigned buddy / mentor</span>
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="default_tasks[]" value="policies">
            <span>Review and acknowledge HR policies</span>
          </label>
        </div>

        <div class="form-group full-width">
          <label for="customTask">Additional Notes / Custom Task (Optional)</label>
          <textarea id="customTask" name="customTask" rows="3"
                    placeholder="e.g., Attend department briefing with Finance Manager on first week."></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> Save Onboarding
          </button>
          <a href="{{ route('admin.onboarding') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Onboarding
          </a>
        </div>
      </form>
    </div>

    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
  </main>
</div>

<script>
function updateEmployeeDetails() {
    var select = document.getElementById('employee_id');
    var option = select.options[select.selectedIndex];
    
    // Get data from data-attributes
    var dept = option.getAttribute('data-dept');
    var role = option.getAttribute('data-role');
    
    document.getElementById('department').value = dept;
    document.getElementById('assignedRole').value = role;
}
</script>

</body>
</html>