<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Leave Balance Tracking - HRMS</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .box{background:#fff;border-radius:10px;padding:16px;margin-bottom:16px}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden}
  th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:middle}
  thead{background:#0f172a;color:#38bdf8}
  .row{display:flex;gap:10px;flex-wrap:wrap}
  .row>*{flex:1 1 200px}
  input,select,button{padding:8px;border:1px solid #d1d5db;border-radius:8px;background:#fff}
  input[type=number]{width:100px}
  .btn{background:#38bdf8;color:#0f172a;border-color:#38bdf8;cursor:pointer}
  .btn-ghost{background:#fff}
  .muted{color:#6b7280;font-size:.9rem}
  .actions{display:flex;gap:6px;flex-wrap:wrap}
  /* modal */
  .modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.5)}
  .sheet{background:#fff;border-radius:10px;padding:16px;max-width:720px;width:92%}
</style>
</head>
<body>
<header><div class="title">Web-Based HRMS</div>
<div class="user-info">
    <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
        <i class="fa-regular fa-bell"></i> &nbsp; HR Admin
    </a>
</div>
</header>

<div class="container">
  @include('admin.layout.sidebar')


  <main>
    <div class="breadcrumb">Home > Leave > Leave Balance Tracking</div>
    <h2>Leave Balance Tracking</h2>
    <p class="subtitle">View, edit (via <strong>Edit</strong>), and see detailed balance history and validity.</p>

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
            <option value="">All</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
            @endforeach
          </select>
        </div>
        <div style="align-self:end">
          <button class="btn" id="apply">Filter</button>
          <button class="btn-ghost" id="clear">Clear</button>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="box">
      <table id="tbl">
        <thead>
          <tr>
            <th>Employee</th><th>Department</th>
            <th>Annual (Remain)</th><th>Sick (Remain)</th><th>Unpaid (Remain)</th>
            <th>Details</th><th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

    </div>

    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
  </main>
</div>

<!-- Details modal -->
<div class="modal" id="modal">
  <div class="sheet">
    <h3 style="margin:0 0 6px">Leave Balance Details</h3>
    <div id="meta" class="muted" style="margin-bottom:10px"></div>
    <table style="width:100%;border:1px solid #e5e7eb;border-collapse:collapse">
      <thead>
        <tr style="background:#f8fafc">
          <th style="padding:8px;text-align:left">Type</th>
          <th style="padding:8px;text-align:right">Total</th>
          <th style="padding:8px;text-align:right">Used</th>
          <th style="padding:8px;text-align:right">Remaining</th>
        </tr>
      </thead>
      <tbody id="breakdown"></tbody>
    </table>
    <div class="muted" id="validity" style="margin-top:8px"></div>
    <div style="margin-top:12px;text-align:right">
      <button id="close">Close</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  /* ==========================================
     SIDEBAR — single active, single open
     ========================================== */
  const ENDPOINT = "{{ route('admin.leave.balance.data') }}";
  const groups  = document.querySelectorAll('.sidebar-group');
  const toggles = document.querySelectorAll('.sidebar-toggle');
  const links   = document.querySelectorAll('.submenu a');
  const STORAGE_KEY = 'hrms_sidebar_open_group';

  // Normalize a URL path for reliable comparison
  const normPath = (u) => {
    const url = new URL(u, location.origin);
    let p = url.pathname
      .replace(/\/index\.php/i,'')  // strip Laravel front controller if present
      .replace(/\/+$/,'');          // strip trailing slash
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

  // Find exactly one active link (exact match; fallback: startsWith)
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

  // Restore last-opened group or default to the first group
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

  // Accordion toggling (persist which group is open)
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

  /* ==========================================
     LEAVE BALANCE TRACKING (unchanged)
     ========================================== */
  const DATA = [
    {
      empId:'EMP001', name:'John Tan',   dept:'IT',
      annual:{total:14, used:2}, sick:{total:8, used:2}, unpaid:{total:5, used:0},
      lastUpdated:'2025-11-03T10:21:00', validUntil:'2025-12-31'
    },
    {
      empId:'EMP002', name:'Alicia Wong',dept:'Finance',
      annual:{total:12, used:2}, sick:{total:8, used:1}, unpaid:{total:3, used:1},
      lastUpdated:'2025-11-04T09:02:00', validUntil:'2025-12-31'
    },
    {
      empId:'EMP003', name:'Marcus Lim', dept:'HR',
      annual:{total:16, used:2}, sick:{total:7, used:2}, unpaid:{total:2, used:0},
      lastUpdated:'2025-11-05T15:45:00', validUntil:'2025-12-31'
    },
    {
      empId:'EMP004', name:'Chen Wei',   dept:'Marketing',
      annual:{total:10, used:2}, sick:{total:10, used:2}, unpaid:{total:4, used:2},
      lastUpdated:'2025-11-06T11:10:00', validUntil:'2025-12-31'
    },
  ];
  let rows=[...DATA];
  const EDIT = {}; // id => { annualRemain, sickRemain, unpaidRemain }

  const $=s=>document.querySelector(s), tbody=document.querySelector('#tbl tbody');

  const remain = (x)=> Math.max(0, x.total - x.used);
  const clampRemain = (r, total)=> Math.min(Math.max(0, r), total);

  function render(){
    tbody.innerHTML='';
    rows.forEach(r=>{
      const id = r.empId;
      const isEditing = Object.prototype.hasOwnProperty.call(EDIT, id);

      const annRem = isEditing ? EDIT[id].annualRemain : remain(r.annual);
      const sickRem= isEditing ? EDIT[id].sickRemain   : remain(r.sick);
      const unpRem = isEditing ? EDIT[id].unpaidRemain : remain(r.unpaid);

      const tr=document.createElement('tr');
      tr.innerHTML=`
        <td>
          <strong>${r.name}</strong><br>
          <span class="muted">${r.empId}</span><br>
          <span class="muted">Last Updated: ${new Date(r.lastUpdated).toLocaleString()}</span>
        </td>
        <td>${r.dept}</td>

        <td>
          ${isEditing
            ? `<input type="number" min="0" step="0.5" value="${annRem}" data-id="${id}" data-f="annualRemain"> / <span class="muted">${r.annual.total}</span>`
            : `<strong>${annRem}</strong> <span class="muted">/ ${r.annual.total}</span>`
          }
        </td>

        <td>
          ${isEditing
            ? `<input type="number" min="0" step="0.5" value="${sickRem}" data-id="${id}" data-f="sickRemain"> / <span class="muted">${r.sick.total}</span>`
            : `<strong>${sickRem}</strong> <span class="muted">/ ${r.sick.total}</span>`
          }
        </td>

        <td>
          ${isEditing
            ? `<input type="number" min="0" step="0.5" value="${unpRem}" data-id="${id}" data-f="unpaidRemain"> / <span class="muted">${r.unpaid.total}</span>`
            : `<strong>${unpRem}</strong> <span class="muted">/ ${r.unpaid.total}</span>`
          }
        </td>

        <td><button class="btn-ghost view" data-id="${id}"><i class="fa-solid fa-eye"></i> View</button></td>

        <td>
          ${isEditing
            ? `<div class="actions">
                 <button class="btn" data-act="save" data-id="${id}"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                 <button class="btn-ghost" data-act="cancel" data-id="${id}"><i class="fa-solid fa-rotate-left"></i> Cancel</button>
               </div>`
            : `<button class="btn-ghost" data-act="edit" data-id="${id}"><i class="fa-solid fa-pen-to-square"></i> Edit</button>`
          }
        </td>
      `;
      tbody.appendChild(tr);
    });
    if(!rows.length) tbody.innerHTML='<tr><td colspan="7">No employees.</td></tr>';
  }

  function filter(){
    const q=$('#q').value.trim().toLowerCase(), d=$('#dept').value;
    rows = DATA.filter(r => (!q || r.empId.toLowerCase().includes(q) || r.name.toLowerCase().includes(q)) && (!d || r.dept===d));
    render();
  }
  $('#apply').addEventListener('click', filter);
  $('#clear').addEventListener('click', ()=>{ $('#q').value=''; $('#dept').value=''; filter(); });

  tbody.addEventListener('click', (e)=>{
    const btn = e.target.closest('button[data-act]');
    if (btn){
      const id = btn.dataset.id, act = btn.dataset.act;
      if (act==='edit'){
        const row = DATA.find(x=>x.empId===id);
        EDIT[id] = {
          annualRemain: remain(row.annual),
          sickRemain:   remain(row.sick),
          unpaidRemain: remain(row.unpaid),
        };
        render();
      } else if (act==='cancel'){
        delete EDIT[id];
        render();
      } else if (act==='save'){
        const row = DATA.find(x=>x.empId===id);
        const buf = EDIT[id];
        if (!buf) return;
        buf.annualRemain = clampRemain(Number(buf.annualRemain||0), row.annual.total);
        buf.sickRemain   = clampRemain(Number(buf.sickRemain||0),   row.sick.total);
        buf.unpaidRemain = clampRemain(Number(buf.unpaidRemain||0), row.unpaid.total);
        row.annual.used = row.annual.total - buf.annualRemain;
        row.sick.used   = row.sick.total   - buf.sickRemain;
        row.unpaid.used = row.unpaid.total - buf.unpaidRemain;
        row.lastUpdated = new Date().toISOString();
        delete EDIT[id];
        filter();
      }
      return;
    }

    const view = e.target.closest('.view');
    if (view){
      openModal(view.dataset.id);
    }
  });

  tbody.addEventListener('input', (e)=>{
    if (e.target.matches('input[type="number"]')){
      const id=e.target.dataset.id, f=e.target.dataset.f;
      if (!EDIT[id]) return;
      EDIT[id][f] = e.target.value;
    }
  });

  // modal
  const modal = document.getElementById('modal');
  const meta = document.getElementById('meta');
  const breakdown = document.getElementById('breakdown');
  const validity = document.getElementById('validity');
  document.getElementById('close').addEventListener('click', ()=> modal.style.display='none');

  function openModal(empId){
    const r = DATA.find(x=>x.empId===empId);
    const A = r.annual, S = r.sick, U = r.unpaid;
    meta.innerHTML = `<strong>${r.name}</strong> (${r.empId}) — ${r.dept}<br>
      <span class="muted">Last Updated: ${new Date(r.lastUpdated).toLocaleString()}</span>`;
    breakdown.innerHTML = `
      <tr><td style="padding:8px;">Annual</td>
          <td style="padding:8px;text-align:right">${A.total.toFixed(1)}</td>
          <td style="padding:8px;text-align:right">${A.used.toFixed(1)}</td>
          <td style="padding:8px;text-align:right"><strong>${(A.total-A.used).toFixed(1)}</strong></td></tr>
      <tr><td style="padding:8px;">Sick</td>
          <td style="padding:8px;text-align:right">${S.total.toFixed(1)}</td>
          <td style="padding:8px;text-align:right">${S.used.toFixed(1)}</td>
          <td style="padding:8px;text-align:right"><strong>${(S.total-S.used).toFixed(1)}</strong></td></tr>
      <tr><td style="padding:8px;">Unpaid</td>
          <td style="padding:8px;text-align:right">${U.total.toFixed(1)}</td>
          <td style="padding:8px;text-align:right">${U.used.toFixed(1)}</td>
          <td style="padding:8px;text-align:right"><strong>${(U.total-U.used).toFixed(1)}</strong></td></tr>
    `;
    validity.innerHTML = `Balances valid until <strong>${new Date(r.validUntil).toLocaleDateString()}</strong>.`;
    modal.style.display = 'flex';
  }

  // init
  filter();
  /* ================== Leave balance logic ================== */
  const $ = (s)=>document.querySelector(s);
  const tbody = $('#tbl tbody');

  function render(rows) {
    tbody.innerHTML = '';
    if (!rows.length) {
      tbody.innerHTML = '<tr><td colspan="7">No records.</td></tr>';
      return;
    }
    rows.forEach(r => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${r.name}</strong><br><span class="muted">${r.id}</span></td>
        <td>${r.dept}</td>
        <td>${r.annual}</td>
        <td>${r.sick}</td>
        <td>${r.unpaid}</td>
        <td><button class="btn-ghost" data-id="${r.id}">View</button></td>
        <td class="actions">
          <input type="number" min="0" data-field="annual" data-id="${r.id}" value="${r.annual}" />
          <input type="number" min="0" data-field="sick" data-id="${r.id}" value="${r.sick}" />
        </td>
      `;
      tbody.appendChild(tr);
    });
    bindActions(rows);
  }

  function bindActions(rows) {
    document.querySelectorAll('.btn-ghost').forEach(btn => {
      btn.addEventListener('click', () => {
        const row = rows.find(x => x.id === btn.dataset.id);
        if (!row) return;
        openModal(row);
      });
    });
  }

  async function loadData() {
    tbody.innerHTML = '<tr><td colspan="7">Loading...</td></tr>';
    const params = new URLSearchParams({
      department: $('#dept').value,
      q: $('#q').value.trim(),
    });
    try {
      const resp = await fetch(`${ENDPOINT}?${params.toString()}`, { headers: { 'Accept': 'application/json' }});
      if (!resp.ok) throw new Error('Failed to load balances');
      const json = await resp.json();
      render(Array.isArray(json.data) ? json.data : []);
    } catch (err) {
      tbody.innerHTML = `<tr><td colspan="7">Error: ${err.message}</td></tr>`;
    }
  }

  $('#apply').addEventListener('click', loadData);
  $('#clear').addEventListener('click', () => {
    $('#q').value = '';
    $('#dept').value = '';
    loadData();
  });

  /* Modal */
  const modal = document.getElementById('modal');
  const meta = document.getElementById('meta');
  const breakdown = document.getElementById('breakdown');
  const validity = document.getElementById('validity');
  document.getElementById('close').addEventListener('click', () => modal.style.display='none');

  function openModal(row) {
    meta.textContent = `${row.name} (${row.id}) — ${row.dept}`;
    breakdown.innerHTML = row.detail.map(d => `
      <tr>
        <td style="padding:8px;">${d.type}</td>
        <td style="padding:8px;text-align:right">${d.total}</td>
        <td style="padding:8px;text-align:right">${d.used}</td>
        <td style="padding:8px;text-align:right">${d.total - d.used}</td>
      </tr>
    `).join('');
    validity.textContent = 'Validity: current plan year (demo data)';
    modal.style.display = 'flex';
  }

  // initial load
  loadData();
});
</script>

</body>
</html>
