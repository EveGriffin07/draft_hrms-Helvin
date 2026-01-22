<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register Employee - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>
<body>
<header>
  <div class="title">Web-Based HRMS</div>
  <div class="user-info"><i class="fa-regular fa-bell"></i> &nbsp; HR Admin</div>
</header>

<div class="container">
  @include('partials.sidebar')

  <main class="employee-page">
    <div class="breadcrumb">Home > Employee Management > Register Employee</div>
    <h2>Register New Employee</h2>
    <p class="subtitle">Add new employee information into the system.</p>

    <div class="form-container">
      <form class="form-card" method="POST" action="{{ url('/admin/employee/add') }}">
        @csrf
        <h3><i class="fa-solid fa-user-plus"></i> Employee Information</h3>

        <div class="form-group">
          <label for="employeeName">Full Name <span>*</span></label>
          <input type="text" id="employeeName" name="employeeName" placeholder="e.g., John Doe" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address <span>*</span></label>
          <input type="email" id="email" name="email" placeholder="e.g., john@example.com" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" placeholder="e.g., +60123456789">
        </div>

        <div class="form-group">
          <label for="department">Department <span>*</span></label>
          <select id="department" name="department" required>
            <option value="" disabled selected>Select Department</option>
            <option value="Human Resources">Human Resources</option>
            <option value="Finance">Finance</option>
            <option value="IT">Information Technology</option>
            <option value="Sales">Sales</option>
            <option value="Marketing">Marketing</option>
          </select>
        </div>

        <div class="form-group">
          <label for="designation">Designation <span>*</span></label>
          <input type="text" id="designation" name="designation" placeholder="e.g., Software Engineer" required>
        </div>

        <div class="form-row">
          <div class="form-group half">
            <label for="joinDate">Join Date</label>
            <input type="date" id="joinDate" name="joinDate">
          </div>
          <div class="form-group half">
            <label for="status">Employment Status</label>
            <select id="status" name="status">
              <option value="Active" selected>Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <textarea id="address" name="address" rows="3" placeholder="Enter employee address"></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Register Employee</button>
          <a href="{{ url('/admin/employee/list') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Directory</a>
        </div>
      </form>
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

  // Normalize paths so /x and /x/ match; also drop index.php variance
  const normPath = (u) => {
    const url = new URL(u, location.origin);
    let p = url.pathname
      .replace(/\/index\.php$/i, '')
      .replace(/\/index\.php\//i, '/')
      .replace(/\/+$/, '');
    return p === '' ? '/' : p;
  };
  const here = normPath(location.href);

  // Remove any server-injected states to avoid double highlight
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
    // Fallback to best prefix match (e.g., when hitting a create form under the same section)
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

  // Restore previously open group if nothing opened from active link
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
