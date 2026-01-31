<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Attendance Tracking - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <style>
    /* Keep overall theme; make content cards white for readability */
    main { padding: 2rem; }
    .breadcrumb { font-size: .85rem; color:#94a3b8; margin-bottom: 1rem; }
    h2 { color:#38bdf8; margin:0 0 .25rem 0; }
    .subtitle { color:#94a3b8; margin-bottom:1.5rem; }

    .content-card, .summary, .filters, .table-wrap { background:#fff; color:#333; border-radius:12px; }
    .summary { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; padding:16px; margin-bottom:16px; box-shadow:0 2px 10px rgba(0,0,0,.08); }
    .summary .card { background:#fff; border-radius:10px; text-align:center; padding:16px; border:1px solid #edf2f7; }
    .summary .card h3 { font-size:.95rem; color:#6b7280; margin:0 0 6px; }
    .summary .card p { font-size:1.4rem; font-weight:600; color:#111827; margin:0; }

    .toolbar { display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
    .filters { padding:16px; margin-bottom:16px; box-shadow:0 2px 10px rgba(0,0,0,.08); }
    .filters .row { display:flex; gap:12px; flex-wrap:wrap; }
    .filters input, .filters select, .filters button { border:1px solid #d1d5db; background:#fff; color:#111827; border-radius:8px; padding:8px 10px; font-size:.92rem; }
    .filters .btn { cursor:pointer; }
    .filters .btn-primary { background:#38bdf8; border-color:#38bdf8; color:#0f172a; }
    .filters .btn-ghost { background:#fff; color:#111827; }
    .filters .btn-icon { display:inline-flex; align-items:center; gap:.4rem; }
    .filters .split { flex:1 1 260px; }

    .period { display:flex; gap:8px; flex-wrap:wrap; }
    .period .seg { display:flex; border:1px solid #d1d5db; border-radius:10px; overflow:hidden; }
    .period .seg button { border:0; background:#fff; padding:8px 12px; cursor:pointer; }
    .period .seg button.active { background:#0ea5e9; color:#fff; }

    .table-wrap { padding:0; overflow:hidden; border:1px solid #e5e7eb; }
    table { width:100%; border-collapse:collapse; }
    thead { background:#0f172a; color:#38bdf8; }
    th, td { padding:12px 14px; border-bottom:1px solid #e5e7eb; text-align:left; }
    tbody tr:hover { background:#f8fafc; }

    .status { padding:4px 8px; border-radius:999px; font-size:.8rem; }
    .present { background:#dcfce7; color:#166534; }
    .late { background:#fef9c3; color:#854d0e; }
    .absent { background:#fee2e2; color:#991b1b; }
<<<<<<< HEAD
=======
    .leave { background:#e0e7ff; color:#4338ca; }
>>>>>>> chai-training

    .btn-small { padding:6px 10px; font-size:.85rem; border-radius:8px; border:1px solid #d1d5db; background:#fff; cursor:pointer; }
    .btn-view { background:#38bdf8; border-color:#38bdf8; color:#0f172a; }
    .btn-clear { background:#fff; }

    /* Modal */
    .modal-backdrop { position:fixed; inset:0; background:rgba(15,23,42,.6); display:none; align-items:center; justify-content:center; z-index:50; }
    .modal { width:min(920px, 92vw); background:#fff; color:#111827; border-radius:14px; box-shadow:0 10px 30px rgba(0,0,0,.35); overflow:hidden; }
    .modal header { display:flex; justify-content:space-between; align-items:center; padding:14px 16px; background:#0f172a; color:#e2e8f0; }
    .modal .body { padding:16px; max-height:75vh; overflow:auto; }
    .modal .close { border:0; background:transparent; color:#e2e8f0; font-size:1.2rem; cursor:pointer; }
    .chips { display:flex; gap:8px; flex-wrap:wrap; margin:8px 0 16px; }
    .chips .chip { background:#f3f4f6; border:1px solid #e5e7eb; padding:6px 10px; border-radius:999px; font-size:.85rem; }

    footer { text-align:center; color:#64748b; font-size:.8rem; padding:22px 0 0; }
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
      <div class="breadcrumb">Home > Attendance > Tracking</div>
      <h2>Attendance Tracking</h2>
      <p class="subtitle">Trace weekly/monthly attendance, view records, and filter by employee or department.</p>

      <!-- Summary cards (auto-updated) -->
      <section class="summary" id="summaryCards">
        <div class="card"><h3>Total Records</h3><p id="sum-total">0</p></div>
        <div class="card"><h3>Present</h3><p id="sum-present">0</p></div>
        <div class="card"><h3>Late</h3><p id="sum-late">0</p></div>
<<<<<<< HEAD
        <div class="card"><h3>Absent</h3><p id="sum-absent">0</p></div>
=======
        <div class="card"><h3>Absent / Leave</h3><p id="sum-absent">0</p></div>
>>>>>>> chai-training
      </section>

      <!-- Filters + Period controls -->
      <section class="filters">
        <div class="row">
          <div class="split">
            <label for="search">Search (Name/ID)</label><br>
            <input type="text" id="search" placeholder="e.g., EMP001 or John Tan">
          </div>

          <div class="split">
            <label for="department">Department</label><br>
            <select id="department">
              <option value="">All</option>
<<<<<<< HEAD
              <option value="IT">IT</option>
              <option value="HR">HR</option>
              <option value="Finance">Finance</option>
              <option value="Marketing">Marketing</option>
              <option value="Operations">Operations</option>
=======
              @foreach($departments as $dept)
                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
              @endforeach
>>>>>>> chai-training
            </select>
          </div>

          <div class="split">
            <label for="status">Status</label><br>
            <select id="status">
              <option value="">Any</option>
<<<<<<< HEAD
              <option value="Present">Present</option>
              <option value="Late">Late</option>
              <option value="Absent">Absent</option>
=======
              <option value="present">Present</option>
              <option value="late">Late</option>
              <option value="absent">Absent</option>
              <option value="leave">Leave</option>
>>>>>>> chai-training
            </select>
          </div>

          <div class="split">
            <label>Period</label><br>
            <div class="toolbar">
              <div class="seg" id="segView">
                <button type="button" data-view="week" class="active">Week</button>
                <button type="button" data-view="month">Month</button>
              </div>
              <div class="toolbar">
                <button type="button" class="btn btn-ghost btn-icon" id="prevPeriod"><i class="fa-solid fa-chevron-left"></i> Prev</button>
                <button type="button" class="btn btn-ghost btn-icon" id="thisPeriod"><i class="fa-regular fa-calendar"></i> This</button>
                <button type="button" class="btn btn-ghost btn-icon" id="nextPeriod">Next <i class="fa-solid fa-chevron-right"></i></button>
              </div>
            </div>
          </div>

          <div class="split">
            <label for="start">Start</label><br>
            <input type="date" id="start">
          </div>
          <div class="split">
            <label for="end">End</label><br>
            <input type="date" id="end">
          </div>

          <div class="toolbar">
            <button class="btn btn-primary" id="applyFilters"><i class="fa-solid fa-filter"></i> Apply</button>
            <button class="btn btn-ghost" id="clearFilters">Clear</button>
          </div>
        </div>
      </section>

      <!-- Table -->
      <section class="table-wrap">
        <table id="attendanceTable">
          <thead>
            <tr>
              <th>Date</th>
              <th>Employee ID</th>
              <th>Name</th>
              <th>Department</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody><!-- JS fills --></tbody>
        </table>
      </section>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <!-- Record Modal -->
  <div class="modal-backdrop" id="recordModal">
    <div class="modal">
      <header>
        <h3 id="modalTitle">Attendance Record</h3>
        <button class="close" id="modalClose" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
      </header>
      <div class="body">
        <div class="chips" id="modalChips"></div>
        <div class="content-card" style="padding:12px;">
          <table style="width:100%; border-collapse:collapse;">
            <thead>
              <tr style="background:#f3f4f6;">
                <th style="padding:10px; text-align:left;">Date</th>
                <th style="padding:10px; text-align:left;">Check-in</th>
                <th style="padding:10px; text-align:left;">Check-out</th>
                <th style="padding:10px; text-align:left;">Status</th>
              </tr>
            </thead>
            <tbody id="modalBody"><!-- JS fills --></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
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
    // Example: both '/admin/attendance/tracking' and '/admin/attendance/tracking/' normalize the same.
  };
  const here = normPath(location.href);
<<<<<<< HEAD
=======
  const initialStart = "{{ $start ?? '' }}";
  const initialEnd   = "{{ $end ?? '' }}";
>>>>>>> chai-training

  // Clear any server-side default actives so JS owns it (prevents double highlight)
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
    // Fallback: longest prefix match (e.g., when the link is the section root)
    let best = null;
    for (const a of links) {
      const p = normPath(a.href);
      if (p !== '/' && here.startsWith(p)) {
        if (!best || p.length > normPath(best.href).length) best = a;
      }
    }
    activeLink = best;
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
      if (t0) t.setAttribute('aria-expanded','true');
    }
  }

  toggles.forEach((btn, i) => {
    btn.setAttribute('role','button');
    btn.setAttribute('tabindex','0');

    const doToggle = (e) => {
      e.preventDefault();
      const group = btn.closest('.sidebar-group');
      const isOpen = group.classList.contains('open');

      // Close all groups
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

<<<<<<< HEAD
  /* ---------- Dummy Data (replace with API/DB later) ---------- */
  // Format: {date:'YYYY-MM-DD', id:'EMP001', name:'John Tan', dept:'IT', in:'09:05', out:'18:00', status:'Late'}
  const DATA = [
    // Week sample around Nov 2025 (adjust as needed)
    {date:'2025-11-03', id:'EMP001', name:'John Tan', dept:'IT', in:'09:05', out:'18:00', status:'Late'},
    {date:'2025-11-03', id:'EMP002', name:'Alicia Wong', dept:'Finance', in:'08:55', out:'17:55', status:'Present'},
    {date:'2025-11-03', id:'EMP003', name:'Marcus Lim', dept:'HR', in:'-', out:'-', status:'Absent'},

    {date:'2025-11-04', id:'EMP001', name:'John Tan', dept:'IT', in:'08:59', out:'18:10', status:'Present'},
    {date:'2025-11-04', id:'EMP002', name:'Alicia Wong', dept:'Finance', in:'09:10', out:'18:05', status:'Late'},
    {date:'2025-11-04', id:'EMP003', name:'Marcus Lim', dept:'HR', in:'09:00', out:'17:58', status:'Present'},

    {date:'2025-11-05', id:'EMP001', name:'John Tan', dept:'IT', in:'09:03', out:'18:01', status:'Late'},
    {date:'2025-11-05', id:'EMP002', name:'Alicia Wong', dept:'Finance', in:'08:52', out:'17:52', status:'Present'},
    {date:'2025-11-05', id:'EMP004', name:'Chen Wei', dept:'Marketing', in:'09:20', out:'18:12', status:'Late'},

    {date:'2025-11-06', id:'EMP001', name:'John Tan', dept:'IT', in:'08:57', out:'18:05', status:'Present'},
    {date:'2025-11-06', id:'EMP004', name:'Chen Wei', dept:'Marketing', in:'09:00', out:'18:00', status:'Present'},

    {date:'2025-11-07', id:'EMP001', name:'John Tan', dept:'IT', in:'-', out:'-', status:'Absent'},
    {date:'2025-11-07', id:'EMP002', name:'Alicia Wong', dept:'Finance', in:'08:55', out:'17:55', status:'Present'},
    {date:'2025-11-07', id:'EMP003', name:'Marcus Lim', dept:'HR', in:'09:40', out:'18:10', status:'Late'},

    // Some earlier month items for month view
    {date:'2025-10-28', id:'EMP004', name:'Chen Wei', dept:'Marketing', in:'09:03', out:'18:10', status:'Late'},
    {date:'2025-10-29', id:'EMP002', name:'Alicia Wong', dept:'Finance', in:'08:51', out:'17:45', status:'Present'},
  ];
=======
  /* ---------- API-backed data ---------- */
  const ENDPOINT = "{{ route('admin.attendance.data') }}";
  let DATA = [];           // Last fetched, already filtered by current form inputs
  let SUMMARY = { total:0, present:0, late:0, absent:0, leave:0 };
>>>>>>> chai-training

  /* ---------- Utilities ---------- */
  const $ = s => document.querySelector(s);
  const $$ = s => document.querySelectorAll(s);
  const tbody = $('#attendanceTable tbody');

  function isBetween(d, start, end) {
    const x = new Date(d);
    if (start) { const s = new Date(start); s.setHours(0,0,0,0); if (x < s) return false; }
    if (end)   { const e = new Date(end);   e.setHours(23,59,59,999); if (x > e) return false; }
    return true;
  }

  function getWeekRange(baseDate=new Date()) {
    const d = new Date(baseDate);
    const day = d.getDay(); // 0 Sun
    const diffToMon = (day === 0 ? -6 : 1 - day); // Monday start
    const start = new Date(d); start.setDate(d.getDate() + diffToMon);
    const end = new Date(start); end.setDate(start.getDate() + 6);
    return [start, end];
  }

  function getMonthRange(baseDate=new Date()) {
    const d = new Date(baseDate);
    const start = new Date(d.getFullYear(), d.getMonth(), 1);
    const end = new Date(d.getFullYear(), d.getMonth()+1, 0);
    return [start, end];
  }

  function ymd(dt){ return dt.toISOString().slice(0,10); }

  /* ---------- State ---------- */
  let view = 'week';
  let anchor = new Date(); // anchor date for week/month
  let range = getWeekRange(anchor); // [start, end]

  function setRangeFromView() {
    range = (view === 'week') ? getWeekRange(anchor) : getMonthRange(anchor);
    $('#start').value = ymd(range[0]);
    $('#end').value = ymd(range[1]);
  }

<<<<<<< HEAD
=======
  function setInitialRange() {
    if (initialStart) $('#start').value = initialStart;
    if (initialEnd) $('#end').value = initialEnd;
    if (!$('#start').value || !$('#end').value) {
      setRangeFromView();
    }
  }

>>>>>>> chai-training
  /* ---------- Rendering ---------- */
  function renderTable(rows) {
    tbody.innerHTML = '';
    if (!rows.length) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 8;
      td.textContent = 'No attendance records for selected filters.';
      tr.appendChild(td); tbody.appendChild(tr);
      return;
    }
    for (const r of rows) {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${r.date}</td>
        <td>${r.id}</td>
        <td>${r.name}</td>
        <td>${r.dept}</td>
        <td>${r.in}</td>
        <td>${r.out}</td>
        <td>
          <span class="status ${r.status.toLowerCase()}">${r.status}</span>
        </td>
        <td>
          <button class="btn-small btn-view" data-id="${r.id}" data-name="${r.name}">View</button>
        </td>
      `;
      tbody.appendChild(tr);
    }
  }

<<<<<<< HEAD
  function updateSummary(rows) {
    $('#sum-total').textContent   = rows.length;
    $('#sum-present').textContent = rows.filter(r => r.status==='Present').length;
    $('#sum-late').textContent    = rows.filter(r => r.status==='Late').length;
    $('#sum-absent').textContent  = rows.filter(r => r.status==='Absent').length;
  }

  /* ---------- Filtering ---------- */
  function applyFilters() {
    const q = $('#search').value.trim().toLowerCase();
    const dept = $('#department').value;
    const status = $('#status').value;
    const start = $('#start').value;
    const end = $('#end').value;

    const filtered = DATA.filter(r => {
      const qmatch = !q || r.id.toLowerCase().includes(q) || r.name.toLowerCase().includes(q);
      const dmatch = !dept || r.dept === dept;
      const smatch = !status || r.status === status;
      const rmatch = isBetween(r.date, start, end);
      return qmatch && dmatch && smatch && rmatch;
    }).sort((a,b)=> a.date.localeCompare(b.date) || a.id.localeCompare(b.id));

    renderTable(filtered);
    updateSummary(filtered);
    wireViewButtons(filtered);
=======
  function updateSummary() {
    $('#sum-total').textContent   = SUMMARY.total;
    $('#sum-present').textContent = SUMMARY.present;
    $('#sum-late').textContent    = SUMMARY.late;
    // Absent card also counts approved leave so admins see all non-working cases
    $('#sum-absent').textContent  = SUMMARY.absent + SUMMARY.leave;
  }

  /* ---------- Fetch & Filtering ---------- */
  async function applyFilters() {
    const btn = document.getElementById('applyFilters');
    btn.disabled = true;
    const originalLabel = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading';

    const params = new URLSearchParams({
      q: $('#search').value.trim(),
      department: $('#department').value,
      status: $('#status').value,
      start: $('#start').value,
      end: $('#end').value,
    });

    try {
      const resp = await fetch(`${ENDPOINT}?${params.toString()}`, { headers: { 'Accept': 'application/json' }});
      if (!resp.ok) throw new Error('Failed to load attendance data');
      const json = await resp.json();
      DATA = Array.isArray(json.data) ? json.data : [];
      SUMMARY = json.summary || SUMMARY;
      renderTable(DATA);
      updateSummary();
      wireViewButtons();
    } catch (err) {
      console.error(err);
      tbody.innerHTML = `<tr><td colspan="8">Could not load attendance records. Please try again.</td></tr>`;
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalLabel;
    }
>>>>>>> chai-training
  }

  /* ---------- Period Controls ---------- */
  function shiftPeriod(delta=0) {
    if (view === 'week') {
      anchor.setDate(anchor.getDate() + delta*7);
    } else {
      anchor.setMonth(anchor.getMonth() + delta);
    }
    setRangeFromView();
    applyFilters();
  }

  document.getElementById('prevPeriod').addEventListener('click', ()=> shiftPeriod(-1));
  document.getElementById('nextPeriod').addEventListener('click', ()=> shiftPeriod(+1));
  document.getElementById('thisPeriod').addEventListener('click', ()=> { anchor = new Date(); setRangeFromView(); applyFilters(); });

  document.querySelectorAll('#segView button').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      document.querySelectorAll('#segView button').forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      view = btn.dataset.view;
      setRangeFromView();
      applyFilters();
    });
  });

  document.getElementById('applyFilters').addEventListener('click', applyFilters);
  document.getElementById('clearFilters').addEventListener('click', ()=>{
    document.getElementById('search').value='';
    document.getElementById('department').value='';
    document.getElementById('status').value='';
    setRangeFromView();
    applyFilters();
  });

  /* ---------- Modal: View Record per employee in selected range ---------- */
  const modal = document.getElementById('recordModal');
  const modalClose = document.getElementById('modalClose');
  const modalTitle = document.getElementById('modalTitle');
  const modalChips = document.getElementById('modalChips');
  const modalBody  = document.getElementById('modalBody');

  function openModal(empId, name) {
    const start = document.getElementById('start').value, end = document.getElementById('end').value;
    const rows = DATA.filter(r => r.id===empId && isBetween(r.date, start, end))
                     .sort((a,b)=> a.date.localeCompare(b.date));
<<<<<<< HEAD
    modalTitle.textContent = `${name} (${empId}) — Attendance`;
    modalChips.innerHTML = `
      <span class="chip"><i class="fa-regular fa-calendar"></i> ${start} → ${end}</span>
=======
    modalTitle.textContent = `${name} (${empId}) - Attendance`;
    modalChips.innerHTML = `
      <span class="chip"><i class="fa-regular fa-calendar"></i> ${start} -> ${end}</span>
>>>>>>> chai-training
      <span class="chip"><i class="fa-solid fa-building"></i> ${rows[0]?.dept ?? '-'}</span>
      <span class="chip"><i class="fa-regular fa-circle-check"></i> Present: ${rows.filter(r=>r.status==='Present').length}</span>
      <span class="chip"><i class="fa-solid fa-clock"></i> Late: ${rows.filter(r=>r.status==='Late').length}</span>
      <span class="chip"><i class="fa-solid fa-user-slash"></i> Absent: ${rows.filter(r=>r.status==='Absent').length}</span>
<<<<<<< HEAD
=======
      <span class="chip"><i class="fa-solid fa-umbrella-beach"></i> Leave: ${rows.filter(r=>r.status==='Leave').length}</span>
>>>>>>> chai-training
    `;
    modalBody.innerHTML = rows.map(r => `
      <tr>
        <td style="padding:10px;">${r.date}</td>
        <td style="padding:10px;">${r.in}</td>
        <td style="padding:10px;">${r.out}</td>
        <td style="padding:10px;"><span class="status ${r.status.toLowerCase()}">${r.status}</span></td>
      </tr>
    `).join('') || `<tr><td colspan="4" style="padding:10px;">No records in range.</td></tr>`;
    modal.style.display = 'flex';
  }

  function closeModal(){ modal.style.display='none'; }
  modalClose.addEventListener('click', closeModal);
  modal.addEventListener('click', e=>{ if (e.target === modal) closeModal(); });

  function wireViewButtons() {
    document.querySelectorAll('#attendanceTable .btn-view').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        openModal(btn.dataset.id, btn.dataset.name);
      });
    });
  }

  /* ---------- Init ---------- */
<<<<<<< HEAD
  setRangeFromView();     // set start/end to current week by default
=======
  setInitialRange();      // set start/end to provided dates or current week
>>>>>>> chai-training
  applyFilters();         // render table + summary
});
</script>
</body>
</html>
