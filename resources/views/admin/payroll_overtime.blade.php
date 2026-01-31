<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Claim Overtime - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
<<<<<<< HEAD
=======
  <meta name="csrf-token" content="{{ csrf_token() }}">
>>>>>>> chai-training
  <style>
    body { background:#f5f7fb; }
    main { padding:28px 32px; }
    .page-box {
      background:#fff;
      border-radius:12px;
      padding:18px;
      box-shadow:0 12px 30px rgba(15,23,42,0.08);
      border:1px solid #e5e7eb;
    }
    .page-header { margin-bottom:14px; }
    .page-header h2 { margin:0; color:#0f172a; }
    .page-header p { margin:4px 0 0; color:#6b7280; font-size:14px; }

    .toolbar {
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      justify-content:flex-end;
      margin-bottom:12px;
    }
    .toolbar input,
    .toolbar select {
      min-width:160px;
      padding:10px 12px;
      border:1px solid #d1d5db;
      border-radius:10px;
      background:#fff;
      font-size:14px;
    }

    table {
      width:100%;
      border-collapse:collapse;
      background:#fff;
    }
    th, td { padding:12px 14px; text-align:left; border-bottom:1px solid #e5e7eb; }
    thead th { color:#0f172a; font-weight:600; background:#f8fafc; }

    .status {
      display:inline-flex;
      align-items:center;
      gap:6px;
      padding:6px 10px;
      border-radius:999px;
      font-weight:600;
      font-size:13px;
    }
    .pending { background:#fff7ed; color:#c05621; border:1px solid #fed7aa; }
    .approved { background:#ecfdf3; color:#15803d; border:1px solid #bbf7d0; }
    .hold { background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe; }

    .btn {
      border:none;
      border-radius:999px;
      padding:8px 14px;
      font-weight:700;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      gap:8px;
      box-shadow:0 10px 20px rgba(16,185,129,0.25);
    }
    .btn-approve { background:#22c55e; color:#fff; }
    .btn-reject { background:#e5e7eb; color:#1f2937; box-shadow:none; }
    .btn-view { background:#e0e7ff; color:#312e81; box-shadow:none; }
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
      <div class="breadcrumb">Home > Payroll > Claim Overtime</div>
      <div class="page-header">
        <h2>Claim Overtime</h2>
        <p>Review and act on employee overtime requests currently pending approval.</p>
      </div>

      <div class="page-box">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px; flex-wrap:wrap;">
          <div>
            <h3 style="margin:0 0 4px;">Pending Overtime Requests</h3>
            <p style="margin:0; color:#6b7280;">Approve, reject, or hold overtime claims with quick filters.</p>
          </div>
          <div class="toolbar">
            <input type="text" id="search" placeholder="Search employee...">
<<<<<<< HEAD
            <select id="status">
              <option value="">All Status</option>
              <option value="Pending">Pending</option>
              <option value="Approved">Approved</option>
              <option value="Hold">Hold</option>
=======
            <select id="dept">
              <option value="">All Departments</option>
              @foreach($departments as $dept)
                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
              @endforeach
            </select>
            <select id="status">
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
>>>>>>> chai-training
            </select>
            <select id="range">
              <option value="">Any Date</option>
              <option value="this-month">This Month</option>
              <option value="last-month">Last Month</option>
            </select>
          </div>
        </div>

        <table id="ot-table">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Department</th>
              <th>Date</th>
              <th>Hours</th>
              <th>Reason</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <footer style="margin-top:16px; text-align:center; color:#94a3b8; font-size:12px;">Ac 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
<<<<<<< HEAD
    const DATA = [
      { id:1, name:'Jane Smith', dept:'Finance', date:'2025-12-05', hours:3.5, reason:'Quarter-end closing', status:'Pending' },
      { id:2, name:'David Lee', dept:'IT Department', date:'2025-12-04', hours:2.0, reason:'Deployment support', status:'Pending' },
      { id:3, name:'Anna Wong', dept:'Marketing', date:'2025-12-02', hours:1.5, reason:'Campaign launch', status:'Approved' },
    ];

    const tbody = document.querySelector('#ot-table tbody');
    const search = document.getElementById('search');
    const status = document.getElementById('status');
    const range = document.getElementById('range');

    const monthMatch = (d, key) => {
      const dt = new Date(d);
      const now = new Date();
      if (key === 'this-month') {
        return dt.getFullYear() === now.getFullYear() && dt.getMonth() === now.getMonth();
      }
      if (key === 'last-month') {
        const prev = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        return dt.getFullYear() === prev.getFullYear() && dt.getMonth() === prev.getMonth();
      }
      return true;
    };

    function render() {
      const q = search.value.trim().toLowerCase();
      const s = status.value;
      const r = range.value;

      const rows = DATA.filter(item => {
        const textMatch = !q || item.name.toLowerCase().includes(q) || item.dept.toLowerCase().includes(q);
        const statusMatch = !s || item.status === s;
        const dateMatch = !r || monthMatch(item.date, r);
        return textMatch && statusMatch && dateMatch;
      });

=======
    const tbody   = document.querySelector('#ot-table tbody');
    const search  = document.getElementById('search');
    const dept    = document.getElementById('dept');
    const status  = document.getElementById('status');
    const range   = document.getElementById('range');
    const CSRF    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const ENDPOINT_LIST   = "{{ route('admin.payroll.overtime.data') }}";
    const ENDPOINT_STATUS = (id) => "{{ route('admin.payroll.overtime.status', ['overtime' => '__ID__']) }}".replace('__ID__', id);

    function rangeToDates(key) {
      const now = new Date();
      const firstOfThis = new Date(now.getFullYear(), now.getMonth(), 1);
      if (key === 'this-month') {
        return { start: firstOfThis.toISOString().slice(0,10), end: new Date(now.getFullYear(), now.getMonth()+1, 0).toISOString().slice(0,10) };
      }
      if (key === 'last-month') {
        const start = new Date(now.getFullYear(), now.getMonth()-1, 1);
        const end   = new Date(now.getFullYear(), now.getMonth(), 0);
        return { start: start.toISOString().slice(0,10), end: end.toISOString().slice(0,10) };
      }
      return { start:'', end:'' };
    }

    async function loadData() {
      tbody.innerHTML = '<tr><td colspan="7">Loading...</td></tr>';

      const { start, end } = rangeToDates(range.value);
      const params = new URLSearchParams({
        q: search.value.trim(),
        department: dept.value,
        status: status.value,
        start,
        end,
      });

      try {
        const resp = await fetch(`${ENDPOINT_LIST}?${params.toString()}`, { headers: { 'Accept': 'application/json' }});
        if (!resp.ok) throw new Error('Unable to load overtime records');
        const json = await resp.json();
        renderTable(Array.isArray(json.data) ? json.data : []);
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="7">Error: ${err.message}</td></tr>`;
      }
    }

    function statusBadge(status) {
      const s = status.toLowerCase();
      if (s === 'approved') return 'approved';
      if (s === 'rejected') return 'hold';
      return 'pending';
    }

    function renderTable(rows) {
>>>>>>> chai-training
      tbody.innerHTML = '';
      if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="7">No overtime requests found.</td></tr>';
        return;
      }

      rows.forEach(item => {
<<<<<<< HEAD
        const tr = document.createElement('tr');
        const badge = item.status === 'Approved' ? 'approved' : item.status === 'Hold' ? 'hold' : 'pending';
        tr.innerHTML = `
          <td><strong>${item.name}</strong></td>
          <td>${item.dept}</td>
          <td>${new Date(item.date).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })}</td>
          <td>${item.hours}</td>
          <td>${item.reason}</td>
          <td><span class="status ${badge}">${item.status}</span></td>
          <td>
            ${item.status === 'Pending'
              ? `<button class="btn btn-approve" data-id="${item.id}" data-action="approve"><i class="fa-solid fa-check"></i> Approve</button>
                 <button class="btn btn-reject" data-id="${item.id}" data-action="reject"><i class="fa-solid fa-xmark"></i> Reject</button>`
              : `<button class="btn btn-view" data-id="${item.id}" data-action="view"><i class="fa-regular fa-eye"></i> View</button>`
=======
        const badge = statusBadge(item.status);
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${item.employee}</strong><br><span style="color:#6b7280;">${item.code}</span></td>
          <td>${item.dept}</td>
          <td>${item.date}</td>
          <td>${item.hours}</td>
          <td>${item.reason ?? '-'}</td>
          <td><span class="status ${badge}">${item.status}</span></td>
          <td>
            ${item.status.toLowerCase() === 'pending'
              ? `<button class="btn btn-approve" data-id="${item.ot_id}" data-action="approve"><i class="fa-solid fa-check"></i> Approve</button>
                 <button class="btn btn-reject" data-id="${item.ot_id}" data-action="reject"><i class="fa-solid fa-xmark"></i> Reject</button>`
              : `<button class="btn btn-view" data-id="${item.ot_id}" data-action="view"><i class="fa-regular fa-eye"></i> View</button>`
>>>>>>> chai-training
            }
          </td>
        `;
        tbody.appendChild(tr);
      });

<<<<<<< HEAD
      document.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = Number(btn.dataset.id);
          const action = btn.dataset.action;
          const idx = DATA.findIndex(x => x.id === id);
          if (idx === -1) return;
          if (action === 'approve') DATA[idx].status = 'Approved';
          if (action === 'reject') DATA[idx].status = 'Hold';
          render();
=======
      bindActions();
    }

    function bindActions() {
      document.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', async () => {
          const action = btn.dataset.action;
          if (action === 'view') return;

          const id = btn.dataset.id;
          const label = btn.innerHTML;
          btn.disabled = true;
          btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

          try {
            const resp = await fetch(ENDPOINT_STATUS(id), {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({ action }),
            });
            if (!resp.ok) throw new Error(await resp.text() || 'Update failed');
            await loadData();
          } catch (err) {
            alert('Unable to update overtime: ' + err.message);
          } finally {
            btn.disabled = false;
            btn.innerHTML = label;
          }
>>>>>>> chai-training
        });
      });
    }

<<<<<<< HEAD
    [search, status, range].forEach(el => el.addEventListener('input', render));
    render();
=======
    [search, dept, status, range].forEach(el => el.addEventListener('input', loadData));

    loadData();
>>>>>>> chai-training
  });
  </script>
</body>
</html>
