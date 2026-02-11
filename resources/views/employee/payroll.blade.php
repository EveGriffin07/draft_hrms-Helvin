<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Payroll - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    main { padding:2rem; }
    .breadcrumb { font-size:.85rem; color:#94a3b8; margin-bottom:1rem; }
    h2 { color:#6366f1; margin:0 0 .4rem 0; }
    .subtitle { color:#64748b; margin-bottom:1.2rem; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:14px; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(15,23,42,.06); }
    .label { font-size:12px; color:#94a3b8; text-transform:uppercase; letter-spacing:.02em; }
    .value { font-size:22px; font-weight:700; color:#0f172a; }
    table { width:100%; border-collapse:collapse; }
    thead { background:#0f172a; color:#c4b5fd; }
    th, td { padding:12px 14px; border-bottom:1px solid #e5e7eb; text-align:left; }
    tbody tr:hover { background:#f8fafc; }
    .pill { display:inline-flex; align-items:center; gap:6px; background:#eef2ff; color:#4338ca; border-radius:999px; padding:6px 10px; font-size:.85rem; }
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
      <div class="breadcrumb">Payroll · Payslips & Tax</div>
      <h2>My Payroll</h2>
      <p class="subtitle">Download payslips and review tax withholdings.</p>

      <div class="grid" style="margin-bottom:16px;">
        <div class="card">
          <div class="label">Last Net Pay</div>
          <div class="value">$3,850.00</div>
          <span class="pill"><i class="fa-solid fa-calendar"></i> Jan 31, 2026</span>
        </div>
        <div class="card">
          <div class="label">YTD Gross</div>
          <div class="value">$4,200.00</div>
        </div>
        <div class="card">
          <div class="label">YTD Tax Withheld</div>
          <div class="value">$350.00</div>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="label" style="margin-bottom:8px;">Recent Payslips</div>
        <table>
          <thead>
            <tr><th>Period</th><th>Gross</th><th>Net</th><th>Status</th><th>Action</th></tr>
          </thead>
          <tbody>
            <tr><td>Jan 2026</td><td>$4,200.00</td><td>$3,850.00</td><td>Paid</td><td><a href="#" class="btn btn-secondary btn-small">Download</a></td></tr>
            <tr><td>Dec 2025</td><td>$4,150.00</td><td>$3,810.00</td><td>Paid</td><td><a href="#" class="btn btn-secondary btn-small">Download</a></td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <div class="label" style="margin-bottom:8px;">Tax Documents</div>
        <table>
          <thead>
            <tr><th>Year</th><th>Form</th><th>Status</th><th>Action</th></tr>
          </thead>
          <tbody>
            <tr><td>2025</td><td>Annual Tax Summary</td><td>Available</td><td><a href="#" class="btn btn-secondary btn-small">Download</a></td></tr>
            <tr><td>2024</td><td>Annual Tax Summary</td><td>Available</td><td><a href="#" class="btn btn-secondary btn-small">Download</a></td></tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
