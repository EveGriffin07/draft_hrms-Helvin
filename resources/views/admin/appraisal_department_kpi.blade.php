<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Department KPI Review - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Global HRMS CSS (imports buttons/cards/tables/forms + kpi_employee module) -->
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <!-- Small page-only tweaks to keep it consistent -->
  <style>
    .page-top-actions{
      display:flex;
      justify-content:flex-end;
      gap:10px;
      flex-wrap:wrap;
      margin: 10px 0 18px;
    }

    /* Department KPI details grid (display-only, not a form) */
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

    /* Progress bar */
    .kpi-progress{
      margin-top: 10px;
    }
    .progress-track{
      height: 10px;
      background:#eef2ff;
      border-radius: 999px;
      overflow:hidden;
      border: 1px solid #dbe3ff;
    }
    .progress-fill{
      height:100%;
      width:70%;
      background:#2563eb;
      border-radius: 999px;
    }
    .progress-meta{
      margin-top: 8px;
      display:flex;
      justify-content:space-between;
      gap:10px;
      color:#6b7280;
      font-size: 13px;
    }

    /* Table filters (match the input feel from your other pages) */
    .filter-bar{
      display:flex;
      justify-content:space-between;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
      margin-bottom: 12px;
    }
    .filter-left{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
    }
    .filter-input,
    .filter-select{
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      background:#f9fafb;
      font-size: 14px;
      outline:none;
    }
    .filter-input:focus,
    .filter-select:focus{
      border-color:#2563eb;
      box-shadow: 0 0 0 1px rgba(37,99,235,0.2);
      background:#ffffff;
    }

    /* Extra KPI badge states (so department page has more statuses) */
    .kpi-under-review{
      background:#e0f2fe;
      color:#0369a1;
    }
    .kpi-completed{
      background:#dcfce7;
      color:#166534;
    }

    @media (max-width: 900px){
      .kpi-detail-grid{ grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 560px){
      .kpi-detail-grid{ grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info"><i class="fa-regular fa-bell"></i> &nbsp; HR Admin</div>
  </header>

  <div class="container">
    @include('partials.sidebar')

    <main>
      <div class="breadcrumb">Home > Appraisal > KPI Overview > Department KPI Review</div>

      <h2>Department KPI Review</h2>
      <p class="subtitle">Review department KPI details and track employee KPI review progress.</p>

      <!-- Optional: top actions (consistent button style) -->
      <div class="page-top-actions">
        <a href="{{ route('admin.appraisal') }}" class="btn btn-secondary btn-small">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <a href="#" class="btn btn-primary btn-small">
          <i class="fa-solid fa-file-export"></i> Export
        </a>
      </div>

      <!-- Summary Cards (same component as KPI overview) -->
      <div class="summary">
        <div class="card"><h3>Total Employees</h3><p>12</p></div>
        <div class="card"><h3>Personal KPIs</h3><p>28</p></div>
        <div class="card"><h3>Under Review</h3><p>6</p></div>
        <div class="card"><h3>Completed</h3><p>14</p></div>
      </div>

      <!-- Department KPI Details (use .panel for consistent look) -->
      <div class="panel">
        <div class="panel-header">
          <i class="fa-solid fa-bullseye"></i>
          <h3>Department KPI Details</h3>
        </div>

        <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center;">
          <div style="font-weight:600;color:#111827;">
            IT Department — Develop internal HR portal
          </div>
          <span class="kpi-badge kpi-in-progress">In Progress</span>
        </div>

        <div class="kpi-detail-grid">
          <div class="kpi-detail-item">
            <span class="label">Target</span>
            <span class="value">100%</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">KPI Type</span>
            <span class="value">Quantitative</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Period</span>
            <span class="value">01/01/2025 – 31/12/2025</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Current Progress</span>
            <span class="value">
              70%
              <span class="kpi-badge kpi-under-review">Under Review: 3</span>
            </span>

            <div class="kpi-progress">
              <div class="progress-track" aria-label="Department KPI progress">
                <div class="progress-fill" style="width:70%"></div>
              </div>
              <div class="progress-meta">
                <span>Last updated: 14/12/2025</span>
                <span>Owner: HR Admin</span>
              </div>
            </div>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Overdue Personal KPIs</span>
            <span class="value">1</span>
          </div>

          <div class="kpi-detail-item">
            <span class="label">Notes</span>
            <span class="value" style="font-weight:500;color:#374151;">
              Review each employee KPI and update score/status accordingly.
            </span>
          </div>
        </div>
      </div>

      <!-- Employee Contribution Table (same style as employee list page) -->
      <div class="table-container" style="margin-top:20px;">
        <div class="filter-bar">
          <div class="filter-left">
            <input class="filter-input" type="text" placeholder="Search employee..." style="width:260px;">
            <select class="filter-select">
              <option value="">All Status</option>
              <option>Pending</option>
              <option>In Progress</option>
              <option>Under Review</option>
              <option>Completed</option>
              <option>Overdue</option>
            </select>
          </div>

        

        <table class="hr-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Employee ID</th>
              <th>Position</th>
              <th>KPI Count</th>
              <th>Avg Score</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>John Lim</td>
              <td>EMP0021</td>
              <td>Software Engineer</td>
              <td>3</td>
              <td>–</td>
              <td><span class="kpi-badge kpi-in-progress">In Progress</span></td>
              <td>
                <a href="{{ route('admin.appraisal.employee-kpis') }}" class="btn btn-primary btn-small">
                  <i class="fa-solid fa-chart-line"></i> View KPI
                </a>
              </td>
            </tr>

            <tr>
              <td>Aina Mardhiah</td>
              <td>EMP0107</td>
              <td>System Analyst</td>
              <td>4</td>
              <td>81%</td>
              <td><span class="kpi-badge kpi-under-review">Under Review</span></td>
              <td>
                <a href="{{ route('admin.appraisal.employee-kpis') }}" class="btn btn-primary btn-small">
                  <i class="fa-solid fa-chart-line"></i> View KPI
                </a>
              </td>
            </tr>

            <tr>
              <td>Ravi Kumar</td>
              <td>EMP0144</td>
              <td>QA Engineer</td>
              <td>2</td>
              <td>72%</td>
              <td><span class="kpi-badge kpi-overdue">Overdue</span></td>
              <td>
                <a href="{{ route('admin.appraisal.employee-kpis') }}" class="btn btn-primary btn-small">
                  <i class="fa-solid fa-chart-line"></i> View KPI
                </a>
              </td>
            </tr>

            <tr>
              <td>Siti Farhana</td>
              <td>EMP0202</td>
              <td>UI/UX Designer</td>
              <td>3</td>
              <td>90%</td>
              <td><span class="kpi-badge kpi-completed">Completed</span></td>
              <td>
                <a href="{{ route('admin.appraisal.employee-kpis') }}" class="btn btn-primary btn-small">
                  <i class="fa-solid fa-chart-line"></i> View KPI
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  {{-- Sidebar dropdown script (same as your other pages) --}}
  
</body>
</html>
