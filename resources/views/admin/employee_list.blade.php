<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Employee Overview - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <style>
    /* Page-specific helpers */
    .employee-page .filter-bar { flex-wrap: wrap; gap: 10px; }
    .employee-page .filter-bar .actions { display: flex; gap: 8px; align-items: center; }
    .btn-ghost { background: #fff; border: 1px solid #d1d5db; color: #0f172a; border-radius: 8px; padding: 8px 12px; text-decoration: none; }
    .status-chip { display: inline-block; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .status-active { background: #ecfdf3; color: #15803d; }
    .status-inactive { background: #fef9c3; color: #92400e; }
    .status-terminated { background: #fee2e2; color: #b91c1c; }
    .muted { color: #94a3b8; font-size: 12px; }
    .table-meta { color: #64748b; font-size: 13px; margin-top: 4px; }
  </style>
</head>

<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">

      <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
        <i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name ?? 'HR Admin' }}
      </a>
    </div>
  </header>

  <div class="container">
    @include('admin.layout.sidebar')

    <main class="employee-page">

      <div class="breadcrumb">Home > Employee Management > Employee Overview</div>
      <h2>Employee Overview</h2>
      <p class="subtitle">Live view of every employee record stored in the database.</p>

      <div class="summary-cards">
        <div class="card"><h4>Total Employees</h4><p>{{ $totalEmployees }}</p></div>
        <div class="card"><h4>Active Employees</h4><p>{{ $activeEmployees }}</p></div>
        <div class="card"><h4>Departments</h4><p>{{ $departmentsCount }}</p></div>
        <div class="card"><h4>On Leave Today</h4><p>{{ $onLeave }}</p></div>
      </div>

      @if (session('success'))
        <div style="background:#ecfdf3; border:1px solid #bbf7d0; color:#166534; padding:12px 14px; border-radius:10px; margin-bottom:14px;">
          {{ session('success') }}
        </div>
      @endif

      <form class="filter-bar" method="GET" action="{{ route('admin.employee.list') }}">
        <input type="text" name="q" value="{{ $search }}" placeholder="Search name, email or code..." />

        <select name="department">
          <option value="">All Departments</option>
          @foreach($departments as $dept)
            <option value="{{ $dept->department_id }}" {{ $departmentId == $dept->department_id ? 'selected' : '' }}>
              {{ $dept->department_name }}
            </option>
          @endforeach
        </select>

        <select name="status">
          <option value="">All Statuses</option>
          <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
          <option value="terminated" {{ $status === 'terminated' ? 'selected' : '' }}>Terminated</option>
        </select>

        <div class="actions">
          <button type="submit" class="btn-primary"><i class="fa-solid fa-filter"></i> Apply</button>
          @if($search || $departmentId || $status)
            <a class="btn-ghost" href="{{ route('admin.employee.list') }}"><i class="fa-solid fa-rotate-left"></i> Reset</a>
          @endif
          <a class="btn-primary" href="{{ route('admin.employee.add') }}" style="text-decoration:none;"><i class="fa-solid fa-user-plus"></i> Add Employee</a>
        </div>
      </form>

      <div class="content-section">
        <h3>Employee List</h3>
        <table>
          <thead>
            <tr>

              <th>Employee</th>
              <th>Department</th>
              <th>Position</th>
              <th>Status</th>
              <th>Email</th>
              <th>Hire Date</th>
            </tr>
          </thead>
          <tbody>
            @forelse($employees as $employee)
              <tr>
                <td>
                  <div style="font-weight:600; color:#0f172a;">{{ optional($employee->user)->name ?? 'N/A' }}</div>
                  <div class="muted">{{ $employee->employee_code }}</div>
                </td>
                <td>{{ optional($employee->department)->department_name ?? 'N/A' }}</td>
                <td>{{ optional($employee->position)->position_name ?? 'N/A' }}</td>
                <td>
                  @php
                    $statusClass = match($employee->employee_status) {
                      'active' => 'status-active',
                      'inactive' => 'status-inactive',
                      default => 'status-terminated'
                    };
                  @endphp
                  <span class="status-chip {{ $statusClass }}">{{ ucfirst($employee->employee_status) }}</span>
                </td>
                <td>{{ optional($employee->user)->email ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#94a3b8;">No employees found for the selected filters.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div style="display:flex; justify-content: space-between; align-items:center; margin-top:16px; flex-wrap: wrap; gap: 10px;">
          <div class="muted" style="font-size:13px;">
            Showing {{ $employees->firstItem() ?? 0 }}-{{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} employees
          </div>
          <div>
            {{ $employees->links() }}
          </div>
        </div>
      </div>

      <footer>&copy; 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    /* ===== Unified Sidebar Behavior: single active, single open, persisted ===== */
    const groups  = document.querySelectorAll('.sidebar-group');
    const toggles = document.querySelectorAll('.sidebar-toggle');
    const links   = document.querySelectorAll('.submenu a');
    const STORAGE_KEY = 'hrms_sidebar_open_group';

    // Normalize paths so /x and /x/ match; ignore index.php variants
    const normPath = (u) => {
      const url = new URL(u, location.origin);
      let p = url.pathname
        .replace(/\/index\.php$/i, '')
        .replace(/\/index\.php\//i, '/')
        .replace(/\/+$/, '');
      return p === '' ? '/' : p;
    };
    const here = normPath(location.href);

    // Clear any server-injected open/active to avoid double highlight
    groups.forEach(g => {
      g.classList.remove('open');
      const t = g.querySelector('.sidebar-toggle');
      if (t) t.setAttribute('aria-expanded','false');
    });
    links.forEach(a => a.classList.remove('active'));

    // Choose exactly one active link (exact match, else best prefix)
    let activeLink = null;
    for (const a of links) {
      if (normPath(a.href) === here) { activeLink = a; break; }
    }
    if (!activeLink) {
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

    // Restore previously open group if none opened from active
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

