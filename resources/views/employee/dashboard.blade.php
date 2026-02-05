<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Dashboard - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/hrms-theme.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <style>
    body { background:#f3f6fb; }
    .dashboard-shell { display:flex; min-height:calc(100vh - 64px); }
    .dashboard-main { flex:1; padding:28px 32px; max-width:100%; margin:0 auto; }

    .hero {
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:14px;
      margin-bottom:16px;
    }
    .breadcrumb { font-size:12px; color:#9ca3af; margin-bottom:6px; }
    .hero-title { font-size:21px; font-weight:700; color:#0f172a; }
    .hero-subtitle { color:#6b7280; font-size:13px; margin-top:4px; }
    .hero-actions { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    .chip {
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:999px;
      background:#f1f5f9;
      color:#0f172a;
      border:1px solid #e2e8f0;
      font-weight:600;
      box-shadow:0 8px 16px rgba(148,163,184,0.22);
    }
    .chip i { color:#2563eb; }
    .pill-btn {
      border:none;
      background:linear-gradient(135deg, #1f78f0, #3a66ff);
      color:#fff;
      padding:10px 16px;
      border-radius:999px;
      font-weight:700;
      box-shadow:0 12px 28px rgba(49,130,246,0.3);
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      gap:8px;
      transition:transform 0.1s ease, box-shadow 0.15s ease;
    }
    .pill-btn:hover { transform:translateY(-1px); box-shadow:0 16px 34px rgba(37,99,235,0.32); }

    .kpi-grid {
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(210px,1fr));
      gap:12px;
      margin-bottom:16px;
    }
    .kpi-card {
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:14px;
      padding:14px;
      display:flex;
      gap:12px;
      align-items:flex-start;
      box-shadow:0 6px 16px rgba(15,23,42,0.06);
      min-height:88px;
    }
    .kpi-icon {
      width:40px; height:40px;
      border-radius:12px;
      display:grid; place-items:center;
      color:#0f172a;
      background:#e0e7ff;
      flex-shrink:0;
      box-shadow:0 8px 18px rgba(99,102,241,0.22);
    }
    /* Dynamic Colors based on status can be handled here if needed */
    .kpi-card.present .kpi-icon { background:#dcfce7; color:#15803d; box-shadow:0 8px 18px rgba(34,197,94,0.2); }
    .kpi-card.absent .kpi-icon { background:#fee2e2; color:#ef4444; box-shadow:0 8px 18px rgba(239,68,68,0.2); }
    
    .kpi-card:nth-child(2) .kpi-icon { background:#e0f2fe; color:#0ea5e9; box-shadow:0 8px 18px rgba(14,165,233,0.2); }
    .kpi-card:nth-child(3) .kpi-icon { background:#fef9c3; color:#d97706; box-shadow:0 8px 18px rgba(234,179,8,0.2); }
    .kpi-card:nth-child(4) .kpi-icon { background:#ede9fe; color:#7c3aed; box-shadow:0 8px 18px rgba(124,58,237,0.2); }
    
    .kpi-card.present .kpi-value { color:#0f172a; }
    .kpi-card.leave .kpi-value { color:#0ea5e9; }
    .kpi-card.training .kpi-value { color:#059669; }
    .kpi-card.payslip .kpi-value { color:#7c3aed; }
    
    .kpi-label { font-size:12px; text-transform:uppercase; letter-spacing:0.05em; color:#9ca3af; }
    .kpi-value { font-size:22px; font-weight:800; color:#0f172a; line-height:1.2; }
    .kpi-meta { display:flex; align-items:center; gap:6px; color:#16a34a; font-weight:600; font-size:13px; }
    .kpi-meta i { color:inherit; }
    .meta-blue { color:#1d4ed8; }
    .meta-green { color:#16a34a; }
    .meta-purple { color:#7c3aed; }
    .kpi-meta .muted { color:#6b7280; font-weight:500; }
    .status-dot {
      width:8px; height:8px; border-radius:50%;
      background:#16a34a;
      box-shadow:0 0 0 6px rgba(22,163,74,0.12);
    }

    .analytics-row {
      display:grid;
      grid-template-columns:2fr 1fr;
      gap:12px;
    }
    @media (max-width:1080px) { .analytics-row { grid-template-columns:1fr; } }

    .panel {
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:14px;
      padding:16px;
      box-shadow:0 10px 26px rgba(15,23,42,0.08);
    }
    .panel header { display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
    .panel title { font-weight:700; color:#0f172a; font-size:15px; }
    .panel .caption { color:#9ca3af; font-size:12px; }
    .panel .label {
      display:flex;
      align-items:center;
      gap:8px;
      font-weight:700;
      color:#0f172a;
      font-size:14px;
    }
    .panel .label .dot {
      width:10px; height:10px; border-radius:50%;
      background:#2563eb;
      box-shadow:0 0 0 6px rgba(37,99,235,0.08);
      display:inline-block;
    }

    .chart-shell { margin-top:6px; background:#f8fafc; border-radius:12px; padding:12px; border:1px solid #e5e7eb; }
    .chart-shell svg { width:100%; height:220px; }

    .donut-wrap {
      display:flex;
      gap:14px;
      align-items:center;
      flex-wrap:wrap;
    }
    .donut {
      --a:45;
      --s:25;
      --c:15;
      --e:10;
      --o:5;
      width:180px; height:180px;
      border-radius:50%;
      background:conic-gradient(
        #1d4ed8 0 calc(var(--a)*1%),
        #f97316 calc(var(--a)*1%) calc((var(--a)+var(--s))*1%),
        #22c55e calc((var(--a)+var(--s))*1%) calc((var(--a)+var(--s)+var(--c))*1%),
        #facc15 calc((var(--a)+var(--s)+var(--c))*1%) calc((var(--a)+var(--s)+var(--c)+var(--e))*1%),
        #a855f7 calc((var(--a)+var(--s)+var(--c)+var(--e))*1%) 100%
      );
      position:relative;
      display:grid;
      place-items:center;
      box-shadow:0 10px 26px rgba(15,23,42,0.08);
    }
    .donut-hole {
      width:100px; height:100px;
      background:#fff;
      border-radius:50%;
      display:grid;
      place-items:center;
      text-align:center;
      border:1px solid #e5e7eb;
      box-shadow:inset 0 0 0 1px #f3f4f6;
    }
    .donut-hole .value { font-weight:800; font-size:22px; color:#0f172a; }
    .donut-hole .label { font-size:12px; color:#9ca3af; }

    .legend { list-style:none; padding:0; margin:0; display:grid; gap:8px; }
    .legend li { display:flex; align-items:center; gap:10px; color:#0f172a; font-weight:600; }
    .legend span.swatch { width:12px; height:12px; border-radius:4px; display:inline-block; }
    .legend .muted { color:#6b7280; font-weight:500; }

    /* Reports & analytics */
    .reports-card {
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:14px;
      box-shadow:0 14px 30px rgba(15,23,42,0.08);
      padding:18px;
      margin-bottom:16px;
    }
    .pill-row { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
    .pill { padding:8px 12px; border-radius:999px; border:1px solid #d1d5db; background:#f8fafc; color:#0f172a; font-weight:600; cursor:pointer; }
    .pill.active { background:#1f78f0; color:#fff; border-color:#1f78f0; }
    .export-row { display:flex; gap:8px; }
    .export-btn { padding:8px 12px; border-radius:10px; border:1px solid #d1d5db; background:#fff; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:8px; }
    .filter-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:10px; margin-top:12px; }
    .mini-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:12px; margin-top:14px; }
    .mini-card { background:#f8fafc; border:1px solid #e5e7eb; border-radius:12px; padding:12px; }
    .mini-card h4 { margin:0 0 6px; font-size:13px; color:#4b5563; }
    .mini-card .value { font-size:20px; font-weight:800; color:#0f172a; }
    .mini-card .muted { margin:0; }
    .chart-box { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:14px; box-shadow:0 8px 20px rgba(15,23,42,0.06); width:100%; }
    .chart-canvas { width:100%; height:320px; display:block; }
    .chart-canvas.sm { height:300px; }
    .table-lite { width:100%; border-collapse:collapse; margin-top:10px; }
    .table-lite th, .table-lite td { padding:10px 12px; border-bottom:1px solid #e5e7eb; text-align:left; font-size:13px; }
    .table-lite thead th { background:#f8fafc; color:#0f172a; }
    .two-col { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    @media (max-width:960px) { .two-col { grid-template-columns:1fr; } }
    .report-switch { display:flex; gap:8px; flex-wrap:wrap; margin:12px 0; }
    .report-btn { padding:10px 14px; border-radius:12px; border:1px solid #d1d5db; background:#f8fafc; color:#0f172a; font-weight:700; cursor:pointer; box-shadow:0 8px 18px rgba(15,23,42,0.06); }
    .report-btn.active { background:#1f78f0; color:#fff; border-color:#1f78f0; box-shadow:0 12px 26px rgba(31,120,240,0.28); }
    .report-section { display:none; }
    .report-section.active { display:block; }
    footer { text-align:center; color:#94a3b8; font-size:12px; padding:18px 0 6px; }
  </style>
</head>
<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
        <i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name }}
    </div>
  </header>

  <div class="container dashboard-shell">
    
    {{-- FIX: Point to the new sidebar file --}}
    @include('employee.layout.sidebar')

    <main class="dashboard-main">
      <div class="hero">
        <div>
          <div class="breadcrumb">Home > Employee Dashboard</div>
          <div class="hero-title">Employee Dashboard</div>
          <div class="hero-subtitle">Personal overview of attendance, leave, payslips, training, and announcements.</div>
        </div>
        <div class="hero-actions">
          <div class="chip"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::now()->format('d M Y') }}</div>
          <button class="pill-btn"><i class="fa-solid fa-plane-up"></i> Request Leave</button>
        </div>
      </div>

      <section class="kpi-grid">
        <article class="kpi-card {{ $todayAttendance ? 'present' : 'absent' }}">
          <div class="kpi-icon"><i class="fa-solid fa-user-check"></i></div>
          <div>
            <div class="kpi-label">Attendance Today</div>
            <div class="kpi-value">{{ $todayAttendance ? 'Present' : 'Absent' }}</div>
            <div class="kpi-meta meta-blue">
                <span class="status-dot"></span> 
                @if($todayAttendance && $todayAttendance->clock_in_time)
                    Clocked in {{ \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('h:i A') }}
                @else
                    Not clocked in
                @endif
            </div>
          </div>
        </article>

        <article class="kpi-card leave">
          <div class="kpi-icon"><i class="fa-solid fa-umbrella-beach"></i></div>
          <div>
            <div class="kpi-label">Leave Balance</div>
            <div class="kpi-value">{{ $leaveBalance }} days</div>
            <div class="kpi-meta meta-green"><i class="fa-solid fa-leaf"></i> Annual Remaining</div>
          </div>
        </article>

        <article class="kpi-card training">
          <div class="kpi-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
          <div>
            <div class="kpi-label">Upcoming Training</div>
            <div class="kpi-value">{{ $upcomingTrainings }} sessions</div>
            <div class="kpi-meta meta-green"><i class="fa-regular fa-calendar-check"></i> Check schedule</div>
          </div>
        </article>

        <article class="kpi-card payslip">
          <div class="kpi-icon"><i class="fa-solid fa-file-lines"></i></div>
          <div>
            <div class="kpi-label">Payslips</div>
            <div class="kpi-value">
                @if($latestPayslip)
                    {{ $latestPayslip->period->period_month ?? 'N/A' }}
                @else
                    None
                @endif
            </div>
            <div class="kpi-meta meta-purple">
                <i class="fa-solid fa-bolt"></i> 
                @if($latestPayslip)
                    Net: RM{{ number_format($latestPayslip->net_salary, 2) }}
                @else
                    No records
                @endif
            </div>
          </div>
        </article>
      </section>

      <div class="analytics-row">
        <section class="panel">
          <header>
            <div class="label"><span class="dot"></span> Attendance (Last 7 Days)</div>
            <span class="caption">Mon - Sun</span>
          </header>
          <div class="chart-shell">
            <canvas id="chart-attendance-7d" class="chart-canvas"></canvas>
          </div>
        </section>

        <section class="panel">
          <header>
            <div class="label"><span class="dot" style="background:#1d4ed8; box-shadow:0 0 0 6px rgba(29,78,216,0.08);"></span> Leave Breakdown</div>
            <span class="caption">Annual, sick, casual, emergency, other</span>
          </header>
          <div class="donut-wrap">
            <div class="donut">
              <div class="donut-hole">
                <div class="value">18</div>
                <div class="label">days</div>
              </div>
            </div>
            <ul class="legend">
              <li><span class="swatch" style="background:#1d4ed8;"></span> Annual <span class="muted">8 days</span></li>
              <li><span class="swatch" style="background:#f97316;"></span> Sick <span class="muted">4.5 days</span></li>
              <li><span class="swatch" style="background:#22c55e;"></span> Casual <span class="muted">2 days</span></li>
              <li><span class="swatch" style="background:#facc15;"></span> Emergency <span class="muted">1 day</span></li>
              <li><span class="swatch" style="background:#a855f7;"></span> Other <span class="muted">0.5 day</span></li>
            </ul>
        </div>
      </section>
      </div>

      <section class="reports-card">
        <div style="display:flex; justify-content:space-between; gap:10px; align-items:center; flex-wrap:wrap;">
          <div>
            <h3 style="margin:0;">Central Reports & Analytics</h3>
            <p class="muted" style="margin:4px 0 0;">View consolidated metrics from attendance, overtime, and leave modules with quick exports.</p>
          </div>
          <div class="export-row">
            <button class="export-btn"><i class="fa-solid fa-file-csv"></i> Export CSV</button>
            <button class="export-btn"><i class="fa-regular fa-file-pdf"></i> Export PDF</button>
          </div>
        </div>

        <div class="report-switch">
          <button class="report-btn active" data-section="attendance">Attendance</button>
          <button class="report-btn" data-section="overtime">Overtime</button>
          <button class="report-btn" data-section="leave">Leave</button>
          <button class="report-btn" data-section="predictive">Predictive</button>
          <button class="report-btn" data-section="recruitment">Recruitment</button>
          <button class="report-btn" data-section="training">Training</button>
          <button class="report-btn" data-section="appraisal">Appraisal / KPI</button>
          <button class="report-btn" data-section="onboarding">Onboarding</button>
        </div>

        <div class="filter-row">
          <div>
            <label style="font-weight:600; color:#0f172a;">Period</label>
            <select><option>Last 30 days</option></select>
          </div>
          <div>
            <label style="font-weight:600; color:#0f172a;">From</label>
            <input type="date" value="2025-11-14">
          </div>
          <div>
            <label style="font-weight:600; color:#0f172a;">To</label>
            <input type="date" value="2025-12-14">
          </div>
        </div>

        <div class="mini-cards">
          <div class="mini-card">
            <h4>Attendance Rate</h4>
            <div class="value">67%</div>
            <p class="muted">Present vs total days</p>
          </div>
          <div class="mini-card">
            <h4>Overtime Hours</h4>
            <div class="value">26h</div>
            <p class="muted">Approved OT hours</p>
          </div>
          <div class="mini-card">
            <h4>Leave Used</h4>
            <div class="value">9 days</div>
            <p class="muted">Remaining 17 days</p>
          </div>
          <div class="mini-card">
            <h4>Risk Signal</h4>
            <div class="value">Low</div>
            <p class="muted">Score 0 (late 1, absent 1, OT 2h)</p>
          </div>
        </div>
      </section>

      <section class="reports-card report-section active" id="section-attendance">
        <h4 style="margin:0 0 6px;"><i class="fa-solid fa-square-poll-vertical"></i> Attendance Trend</h4>
        <p class="muted" style="margin:0 0 10px;">Stacked attendance status by day. Inspired by dashboard overview charts.</p>
        <div class="chart-box" style="margin-bottom:12px;">
          <canvas id="chart-attendance-trend" class="chart-canvas sm"></canvas>
        </div>
        <div class="two-col">
          <table class="table-lite">
            <thead><tr><th>Day</th><th>Present</th><th>Late</th><th>Absent</th><th>Leave</th></tr></thead>
            <tbody>
              <tr><td>Mon</td><td>1</td><td>0</td><td>0</td><td>0</td></tr>
              <tr><td>Tue</td><td>1</td><td>0</td><td>0</td><td>0</td></tr>
              <tr><td>Wed</td><td>1</td><td>0</td><td>1</td><td>0</td></tr>
              <tr><td>Thu</td><td>0</td><td>1</td><td>0</td><td>0</td></tr>
              <tr><td>Fri</td><td>1</td><td>0</td><td>0</td><td>0</td></tr>
            </tbody>
          </table>
          <table class="table-lite">
            <thead><tr><th>Highlight</th><th>Count</th></tr></thead>
            <tbody>
              <tr><td>Tue</td><td>1 late</td></tr>
              <tr><td>Thu</td><td>1 absent</td></tr>
            </tbody>
          </table>
        </div>
      </section>

      <p style="text-align:center; color:#4b5563; font-size:12px; margin:10px 0 0;">Figure 4.1 : Employee Dashboard.</p>
      <footer>&copy; 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const groups  = document.querySelectorAll('.sidebar-group');
      const toggles = document.querySelectorAll('.sidebar-toggle');
      const links   = document.querySelectorAll('.submenu a, .sidebar-quick-link');
      const STORAGE_KEY = 'hrms_sidebar_open_group';

      const normPath = (u) => {
        const url = new URL(u, location.origin);
        let p = url.pathname
          .replace(/\/index\.php$/i, '')
          .replace(/\/index\.php\//i, '/')
          .replace(/\/+$/ , '');
        return p === '' ? '/' : p;
      };
      const here = normPath(location.href);

      groups.forEach(g => {
        g.classList.remove('open');
        const t = g.querySelector('.sidebar-toggle');
        if (t) t.setAttribute('aria-expanded','false');
      });
      links.forEach(a => a.classList.remove('active'));

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
          // If this is a direct link (like Dashboard), let it navigate
          if (btn.classList.contains('sidebar-quick-link')) return;

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

      // Reports switcher
      const reportButtons = document.querySelectorAll('.report-btn');
      const reportSections = document.querySelectorAll('.report-section');
      reportButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const target = btn.getAttribute('data-section');
          reportButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          let matched = false;
          reportSections.forEach(sec => {
            const active = sec.id === `section-${target}`;
            sec.classList.toggle('active', active);
            if (active) matched = true;
          });
          if (!matched) {
            reportSections.forEach(sec => sec.classList.remove('active'));
          }
        });
      });

      // Chart.js data - adjust values as needed
      const att7Labels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
      const att7Data = [88, 90, 92, 89, 80, 78, 85];
      const trendLabels = ['Mon','Tue','Wed','Thu','Fri'];
      const trendLate = [0, 0, 2, 0, 0];
      const trendAbsent = [0, 0, 0, 1, 0];
      const otLabels = ['Jan','Feb','Mar','Projected'];
      const otHours = [6, 12, 8, 10];
      const otCost = [640, 1080, 720, 900];
      const leaveLabels = ['Annual','Sick','Emergency'];
      const leaveUsed = [6, 2, 1];

      const ctx1 = document.getElementById('chart-attendance-7d');
      if (ctx1) {
        new Chart(ctx1, {
          type: 'line',
          data: {
            labels: att7Labels,
            datasets: [{
              label: 'Present %',
              data: att7Data,
              fill: true,
              backgroundColor: 'rgba(37,99,235,0.15)',
              borderColor: '#2563eb',
              tension: 0.35,
              pointRadius: 5
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: { beginAtZero: true, max: 100, grid: { color: '#e5e7eb' } },
              x: { grid: { display:false } }
            },
            plugins: { legend: { display:false } }
          }
        });
      }

      const ctx2 = document.getElementById('chart-attendance-trend');
      if (ctx2) {
        new Chart(ctx2, {
          type: 'line',
          data: {
            labels: trendLabels,
            datasets: [
              { label:'Late', data: trendLate, borderColor:'#f97316', backgroundColor:'rgba(249,115,22,0.2)', tension:0.35, fill:true },
              { label:'Absent', data: trendAbsent, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,0.2)', tension:0.35, fill:true }
            ]
          },
          options: {
            responsive:true,
            maintainAspectRatio:false,
            scales:{
              y:{ beginAtZero:true, ticks:{ stepSize:1 }, grid:{ color:'#e5e7eb' } },
              x:{ grid:{ color:'#f3f4f6' } }
            },
            plugins:{ legend:{ display:true, position:'top' } }
          }
        });
      }

      const ctx3 = document.getElementById('chart-overtime');
      if (ctx3) {
        new Chart(ctx3, {
          type:'line',
          data:{
            labels: otLabels,
            datasets:[{
              label:'OT Cost (RM)',
              data: otCost,
              borderColor:'#1f78f0',
              backgroundColor:'rgba(31,120,240,0.15)',
              tension:0.35,
              fill:true,
              pointRadius:5
            }]
          },
          options:{
            responsive:true,
            maintainAspectRatio:false,
            scales:{
              y:{ beginAtZero:true, grid:{ color:'#e5e7eb' } },
              x:{ grid:{ display:false } }
            },
            plugins:{ legend:{ display:false } }
          }
        });
      }

      const ctx4 = document.getElementById('chart-leave-usage');
      if (ctx4) {
        new Chart(ctx4, {
          type:'bar',
          data:{
            labels: leaveLabels,
            datasets:[{
              label:'Used',
              data: leaveUsed,
              backgroundColor:'#38bdf8'
            }]
          },
          options:{
            responsive:true,
            maintainAspectRatio:false,
            scales:{
              y:{ beginAtZero:true, grid:{ color:'#e5e7eb' } },
              x:{ grid:{ display:false } }
            },
            plugins:{ legend:{ display:false } }
          }
        });
      }
    });
  </script>
</body>
</html>