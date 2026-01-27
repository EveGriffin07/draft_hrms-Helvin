<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onboarding Management - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>

<style>
.status-badge {
  display:inline-block;
  padding:3px 10px;
  border-radius:999px;
  font-size:12px;
  font-weight:500;
}
.status-in-progress { background:#dbeafe; color:#1d4ed8; }
.status-pending { background:#fee2e2; color:#b91c1c; }
.status-completed { background:#dcfce7; color:#15803d; }
.progress-bar {
  width:100%;
  height:8px;
  border-radius:999px;
  background:#e5e7eb;
  overflow:hidden;
  margin-bottom:4px;
}
.progress-fill {
  height:100%;
  border-radius:999px;
  background:#2563eb;
}
.progress-label {
  font-size:12px;
  color:#4b5563;
}

  </style>

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
    <div class="breadcrumb">Home > Onboarding > Overview</div>
    <h2>Onboarding Management</h2>
    <p class="subtitle">Monitor onboarding progress and manage new employee checklist activities.</p>

    <!-- Summary cards -->
    <div class="summary">
      <div class="card">
        <h3>New Hires</h3>
        <p>6</p>
      </div>
      <div class="card">
        <h3>In Progress</h3>
        <p>3</p>
      </div>
      <div class="card">
        <h3>Completed</h3>
        <p>2</p>
      </div>
      <div class="card">
        <h3>Pending</h3>
        <p>1</p>
      </div>
    </div>

    <!-- Filter bar (simple UI only) -->
    <div class="filter-bar" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:18px 0;">
      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Department</label>
        <select style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
          <option>All Departments</option>
          <option>Finance</option>
          <option>Marketing</option>
          <option>IT</option>
          <option>Sales</option>
          <option>HR</option>
        </select>
      </div>

      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Status</label>
        <select style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
          <option>All Status</option>
          <option>In Progress</option>
          <option>Completed</option>
          <option>Pending</option>
        </select>
      </div>

      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Start From</label>
        <input type="date" style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
      </div>

      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Deadline To</label>
        <input type="date" style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
      </div>

      <div class="filter-group" style="display:flex;align-items:flex-end;">
        <button class="btn btn-primary" style="width:100%;padding:8px 14px;font-size:13px;">
          Apply Filters
        </button>
      </div>
    </div>

    <!-- Employee onboarding table -->
    <h3>Employee Onboarding Progress</h3>
    <div class="training-list">
      <table>
        <thead>
        <tr>
          <th>Employee Name</th>
          <th>Department</th>
          <th>Start Date</th>
          <th>Deadline</th>
          <th>Progress</th>
          <th>Status</th>
          <th>Checklist</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>Nur Aisyah</td>
          <td>Finance</td>
          <td>2025-11-01</td>
          <td>2025-11-10</td>
          <td style="width:220px;">
            <div class="progress-bar">
              <div class="progress-fill" style="width:80%;"></div>
            </div>
            <span class="progress-label">80%</span>
          </td>
          <td>
            <span class="status-badge status-in-progress">In Progress</span>
          </td>
          <td>
    <a href="{{ route('admin.onboarding.checklist') }}" class="btn btn-primary btn-pill">
        View Checklist
    </a>
</td>
        </tr>

        <tr>
          <td>Daniel Tan</td>
          <td>Marketing</td>
          <td>2025-11-03</td>
          <td>2025-11-12</td>
          <td style="width:220px;">
            <div class="progress-bar">
              <div class="progress-fill" style="width:50%;"></div>
            </div>
            <span class="progress-label">50%</span>
          </td>
          <td>
            <span class="status-badge status-pending">Pending</span>
          </td>
          <td>
            <button class="btn btn-primary">View Checklist</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
  </main>
</div>


</body>
</html>
