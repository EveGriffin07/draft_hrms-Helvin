<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Employee - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <style>
    /* ✅ CSS FIX: Center all table content vertically */
    .hr-table th, 
    .hr-table td {
      vertical-align: middle !important;
    }
  </style>
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

  <div class="breadcrumb">Home > Appraisal > Choose Employee</div>

  <h2>Select Employee</h2>
  <p class="subtitle">Choose an employee to view their KPI records.</p>

  <div class="table-container">

    <div class="table-actions">
      <input type="text" placeholder="Search employee..." style="padding: 10px; width: 250px; border-radius: 6px; border: 1px solid #ccc;">
    </div>

    <table class="hr-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Department</th>
          <th>Position</th>
          <th>Employee ID</th>
          <th style="text-align: center;">KPI Count</th>
          <th style="text-align: center;">Action</th>
        </tr>
      </thead>

      <tbody>
        {{-- ✅ DYNAMIC EMPLOYEE LIST --}}
        @forelse($employees as $emp)
        <tr>
          <td>
             {{-- Uses User relationship for name --}}
             {{ $emp->user->name ?? 'Unknown Name' }}
          </td>
          <td>{{ $emp->department->department_name ?? 'N/A' }}</td>
          
          {{-- ✅ FIX: Display Position Name properly --}}
          <td>{{ optional($emp->position)->position_name ?? 'N/A' }}</td>

          <td>{{ $emp->employee_id }}</td>
          
          {{-- Uses withCount('employeeKpis') from controller --}}
          <td style="text-align: center;">
             <span class="badge" style="background:#eef2ff; color:#3730a3; padding:4px 8px; border-radius:4px; font-weight:600;">
                 {{ $emp->employee_kpis_count }}
             </span>
          </td>
          
          <td style="text-align: center;">
            {{-- Link passes ID to the next page --}}
            <a href="{{ route('admin.appraisal.employee-kpis', ['emp' => $emp->employee_id]) }}" 
               class="btn btn-primary btn-small"
               style="padding: 6px 12px; font-size: 13px; white-space: nowrap; min-width: 110px; display: inline-flex; justify-content: center; align-items: center;">
              <i class="fa-solid fa-chart-line"></i> &nbsp; View KPI
            </a>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No employees found in the database.</td>
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