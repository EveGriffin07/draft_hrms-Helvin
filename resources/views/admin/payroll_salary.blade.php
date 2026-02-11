<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Salary Calculation - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body { background:#f5f7fb; }
    main { padding:28px 32px; }
    .card {
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:12px;
      padding:16px;
      margin-bottom:16px;
      box-shadow:0 12px 28px rgba(15,23,42,0.08);
    }
    h2 { margin-bottom:4px; }
    .subtitle { color:#6b7280; }
    .grid { display:flex; gap:12px; flex-wrap:wrap; }
    .grid > * { flex:1 1 220px; }
    label { display:block; margin-bottom:6px; font-weight:600; color:#0f172a; }
    input, select, button {
      width:100%;
      padding:10px 12px;
      border:1px solid #d1d5db;
      border-radius:10px;
      background:#fff;
      font-size:14px;
    }
    button { cursor:pointer; font-weight:700; }
    .btn-primary { background:#1f78f0; color:#fff; border-color:#1f78f0; box-shadow:0 10px 20px rgba(31,120,240,0.25); }
    .btn-ghost { background:#fff; color:#1f2937; }
    table { width:100%; border-collapse:collapse; background:#fff; }
    th, td { padding:12px 14px; border-bottom:1px solid #e5e7eb; text-align:left; }
    thead th { background:#f8fafc; color:#0f172a; }
    .num { text-align:right; }
    .muted { color:#6b7280; font-size:13px; }
    .chip { display:inline-block; padding:6px 10px; background:#e0f2fe; color:#0c4a6e; border-radius:999px; font-weight:600; }
    .modal { position:fixed; inset:0; background:rgba(0,0,0,0.45); display:none; align-items:center; justify-content:center; }
    .sheet { background:#fff; border-radius:12px; padding:18px; width:min(760px,92vw); box-shadow:0 16px 40px rgba(0,0,0,0.25); }
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
      <div class="breadcrumb">Home > Payroll > Salary Calculation</div>
      <h2>Salary Calculation</h2>
      <p class="subtitle">Gross -> Deductions -> Net, with clear rates, details, and quick adjustments.</p>

      <div class="card">
        <h3 style="margin:0 0 6px;">Rates & Assumptions (Demo)</h3>
        <ul class="muted" style="margin:0 0 6px 18px; line-height:1.5">
          <li><strong>OT Pay</strong> = OT Hours x OT Rate</li>
          <li><strong>Gross</strong> = Base + Allowances + OT + Salary Increase</li>
          <li><strong>Deductions</strong> = Penalty + Manual Penalty + EPF/Tax</li>
          <li><strong>Net</strong> = Gross - Deductions</li>
        </ul>
        <div class="muted">Replace with your company's real policy (EPF %, tax rules, etc.).</div>
      </div>

      <div class="card">
        <div class="grid">
          <div>
            <label>Department</label>
            <select id="dept">
              <option value="">All</option>

              @foreach($departments as $dept)
                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label>Start</label>
            <input type="date" id="start">
          </div>
          <div>
            <label>End</label>
            <input type="date" id="end">
          </div>
          <div style="align-self:end; display:flex; gap:8px;">
            <button class="btn-primary" id="apply">Apply</button>
            <button class="btn-ghost" id="clear">Clear</button>
          </div>
        </div>
      </div>

      <div class="card">
        <h3 style="margin:0 0 8px;">Adjustments</h3>
        <div class="grid">
          <div>
            <label>Employee</label>
            <select id="adj-emp"></select>
          </div>
          <div>
            <label>OT Rate (RM/hr)</label>
            <input type="number" id="adj-otrate" step="0.01" placeholder="e.g., 22">
          </div>
          <div>
            <label>Manual Penalty (RM)</label>
            <input type="number" id="adj-penalty" step="0.01" placeholder="e.g., 50">
          </div>
          <div>
            <label>Salary Increase</label>
            <div style="display:flex; gap:8px;">
              <select id="adj-inc-type" style="flex:0 0 90px;">
                <option value="rm">+ RM</option>
                <option value="pct">+ %</option>
              </select>
              <input type="number" id="adj-inc-value" step="0.01" placeholder="e.g., 200 or 5">
            </div>
          </div>
          <div style="align-self:end; display:flex; gap:8px;">
            <button class="btn-primary" id="adj-apply">Apply to Employee</button>
            <button class="btn-ghost" id="adj-reset">Reset Employee</button>
          </div>
        </div>
      </div>

      <div class="card">
        <table id="tbl">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Department</th>
              <th class="num">Base</th>
              <th class="num">Allowance</th>
              <th class="num">OT (hrs x rate)</th>
              <th class="num">Salary Increase</th>
              <th class="num">Penalty</th>
              <th class="num">Manual Penalty</th>
              <th class="num">EPF/Tax</th>
              <th class="num">Net</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <footer style="text-align:center; color:#94a3b8; font-size:12px;">Ac 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <div class="modal" id="modal">
    <div class="sheet">
      <h3 style="margin:0 0 6px;">Salary Details</h3>
      <div id="meta" class="muted" style="margin-bottom:10px"></div>
      <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb;">
        <tbody id="breakdown"></tbody>
      </table>
      <div style="margin-top:12px; text-align:right;">
        <button class="btn-ghost" id="close">Close</button>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {

    const ENDPOINT = "{{ route('admin.payroll.salary.data') }}";
    let DATA = [];
    const ADJ = {};

    const $ = (s) => document.querySelector(s);
    const tbody = $('#tbl tbody');
    const empSel = $('#adj-emp');


    const money = (n) => Number(n ?? 0).toLocaleString('en-MY', { minimumFractionDigits:2, maximumFractionDigits:2 });
    const inRange = (d, s, e) => {
      const x = new Date(d);
      if (s && x < new Date(s)) return false;
      if (e && x > new Date(e)) return false;
      return true;
    };


    function refillEmpSelect() {
      empSel.innerHTML = '';
      DATA.forEach(emp => {
        const opt = document.createElement('option');
        opt.value = emp.id;
        opt.textContent = `${emp.name} (${emp.id}) - ${emp.dept}`;
        empSel.appendChild(opt);
      });
    }

    function calc(e) {
      const a = ADJ[e.id] || {};
      const otRate = a.otRate !== undefined ? Number(a.otRate) : e.otRate;
      const otPay = e.otHrs * otRate;
      let salaryInc = 0;
      if (a.incType === 'rm' && a.incValue) salaryInc = Number(a.incValue);
      if (a.incType === 'pct' && a.incValue) salaryInc = e.base * (Number(a.incValue) / 100);
      const manualPenalty = a.manualPenalty ? Number(a.manualPenalty) : 0;
      const gross = e.base + e.allow + otPay + salaryInc;
      const ded = e.penalty + manualPenalty + e.epfTax;
      const net = gross - ded;
      return { otRate, otPay, salaryInc, manualPenalty, gross, ded, net };
    }

    function render(rows) {
      tbody.innerHTML = '';
      if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="11">No records.</td></tr>';
        return;
      }
      rows.forEach(e => {
        const c = calc(e);
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${e.name}</strong><br><span class="muted">${e.id}</span></td>
          <td>${e.dept}</td>
          <td class="num">${money(e.base)}</td>
          <td class="num" title="${e.allowItems.map(i => i[0] + ': RM ' + i[1]).join(', ')}">${money(e.allow)}</td>
          <td class="num">${e.otHrs} x ${money(c.otRate)} = ${money(c.otPay)}</td>
          <td class="num">${c.salaryInc ? ('+ ' + money(c.salaryInc)) : '-'}</td>
          <td class="num">-${money(e.penalty)}</td>
          <td class="num">-${money(c.manualPenalty)}</td>
          <td class="num">-${money(e.epfTax)}</td>
          <td class="num"><strong>${money(c.net)}</strong></td>
          <td><button class="btn-ghost" data-id="${e.id}">View</button></td>
        `;
        tbody.appendChild(tr);
      });
      document.querySelectorAll('[data-id]').forEach(btn => {
        btn.addEventListener('click', () => {
          const row = rows.find(x => x.id === btn.dataset.id);
          if (row) openModal(row);
        });
      });
    }


    async function loadData() {
      tbody.innerHTML = '<tr><td colspan="11">Loading...</td></tr>';
      const params = new URLSearchParams({
        department: $('#dept').value,
        start: $('#start').value,
        end: $('#end').value,
      });
      try {
        const resp = await fetch(`${ENDPOINT}?${params.toString()}`, { headers: { 'Accept': 'application/json' }});
        if (!resp.ok) throw new Error('Failed to load salaries');
        const json = await resp.json();
        DATA = Array.isArray(json.data) ? json.data : [];
        refillEmpSelect();
        applyFilters();
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="11">Error: ${err.message}</td></tr>`;
      }
    }

    function applyFilters() {
      const d = $('#dept').value;
      const s = $('#start').value;
      const e = $('#end').value;
      const rows = DATA.filter(x => (!d || x.dept === d) && inRange(x.last, s, e));
      render(rows);
    }


    $('#apply').addEventListener('click', loadData);
    $('#clear').addEventListener('click', () => {
      $('#dept').value = '';
      $('#start').value = '';
      $('#end').value = '';
      applyFilters();
    });

    $('#adj-apply').addEventListener('click', () => {
      const id = $('#adj-emp').value;
      ADJ[id] = {
        otRate: $('#adj-otrate').value !== '' ? Number($('#adj-otrate').value) : undefined,
        manualPenalty: $('#adj-penalty').value !== '' ? Number($('#adj-penalty').value) : 0,
        incType: $('#adj-inc-value').value !== '' ? $('#adj-inc-type').value : undefined,
        incValue: $('#adj-inc-value').value !== '' ? Number($('#adj-inc-value').value) : undefined,
      };
      applyFilters();
    });

    $('#adj-reset').addEventListener('click', () => {
      const id = $('#adj-emp').value;
      delete ADJ[id];
      $('#adj-otrate').value = '';
      $('#adj-penalty').value = '';
      $('#adj-inc-type').value = 'rm';
      $('#adj-inc-value').value = '';
      applyFilters();
    });

    const modal = document.getElementById('modal');
    const meta = document.getElementById('meta');
    const breakdown = document.getElementById('breakdown');
    document.getElementById('close').addEventListener('click', () => modal.style.display = 'none');

    function openModal(e) {
      const c = calc(e);
      meta.innerHTML = `<strong>${e.name}</strong> (${e.id}) - ${e.dept}<br>
        <span class="muted">
          Net = (Base + Allowances + OT Hours x OT Rate + Salary Increase) - (Penalty + Manual Penalty + EPF/Tax)
        </span>`;

      breakdown.innerHTML = `
        <tr><td style="padding:8px;">Base Salary</td><td style="padding:8px;text-align:right">RM ${money(e.base)}</td></tr>
        <tr><td style="padding:8px;">Allowances</td><td style="padding:8px;text-align:right">RM ${money(e.allow)}</td></tr>
        <tr><td style="padding:0;" colspan="2">
          <table style="width:100%;border-collapse:collapse;">
            ${e.allowItems.map(i => `<tr><td style="padding:6px 8px 6px 22px;" class="muted">- ${i[0]}</td><td style="padding:6px 8px;text-align:right" class="muted">RM ${money(i[1])}</td></tr>`).join('')}
          </table>
        </td></tr>
        <tr><td style="padding:8px;">Overtime</td>
            <td style="padding:8px;text-align:right">(${e.otHrs} hrs x RM ${money(c.otRate)}) = <strong>RM ${money(c.otPay)}</strong></td></tr>
        <tr><td style="padding:8px;">Salary Increase</td><td style="padding:8px;text-align:right">${c.salaryInc ? ('RM ' + money(c.salaryInc)) : '-'}</td></tr>
        <tr><td style="padding:8px;">Penalty (aggregated)</td><td style="padding:8px;text-align:right">- RM ${money(e.penalty)}</td></tr>
        <tr><td style="padding:8px;">Manual Penalty</td><td style="padding:8px;text-align:right">- RM ${money(c.manualPenalty)}</td></tr>
        <tr><td style="padding:8px;">EPF/Tax (aggregated)</td><td style="padding:8px;text-align:right">- RM ${money(e.epfTax)}</td></tr>
        <tr><td style="padding:8px;"><strong>Gross Pay</strong></td><td style="padding:8px;text-align:right"><strong>RM ${money(c.gross)}</strong></td></tr>
        <tr><td style="padding:8px;"><strong>Deductions</strong></td><td style="padding:8px;text-align:right"><strong>- RM ${money(c.ded)}</strong></td></tr>
        <tr><td style="padding:8px;background:#f8fafc"><strong>Net Pay</strong></td><td style="padding:8px;text-align:right;background:#f8fafc"><strong>RM ${money(c.net)}</strong></td></tr>
      `;

      modal.style.display = 'flex';
    }


    loadData();
  });
  </script>
</body>
</html>

