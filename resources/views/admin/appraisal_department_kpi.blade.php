<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Department KPI Review - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <style>
    /* existing styles */
    .kpi-detail-grid{
      display:grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
      margin-top: 14px;
    }
    .kpi-detail-item{
      background:#ffffff;
      border:1px solid #e5e7eb;
      border-radius: 10px;
      padding: 12px 14px;
    }
    .kpi-detail-item .label{
      display:block;
      font-size: 12px;
      color:#6b7280;
      margin-bottom: 6px;
      font-weight: 500;
    }
    .kpi-detail-item .value{
      font-size: 14px;
      color:#111827;
      font-weight: 600;
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
    }
    .kpi-progress{ margin-top: 10px; }
    .progress-track{
      height: 10px;
      background:#eef2ff;
      border-radius: 999px;
      overflow:hidden;
      border: 1px solid #dbe3ff;
    }
    .progress-fill{
      height:100%;
      background:#2563eb;
      border-radius: 999px;
    }

    /* ✅ NEW STYLES: Center content vertically in the table */
    .hr-table th, 
    .hr-table td {
      vertical-align: middle !important; /* Centers text up/down */
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
      <div class="breadcrumb">Home > Appraisal > KPI Overview > Department KPI Review</div>

      <h2>Department KPI Review</h2>
      <p class="subtitle">Review department KPI details and track progress.</p>

      <div class="page-top-actions" style="margin-bottom: 15px; display:flex; justify-content: flex-end;">
        <a href="{{ route('admin.appraisal') }}" class="btn btn-secondary btn-small">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
      </div>

      <div class="panel" style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
        <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
          <div style="display:flex; gap:10px; align-items:center;">
             <i class="fa-solid fa-bullseye" style="color:#2563eb;"></i>
             <h3 style="margin:0;">{{ $deptKpi->department->department_name ?? 'Department' }} — {{ $deptKpi->template->kpi_title }}</h3>
          </div>
          <span class="kpi-badge kpi-active" style="background:#dbeafe; color:#1e40af; padding:4px 8px; border-radius:4px; font-size:12px;">{{ ucfirst($deptKpi->status) }}</span>
        </div>

        <div class="kpi-detail-grid">
          <div class="kpi-detail-item">
            <span class="label">Target</span>
            <span class="value">{{ $deptKpi->target }}</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">KPI Type</span>
            <span class="value">{{ $deptKpi->template->kpi_type }}</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Period</span>
            <span class="value">{{ $deptKpi->period_start }} – {{ $deptKpi->period_end }}</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Current Progress</span>
            <span class="value">
              {{ $deptKpi->progress }}%
            </span>

            <div class="kpi-progress">
              <div class="progress-track">
                <div class="progress-fill" style="width: {{ min($deptKpi->progress, 100) }}%"></div>
              </div>
            </div>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Created By</span>
            <span class="value">{{ $deptKpi->creator->name ?? 'Admin' }}</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Description</span>
            <span class="value" style="font-weight:500;color:#374151; font-size: 13px;">
              {{ $deptKpi->template->kpi_description }}
            </span>
          </div>
        </div>
      </div>

      <h3 style="margin-top: 30px;">Employees in {{ $deptKpi->department->department_name ?? 'Department' }}</h3>
      <div class="table-container">
        
        <table class="hr-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Employee ID</th>
              <th>Position</th>
              <th style="text-align: center;">Personal KPIs</th>
              <th style="text-align: center;">Action</th>
            </tr>
          </thead>

          <tbody>
            @forelse($employees as $emp)
            <tr>
              <td>{{ $emp->user->name ?? 'Unknown' }}</td>
              <td>{{ $emp->employee_id }}</td>
              
              {{-- ✅ FIX 1: Clean Position Name (removes the JSON code) --}}
              <td>{{ $emp->position->position_name ?? 'N/A' }}</td>
              
              {{-- Center the count --}}
              <td style="text-align: center;">{{ $emp->employee_kpis_count }}</td>

              {{-- ✅ FIX 2 & 3: Centered Button + Fixed Size --}}
              <td style="text-align: center;">
                <a href="{{ route('admin.appraisal.employee-kpis', ['emp' => $emp->employee_id]) }}" 
                   class="btn btn-primary btn-small"
                   style="padding: 6px 12px; font-size: 13px; white-space: nowrap; min-width: 120px; display: inline-flex; justify-content: center; align-items: center;">
                  <i class="fa-solid fa-chart-line"></i> &nbsp; View KPIs
                </a>
              </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:15px;">No employees found in this department.</td>
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