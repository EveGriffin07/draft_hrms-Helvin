<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Leave Request - HRMS</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
<style>
  .box{background:#fff;border-radius:10px;padding:16px;margin-bottom:16px}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden}
  th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:middle}
  thead{background:#0f172a;color:#38bdf8}
  .row{display:flex;gap:10px;flex-wrap:wrap}
  .row>*{flex:1 1 200px}
  input,select,button{padding:8px;border:1px solid #d1d5db;border-radius:8px;background:#fff}
  .btn{background:#38bdf8;color:#0f172a;border-color:#38bdf8;cursor:pointer}
  .btn-ghost{background:#fff}
  .muted{color:#6b7280;font-size:.9rem}

  /* status pills */
  .pill{padding:4px 8px;border-radius:999px;font-size:.8rem;white-space:nowrap}
  .s-pending{background:#fef9c3;color:#854d0e}
  .s-approved{background:#dcfce7;color:#166534}
  .s-rejected{background:#fee2e2;color:#991b1b}

  /* colored status buttons for edit mode */
  .chip{padding:6px 10px;border-radius:999px;border:1px solid transparent;cursor:pointer;font-size:.85rem}
  .chip.pending{background:#fef9c3;border-color:#fde68a;color:#854d0e}
  .chip.approved{background:#dcfce7;border-color:#86efac;color:#166534}
  .chip.rejected{background:#fee2e2;border-color:#fecaca;color:#991b1b}
  .chip.active{outline:2px solid #0ea5e9; outline-offset: 2px}

  .actions{display:flex;gap:6px;flex-wrap:wrap}
</style>
</head>
<body>
<header><div class="title">Web-Based HRMS</div><div class="user-info">
    <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
        <i class="fa-regular fa-bell"></i> &nbsp; HR Admin
    </a>
</div></header>

<div class="container">
  @include('admin.layout.sidebar')

  <main>
    <div class="breadcrumb">Home > Leave > Leave Request</div>
    <h2>Leave Request</h2>
    <p class="subtitle">Review requests. Click <strong>Edit</strong> to change <em>status only</em>, then <strong>Save</strong> or <strong>Cancel</strong>.</p>

    <!-- Filters -->
    <div class="box">
      <div class="row">
        <div>
          <label>Search</label>
          <input id="q" type="text" placeholder="EMP001 / name">
        </div>
        <div>
          <label>Department</label>
          <select id="dept">
            <option value="">All</option><option>IT</option><option>HR</option><option>Finance</option><option>Marketing</option>
          </select>
        </div>
        <div>
          <label>Type</label>
          <select id="type">
            <option value="">All</option><option>Annual</option><option>Sick</option><option>Unpaid</option><option>Emergency</option>
          </select>
        </div>
        <div>
          <label>Status</label>
          <select id="status">
            <option value="">All</option><option>Pending</option><option>Approved</option><option>Rejected</option>
          </select>
        </div>
        <div style="align-self:end">
          <button class="btn" id="apply">Filter</button>
          <button class="btn-ghost" id="clear">Clear</button>
        </div>
      </div>
    </div>

    <!-- Summary -->
    <div class="box" id="kpis">
      <div class="row" style="align-items:stretch">
        <div><strong>Total Requests</strong><div id="k-total" class="muted">-</div></div>
        <div><strong>Pending</strong><div id="k-pending" class="muted">-</div></div>
        <div><strong>Approved</strong><div id="k-approved" class="muted">-</div></div>
        <div><strong>Rejected</strong><div id="k-rejected" class="muted">-</div></div>
      </div>
    </div>

    <!-- Table -->
    <div class="box">
      <table id="tbl">
        <thead>
          <tr>
            <th>Employee</th><th>Department</th><th>Type</th><th>Period</th><th>Days</th><th>Status</th><th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <p class="muted" style="margin-top:8px">Front-end demo only. Hook up a controller to persist changes.</p>
    </div>

    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
  </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  /* ========= Sidebar: single active, single open, persistence ========= */
  const groups  = document.querySelectorAll('.sidebar-group');
  const toggles = document.querySelectorAll('.sidebar-toggle');
  const links   = document.querySelectorAll('.submenu a');
  const STORAGE_KEY = 'hrms_sidebar_open_group';

  // Normalize URL path for reliable comparison
  const normPath = (u) => {
    const url = new URL(u, location.origin);
    let p = url.pathname
      .replace(/\/index\.php$/i, '') // strip Laravel front controller if present at end
      .replace(/\/index\.php\//i, '/') // strip if in middle
      .replace(/\/+$/, '');            // strip trailing slash
    return p === '' ? '/' : p;
  };
  const here = normPath(location.href);

  // Clear any server-rendered states to avoid double-highlighting
  groups.forEach(g => {
    g.classList.remove('open');
    const t = g.querySelector('.sidebar-toggle');
    if (t) t.setAttribute('aria-expanded','false');
  });
  links.forEach(a => a.classList.remove('active'));

  // Pick exactly one active link (exact match; fallback to startsWith)
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

  // If nothing opened by active link, restore last open or default to first
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

  // Accordion behavior + persistence
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
});
</script>

</body>
</html>
