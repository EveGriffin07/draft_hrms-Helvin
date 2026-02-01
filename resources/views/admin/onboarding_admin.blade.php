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
.status-badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:500; }
.status-in-progress { background:#dbeafe; color:#1d4ed8; }
.status-pending { background:#fee2e2; color:#b91c1c; }
.status-completed { background:#dcfce7; color:#15803d; }
.progress-bar { width:100%; height:8px; border-radius:999px; background:#e5e7eb; overflow:hidden; margin-bottom:4px; }
.progress-fill { height:100%; border-radius:999px; background:#2563eb; }
.progress-label { font-size:12px; color:#4b5563; }
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

    <div class="summary">
      <div class="card">
        <h3>New Hires</h3>
        <p>{{ $stats['total'] }}</p>
      </div>
      <div class="card">
        <h3>In Progress</h3>
        <p>{{ $stats['in_progress'] }}</p>
      </div>
      <div class="card">
        <h3>Completed</h3>
        <p>{{ $stats['completed'] }}</p>
      </div>
      <div class="card">
        <h3>Pending</h3>
        <p>{{ $stats['pending'] }}</p>
      </div>
    </div>

    <form method="GET" action="{{ route('admin.onboarding') }}" class="filter-bar" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:18px 0;">
      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Department</label>
        <select name="department" style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
          <option>All Departments</option>
          <option>Finance</option>
          <option>Marketing</option>
          <option>Information Technology</option>
          <option>Sales</option>
          <option>Human Resources</option>
          <option>General</option>
        </select>
      </div>

      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Status</label>
        <select name="status" style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
          <option>All Status</option>
          <option value="In Progress">In Progress</option>
          <option value="Completed">Completed</option>
          <option value="Pending">Pending</option>
        </select>
      </div>

      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Start From</label>
        <input type="date" name="start_date" style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
      </div>

      <div class="filter-group">
        <label style="font-size:12px;color:#4b5563;">Deadline To</label>
        <input type="date" name="end_date" style="width:100%;border-radius:8px;border:1px solid #d1d5db;padding:7px 10px;font-size:13px;">
      </div>

      <div class="filter-group" style="display:flex;align-items:flex-end;">
        <button type="submit" class="btn btn-primary" style="width:100%;padding:8px 14px;font-size:13px;">
          Apply Filters
        </button>
      </div>
    </form>

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
        @forelse($onboardings as $onboarding)
        <tr>
          <td>{{ $onboarding->employee->user->name ?? 'N/A' }}</td>
          <td>{{ $onboarding->employee->department->department_name ?? 'N/A' }}</td>
          <td>{{ $onboarding->start_date }}</td>
          <td>{{ $onboarding->end_date }}</td>
          <td style="width:220px;">
            <div class="progress-bar">
              <div class="progress-fill" style="width:{{ $onboarding->progress }}%;"></div>
            </div>
            <span class="progress-label">{{ $onboarding->progress }}%</span>
          </td>
          <td>
  @if($onboarding->status == 'in_progress')
      <span class="status-badge status-in-progress">In Progress</span>
  @elseif($onboarding->status == 'completed')
      <span class="status-badge status-completed">Completed</span>
  @else
      <span class="status-badge status-pending">Pending</span>
  @endif
</td>
          <td>
            <a href="{{ route('admin.onboarding.checklist.show', $onboarding->onboarding_id) }}" class="btn btn-primary btn-pill">
                View Checklist
            </a>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;">No onboarding records found.</td>
        </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
  </main>
</div>
</body>
</html>