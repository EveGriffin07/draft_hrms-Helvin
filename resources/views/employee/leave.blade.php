<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Leave - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    main { padding:2rem; }
    .breadcrumb { font-size:.85rem; color:#94a3b8; margin-bottom:1rem; }
    h2 { color:#22c55e; margin:0 0 .4rem 0; }
    .subtitle { color:#64748b; margin-bottom:1.2rem; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:14px; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(15,23,42,.06); }
    .label { font-size:12px; color:#94a3b8; text-transform:uppercase; letter-spacing:.02em; }
    .value { font-size:24px; font-weight:700; color:#0f172a; }
    table { width:100%; border-collapse:collapse; }
    thead { background:#0f172a; color:#a3e635; }
    th, td { padding:12px 14px; border-bottom:1px solid #e5e7eb; text-align:left; }
    tbody tr:hover { background:#f8fafc; }
    .status { padding:4px 10px; border-radius:999px; font-size:.85rem; }
    .approved { background:#dcfce7; color:#166534; }
    .pending { background:#fef9c3; color:#854d0e; }
    .rejected { background:#fee2e2; color:#991b1b; }
    form { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px; }
    form textarea { grid-column:1 / -1; }
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
      <div class="breadcrumb">Leave · Apply / Balance / History</div>
      <h2>My Leave</h2>
      <p class="subtitle">Submit a new request, review balances, and track approvals.</p>

      <div class="grid" style="margin-bottom:16px;">
        <div class="card">
          <div class="label">Annual Leave Balance</div>
          <div class="value">8 days</div>
        </div>
        <div class="card">
          <div class="label">Sick Leave Balance</div>
          <div class="value">5 days</div>
        </div>
        <div class="card">
          <div class="label">Pending Requests</div>
          <div class="value">1</div>
        </div>
      </div>

      <div class="card" style="margin-bottom:16px;">
        <div class="label" style="margin-bottom:8px;">Apply for Leave</div>
        <form>
          <div>
            <label>Type</label>
            <select>
              <option>Annual Leave</option>
              <option>Sick Leave</option>
              <option>Unpaid Leave</option>
            </select>
          </div>
          <div>
            <label>Start Date</label>
            <input type="date">
          </div>
          <div>
            <label>End Date</label>
            <input type="date">
          </div>
          <div>
            <label>Total Days</label>
            <input type="number" min="0" step="0.5" value="1">
          </div>
          <textarea rows="3" placeholder="Reason / Notes"></textarea>
          <div style="grid-column:1 / -1;">
            <button type="button" class="btn btn-primary btn-small"><i class="fa-solid fa-paper-plane"></i> Submit Request</button>
          </div>
        </form>
      </div>

      <div class="card">
        <div class="label" style="margin-bottom:8px;">Recent Requests</div>
        <table>
          <thead>
            <tr><th>Date Range</th><th>Type</th><th>Days</th><th>Status</th><th>Note</th></tr>
          </thead>
          <tbody>
            <tr><td>2026-02-20 → 2026-02-22</td><td>Annual</td><td>3</td><td><span class="status pending">Pending</span></td><td>Family travel</td></tr>
            <tr><td>2026-01-15 → 2026-01-16</td><td>Sick</td><td>2</td><td><span class="status approved">Approved</span></td><td>Flu</td></tr>
            <tr><td>2025-12-05 → 2025-12-05</td><td>Unpaid</td><td>1</td><td><span class="status rejected">Rejected</span></td><td>Insufficient balance</td></tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
