<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Penalty Removal & Tracking - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    /* Page-specific: keep white cards/tables for readability */
    main { padding: 2rem; }
    .breadcrumb { font-size:.85rem; color:#94a3b8; margin-bottom:1rem; }
    h2 { color:#38bdf8; margin:0 0 .25rem 0; }
    .subtitle { color:#94a3b8; margin-bottom:1.5rem; }

    .summary, .filters, .table-wrap { background:#fff; color:#111827; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.08); }
    .summary { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; padding:16px; margin-bottom:16px; }
    .summary .card { border:1px solid #edf2f7; border-radius:10px; text-align:center; padding:16px; }
    .summary .card h3 { font-size:.95rem; color:#6b7280; margin:0 0 6px; }
    .summary .card p { font-size:1.4rem; font-weight:600; color:#111827; margin:0; }

    .filters { padding:16px; margin-bottom:16px; }
    .filters .row { display:flex; gap:12px; flex-wrap:wrap; }
    .filters .split { flex:1 1 240px; }
    .filters label { display:block; font-size:.85rem; color:#6b7280; margin-bottom:6px; }
    .filters input, .filters select, .filters button {
      border:1px solid #d1d5db; background:#fff; color:#111827;
      border-radius:8px; padding:8px 10px; font-size:.92rem;
    }
    .filters .btn { cursor:pointer; }
    .filters .btn-primary { background:#38bdf8; border-color:#38bdf8; color:#0f172a; }
    .filters .btn-ghost { background:#fff; color:#111827; }

    .table-wrap { overflow:hidden; border:1px solid #e5e7eb; }
    table { width:100%; border-collapse:collapse; }
    thead { background:#0f172a; color:#38bdf8; }
    th, td { padding:12px 14px; border-bottom:1px solid #e5e7eb; text-align:left; }
    tbody tr:hover { background:#f8fafc; }

    .status { padding:4px 8px; border-radius:999px; font-size:.8rem; white-space:nowrap; }
    .pending  { background:#fef3c7; color:#92400e; }
    .approved { background:#dcfce7; color:#166534; }
    .rejected { background:#fee2e2; color:#991b1b; }

    .points { font-weight:600; color:#0f172a; }

    .btn-xs { padding:6px 10px; font-size:.85rem; border-radius:8px; border:1px solid #d1d5db; background:#fff; cursor:pointer; }
    .btn-approve { background:#22c55e; border-color:#22c55e; color:#fff; }
    .btn-reject  { background:#ef4444; border-color:#ef4444; color:#fff; }
    .btn-disabled { opacity:.5; cursor:not-allowed; }

    footer { text-align:center; color:#64748b; font-size:.8rem; padding:22px 0 0; }

    /* Mini modal confirm */
    .backdrop { position:fixed; inset:0; background:rgba(15,23,42,.55); display:none; align-items:center; justify-content:center; z-index:50; }
    .dialog { width:min(480px, 92vw); background:#fff; color:#111827; border-radius:14px; box-shadow:0 10px 30px rgba(0,0,0,.35); overflow:hidden; }
    .dialog header { padding:12px 16px; background:#0f172a; color:#e2e8f0; font-weight:600; }
    .dialog .body { padding:16px; }
    .dialog .actions { display:flex; gap:8px; justify-content:flex-end; padding:12px 16px; border-top:1px solid #e5e7eb; }
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
      <div class="breadcrumb">Home > Attendance > Penalty Tracking</div>
      <h2>Penalty Removal & Tracking</h2>
      <p class="subtitle">Approve or reject attendance-related penalties and filter the records by employee, department, reason, status, or date.</p>

      <!-- DIFFERENT STATS from Attendance Tracking -->
      <section class="summary" id="summary">
        <div class="card"><h3>Total Penalties</h3><p id="s-total">0</p></div>
        <div class="card"><h3>Pending</h3><p id="s-pending">0</p></div>
        <div class="card"><h3>Approved</h3><p id="s-approved">0</p></div>
        <div class="card"><h3>Rejected</h3><p id="s-rejected">0</p></div>
      </section>

      <!-- Filters -->
      <section class="filters">
        <div class="row">
          <div class="split">
            <label for="q">Search (Name/ID)</label>
            <input id="q" type="text" placeholder="e.g., EMP007 or Sarah Lee">
          </div>
          <div class="split">
            <label for="dept">Department</label>
            <select id="dept">
              <option value="">All</option>
              <option value="IT">IT</option>
              <option value="HR">HR</option>
              <option value="Finance">Finance</option>
              <option value="Marketing">Marketing</option>
              <option value="Sales">Sales</option>
            </select>
          </div>
          <div class="split">
            <label for="reason">Reason</label>
            <select id="reason">
              <option value="">Any</option>
              <option value="Late">Late</option>
              <option value="Absent">Absent</option>
              <option value="Early Checkout">Early Checkout</option>
              <option value="No Clock-in">No Clock-in</option>
            </select>
          </div>
          <div class="split">
            <label for="status">Status</label>
            <select id="status">
              <option value="">Any</option>
              <option value="Pending">Pending</option>
              <option value="Approved">Approved</option>
              <option value="Rejected">Rejected</option>
            </select>
          </div>
          <div class="split">
            <label for="start">Start Date</label>
            <input type="date" id="start">
          </div>
          <div class="split">
            <label for="end">End Date</label>
            <input type="date" id="end">
          </div>
          <div class="split" style="align-self:end;">
            <button class="btn btn-primary" id="apply"><i class="fa-solid fa-filter"></i> Apply</button>
            <button class="btn btn-ghost" id="clear">Clear</button>
          </div>
        </div>
      </section>

      <!-- Table -->
      <section class="table-wrap">
        <table id="penaltyTable">
          <thead>
            <tr>
              <th>Penalty ID</th>
              <th>Employee</th>
              <th>Department</th>
              <th>Reason</th>
              <th>Points</th>
              <th>Date</th>
              <th>Status</th>
              <th style="width:180px;">Action</th>
            </tr>
          </thead>
          <tbody><!-- JS fills --></tbody>
        </table>
      </section>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <!-- Confirm dialog -->
  <div class="backdrop" id="confirm">
    <div class="dialog">
      <header id="confirmTitle">Confirm Action</header>
      <div class="body" id="confirmBody">Are you sure?</div>
      <div class="actions">
        <button class="btn btn-ghost" id="cancelAction">Cancel</button>
        <button class="btn btn-primary" id="proceedAction">Proceed</button>
      </div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  /* ========= Sidebar: single active, single open, persistence ========= */
  const groups  = document.querySelectorAll('.sidebar-group');
  const toggles = document.querySelectorAll('.sidebar-toggle');
  const links   = document.querySelectorAll('.submenu a');
  const STORAGE_KEY = 'hrms_sidebar_open_group';

  const normPath = (u) => {
    const url = new URL(u, location.origin);
    let p = url.pathname
      .replace(/\/index\.php$/i, '')
      .replace(/\/index\.php\//i, '/')
      .replace(/\/+$/, '');
    return p === '' ? '/' : p;
  };
  const here = normPath(location.href);

  // Let JS own the active/open state to avoid double-highlights
  groups.forEach(g => {
    g.classList.remove('open');
    const t = g.querySelector('.sidebar-toggle');
    if (t) t.setAttribute('aria-expanded','false');
  });
  links.forEach(a => a.classList.remove('active'));

  // Choose exactly one active link
  let activeLink = null;
  for (const a of links) {
    if (normPath(a.href) === here) { activeLink = a; break; }
  }
  if (!activeLink) {
    for (const a of links) {
      const p = normPath(a.href);
      if (p !== '/' && here.startsWith(p)) { activeLink = a; break; }
    }
  }

  let openedByActive = false;
  if (activeLink) {
    activeLink.classList.add('active');
    const g = activeLink.closest('.sidebar-group');
    if (g) {
      g.classList.add('open');
      const t = g.querySelector('.sidebar-toggle');
      if (t) t.setAttribute('aria-expanded','true');
      openedByActive = true;
      const idx = Array.from(groups).indexOf(g);
      if (idx >= 0) localStorage.setItem(STORAGE_KEY, String(idx));
    }
  }

  if (!openedByActive) {
    const idx = localStorage.getItem(STORAGE_KEY);
    if (idx !== null && groups[idx]) {
      groups[idx].classList.add('open');
      const t = groups[idx].querySelector('.sidebar-toggle');
      if (t) t.setAttribute('aria-expanded','true');
    } else if (groups[0]) {
      groups[0].classList.add('open');
      const t0 = groups[0].querySelector('.sidebar-toggle');
      if (t0) t0.setAttribute('aria-expanded','true');
    }
  }

  toggles.forEach((btn, i) => {
    btn.setAttribute('role','button');
    btn.setAttribute('tabindex','0');

    const doToggle = (e) => {
      e.preventDefault();
      const group = btn.closest('.sidebar-group');
      const isOpen = group.classList.contains('open');

      groups.forEach(g => {
        g.classList.remove('open');
        const t = g.querySelector('.sidebar-toggle');
        if (t) t.setAttribute('aria-expanded','false');
      });

      if (!isOpen) {
        group.classList.add('open');
        btn.setAttribute('aria-expanded','true');
        localStorage.setItem(STORAGE_KEY, String(i));
      } else {
        btn.setAttribute('aria-expanded','false');
        localStorage.removeItem(STORAGE_KEY);
      }
    };

    btn.addEventListener('click', doToggle);
    btn.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') doToggle(e);
    });
  });

  /* ================== Penalty Removal & Tracking logic ================== */
  // penalty: {pid, id, name, dept, reason, points, date, status, tag}
  let PENALTIES = [
    {pid:'P-1021', id:'EMP007', name:'Sarah Lee',   dept:'Marketing', reason:'3x Late',       points:3, date:'2025-11-02', status:'Pending',  tag:'Late'},
    {pid:'P-1022', id:'EMP010', name:'Daniel Ong',  dept:'Finance',   reason:'2x Absent',     points:4, date:'2025-10-28', status:'Approved', tag:'Absent'},
    {pid:'P-1023', id:'EMP001', name:'John Tan',    dept:'IT',        reason:'Early Checkout',points:1, date:'2025-11-01', status:'Pending',  tag:'Early Checkout'},
    {pid:'P-1024', id:'EMP003', name:'Marcus Lim',  dept:'HR',        reason:'No Clock-in',   points:2, date:'2025-10-29', status:'Rejected', tag:'No Clock-in'},
    {pid:'P-1025', id:'EMP004', name:'Chen Wei',    dept:'Marketing', reason:'Late',          points:1, date:'2025-11-03', status:'Pending',  tag:'Late'},
    {pid:'P-1026', id:'EMP002', name:'Alicia Wong', dept:'Finance',   reason:'Late',          points:1, date:'2025-11-04', status:'Approved', tag:'Late'},
    {pid:'P-1027', id:'EMP005', name:'Haziq Noor',  dept:'Sales',     reason:'Absent',        points:2, date:'2025-11-05', status:'Pending',  tag:'Absent'},
  ];

  const $ = s => document.querySelector(s);
  const tbody = document.querySelector('#penaltyTable tbody');

  function isBetween(d, start, end) {
    const x = new Date(d);
    if (start) { const s = new Date(start); s.setHours(0,0,0,0); if (x < s) return false; }
    if (end)   { const e = new Date(end);   e.setHours(23,59,59,999); if (x > e) return false; }
    return true;
  }

  function updateStats(rows) {
    const total = rows.length;
    const pending = rows.filter(r => r.status === 'Pending').length;
    const approved = rows.filter(r => r.status === 'Approved').length;
    const rejected = rows.filter(r => r.status === 'Rejected').length;
    $('#s-total').textContent    = total;
    $('#s-pending').textContent  = pending;
    $('#s-approved').textContent = approved;
    $('#s-rejected').textContent = rejected;
  }

  function wireActions() {
    document.querySelectorAll('.btn-approve').forEach(btn => {
      btn.addEventListener('click', () => {
        const pid = btn.getAttribute('data-pid');
        const row = PENALTIES.find(p => p.pid === pid);
        if (!row || row.status !== 'Pending') return;
        openConfirm(pid, 'approve', row);
      });
    });

    document.querySelectorAll('.btn-reject').forEach(btn => {
      btn.addEventListener('click', () => {
        const pid = btn.getAttribute('data-pid');
        const row = PENALTIES.find(p => p.pid === pid);
        if (!row || row.status !== 'Pending') return;
        openConfirm(pid, 'reject', row);
      });
    });
  }

  function render(rows) {
    tbody.innerHTML = '';
    if (!rows.length) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 8; td.textContent = 'No penalties match the current filters.';
      tr.appendChild(td); tbody.appendChild(tr);
      updateStats(rows);
      return;
    }

    rows.forEach(r => {
      const tr = document.createElement('tr');
      const approveDisabled = r.status !== 'Pending' ? 'disabled' : '';
      const rejectDisabled  = r.status !== 'Pending' ? 'disabled' : '';
      const approveClasses  = `btn-xs btn-approve ${approveDisabled ? 'btn-disabled' : ''}`;
      const rejectClasses   = `btn-xs btn-reject ${rejectDisabled ? 'btn-disabled' : ''}`;

      tr.innerHTML = `
        <td>${r.pid}</td>
        <td><strong>${r.name}</strong><br><span style="color:#6b7280;">${r.id}</span></td>
        <td>${r.dept}</td>
        <td>${r.reason}</td>
        <td class="points">${r.points}</td>
        <td>${r.date}</td>
        <td><span class="status ${r.status.toLowerCase()}">${r.status}</span></td>
        <td>
          <button class="${approveClasses}" data-pid="${r.pid}">
            <i class="fa-solid fa-check"></i> Approve
          </button>
          <button class="${rejectClasses}" data-pid="${r.pid}">
            <i class="fa-solid fa-xmark"></i> Reject
          </button>
        </td>
      `;
      tbody.appendChild(tr);
    });

    updateStats(rows);
    wireActions();
  }

  function applyFilters() {
    const q = $('#q').value.trim().toLowerCase();
    const dept = $('#dept').value;
    const reason = $('#reason').value;
    const status = $('#status').value;
    const start = $('#start').value;
    const end = $('#end').value;

    const rows = PENALTIES
      .filter(r => {
        const qmatch = !q || r.id.toLowerCase().includes(q) || r.name.toLowerCase().includes(q);
        const dmatch = !dept || r.dept === dept;
        const rmatch = !reason || r.tag === reason || r.reason.toLowerCase().includes(reason.toLowerCase());
        const smatch = !status || r.status === status;
        const tmatch = isBetween(r.date, start, end);
        return qmatch && dmatch && rmatch && smatch && tmatch;
      })
      .sort((a,b)=> b.date.localeCompare(a.date) || a.pid.localeCompare(b.pid));

    render(rows);
  }

  $('#apply').addEventListener('click', applyFilters);
  $('#clear').addEventListener('click', () => {
    $('#q').value = '';
    $('#dept').value = '';
    $('#reason').value = '';
    $('#status').value = '';
    $('#start').value = '';
    $('#end').value = '';
    applyFilters();
  });

  /* ----- Approve / Reject with confirm dialog ----- */
  const confirmBack = document.getElementById('confirm');
  const confirmTitle = document.getElementById('confirmTitle');
  const confirmBody  = document.getElementById('confirmBody');
  const cancelAction = document.getElementById('cancelAction');
  const proceedAction = document.getElementById('proceedAction');

  let pendingAction = null; // {pid, type}

  function openConfirm(pid, type, row) {
    confirmTitle.textContent = type === 'approve' ? 'Approve Penalty' : 'Reject Penalty';
    confirmBody.innerHTML = `
      <p>Are you sure you want to <strong>${type}</strong> penalty <strong>${row.pid}</strong> for <strong>${row.name} (${row.id})</strong>?</p>
      <p style="margin-top:8px; color:#6b7280;">Reason: ${row.reason} · Points: ${row.points} · Date: ${row.date}</p>
    `;
    pendingAction = { pid, type };
    confirmBack.style.display = 'flex';
  }

  function closeConfirm() {
    confirmBack.style.display = 'none';
    pendingAction = null;
  }

  cancelAction.addEventListener('click', closeConfirm);
  confirmBack.addEventListener('click', e => { if (e.target === confirmBack) closeConfirm(); });

  proceedAction.addEventListener('click', () => {
    if (!pendingAction) return;
    const { pid, type } = pendingAction;

    PENALTIES = PENALTIES.map(p => (
      p.pid === pid ? { ...p, status: type === 'approve' ? 'Approved' : 'Rejected' } : p
    ));

    closeConfirm();
    applyFilters();
  });

  /* ----- Init ----- */
  applyFilters();
});
</script>
</body>
</html>
