<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Employee - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
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

    <main class="employee-page">
      <div class="breadcrumb">Home > Employee Management > Add Employee</div>
      <h2>Add New Employee</h2>
      <p class="subtitle">Register new employees and view overall employee data insights.</p>

      <div class="summary-cards">
        <div class="card"><h4>Total Employees</h4><p>128</p></div>
        <div class="card"><h4>Active Employees</h4><p>117</p></div>
        <div class="card"><h4>Departments</h4><p>8</p></div>
        <div class="card"><h4>On Leave</h4><p>6</p></div>
      </div>

      <div class="filter-bar">
        <input type="text" placeholder="Search employee name..." />
        <select>
          <option value="">All Departments</option>
          <option value="HR">Human Resources</option>
          <option value="IT">IT Department</option>
          <option value="Finance">Finance</option>
          <option value="Marketing">Marketing</option>
        </select>
        <button class="btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
      </div>

      <div class="content-section">
        <h3>Employee List</h3>
        <table>
          <thead>
            <tr>
              <th>Name</th><th>Department</th><th>Position</th><th>Status</th><th>Email</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Jane Smith</td><td>Finance</td><td>Accountant</td><td>Active</td><td>jane.smith@hrms.com</td>
              <td><button class="btn-primary">View Profile</button></td>
            </tr>
            <tr>
              <td>David Lee</td><td>IT Department</td><td>Software Engineer</td><td>Active</td><td>david.lee@hrms.com</td>
              <td><button class="btn-primary">View Profile</button></td>
            </tr>
            <tr>
              <td>Anna Wong</td><td>Marketing</td><td>Marketing Assistant</td><td>On Leave</td><td>anna.wong@hrms.com</td>
              <td><button class="btn-primary">View Profile</button></td>
            </tr>
          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
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
