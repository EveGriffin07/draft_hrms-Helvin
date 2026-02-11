<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Attendance - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    main { padding: 2rem; }
    .breadcrumb { font-size:.85rem; color:#94a3b8; margin-bottom:1rem; }
    h2 { color:#0ea5e9; margin:0 0 .4rem 0; }
    .subtitle { color:#64748b; margin-bottom:1.2rem; }
    .card-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:14px; margin-bottom:18px; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(15,23,42,.06); }
    .card .label { font-size:12px; color:#94a3b8; text-transform:uppercase; letter-spacing:.02em; }
    .card .value { font-size:22px; font-weight:700; color:#0f172a; }
    table { width:100%; border-collapse:collapse; }
    thead { background:#0f172a; color:#38bdf8; }
    th, td { padding:12px 14px; border-bottom:1px solid #e5e7eb; text-align:left; }
    tbody tr:hover { background:#f8fafc; }
    .status { padding:4px 10px; border-radius:999px; font-size:.85rem; }
    .present { background:#dcfce7; color:#166534; }
    .late { background:#fef9c3; color:#854d0e; }
    .absent { background:#fee2e2; color:#991b1b; }
    .chips { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; }
    .chip { background:#e0f2fe; color:#0369a1; padding:6px 10px; border-radius:999px; font-size:.9rem; }
  </style>
</head>
<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
      <span><i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name ?? 'Employee' }}</span>
    </div>
  </header>

  <div class="container">
    @include('employee.layout.sidebar')

    <main>
      <div class="breadcrumb">Attendance · My Log & Overtime</div>
      <h2>My Attendance</h2>
      <p class="subtitle">Quick view of today’s status, this week’s summary, and recent logs.</p>

      <div class="card-grid">
        <div class="card">
          <div class="label">Today</div>
          <div class="value">Present</div>
          <div class="chips"><span class="chip">In: 09:05</span><span class="chip">Out: 18:05</span></div>
        </div>
        <div class="card">
          <div class="label">Late Arrivals (30d)</div>
          <div class="value">2</div>
        </div>
        <div class="card">
          <div class="label">Overtime Hours (30d)</div>
          <div class="value">6.5h</div>
        </div>
        <div class="card">
          <div class="label">Absences (30d)</div>
          <div class="value">0</div>
        </div>
      </div>

      <div class="card" style="margin-bottom:14px;">
        <div class="label" style="margin-bottom:8px;">Recent Attendance</div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>In</th>
                <th>Out</th>
                <th>Status</th>
                <th>Overtime</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2026-02-11</td><td>09:05</td><td>18:05</td><td><span class="status present">Present</span></td><td>1h</td>
              </tr>
              <tr>
                <td>2026-02-10</td><td>09:12</td><td>18:00</td><td><span class="status late">Late</span></td><td>0h</td>
              </tr>
              <tr>
                <td>2026-02-09</td><td>09:00</td><td>18:30</td><td><span class="status present">Present</span></td><td>1.5h</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <div class="label" style="margin-bottom:8px;">Overtime Requests (sample)</div>
        <table>
          <thead>
            <tr><th>Date</th><th>Hours</th><th>Status</th><th>Note</th></tr>
          </thead>
          <tbody>
            <tr><td>2026-02-05</td><td>2.0</td><td><span class="status present">Approved</span></td><td>Project release</td></tr>
            <tr><td>2026-01-29</td><td>1.5</td><td><span class="status late">Pending</span></td><td>Client call</td></tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
