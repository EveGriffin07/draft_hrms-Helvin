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
  <div class="user-info">
    <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
<<<<<<< HEAD
        <i class="fa-regular fa-bell"></i> &nbsp; HR Admin
    </a>
</div>
=======
        <i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name ?? 'HR Admin' }}
    </a>
  </div>
>>>>>>> chai-training
</header>

<div class="container">
  @include('admin.layout.sidebar')

  <main class="employee-page">
    <div class="breadcrumb">Home > Employee Management > Register Employee</div>
    <h2>Register New Employee</h2>
    <p class="subtitle">Add new employee information into the system.</p>

    <div class="form-container">
<<<<<<< HEAD
      <form class="form-card" method="POST" action="{{ url('/admin/employee/add') }}">
=======
      @if ($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecdd3; color:#b91c1c; padding:12px 14px; border-radius:10px; margin-bottom:14px;">
          <strong>Fix the following:</strong>
          <ul style="margin:8px 0 0 18px; padding:0; list-style:disc;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div style="background:#ecfdf3; border:1px solid #bbf7d0; color:#166534; padding:12px 14px; border-radius:10px; margin-bottom:14px;">
          {{ session('success') }}
        </div>
      @endif

      <form class="form-card" method="POST" action="{{ route('admin.employee.store') }}">
>>>>>>> chai-training
        @csrf
        <h3><i class="fa-solid fa-user-plus"></i> Employee Information</h3>

        <div class="form-group">
          <label for="employeeName">Full Name <span>*</span></label>
<<<<<<< HEAD
          <input type="text" id="employeeName" name="employeeName" placeholder="e.g., John Doe" required>
=======
          <input type="text" id="employeeName" name="name" value="{{ old('name') }}" placeholder="e.g., John Doe" required>
>>>>>>> chai-training
        </div>

        <div class="form-group">
          <label for="email">Email Address <span>*</span></label>
<<<<<<< HEAD
          <input type="email" id="email" name="email" placeholder="e.g., john@example.com" required>
=======
          <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="e.g., john@example.com" required>
>>>>>>> chai-training
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
<<<<<<< HEAD
          <input type="text" id="phone" name="phone" placeholder="e.g., +60123456789">
=======
          <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g., +60123456789">
>>>>>>> chai-training
        </div>

        <div class="form-group">
          <label for="department">Department <span>*</span></label>
<<<<<<< HEAD
          <select id="department" name="department" required>
            <option value="" disabled selected>Select Department</option>
            <option value="Human Resources">Human Resources</option>
            <option value="Finance">Finance</option>
            <option value="IT">Information Technology</option>
            <option value="Sales">Sales</option>
            <option value="Marketing">Marketing</option>
=======
          <select id="department" name="department_id" required>
            <option value="" disabled {{ old('department_id') ? '' : 'selected' }}>Select Department</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->department_id }}" {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                {{ $dept->department_name }}
              </option>
            @endforeach
>>>>>>> chai-training
          </select>
        </div>

        <div class="form-group">
<<<<<<< HEAD
          <label for="designation">Designation <span>*</span></label>
          <input type="text" id="designation" name="designation" placeholder="e.g., Software Engineer" required>
=======
          <label for="designation">Position <span>*</span></label>
          <select id="designation" name="position_id" required>
            <option value="" disabled {{ old('position_id') ? '' : 'selected' }}>Select Position</option>
            @foreach($positions as $pos)
              <option value="{{ $pos->position_id }}" {{ old('position_id') == $pos->position_id ? 'selected' : '' }}>
                {{ $pos->position_name }}
              </option>
            @endforeach
          </select>
>>>>>>> chai-training
        </div>

        <div class="form-row">
          <div class="form-group half">
<<<<<<< HEAD
            <label for="joinDate">Join Date</label>
            <input type="date" id="joinDate" name="joinDate">
          </div>
          <div class="form-group half">
            <label for="status">Employment Status</label>
            <select id="status" name="status">
              <option value="Active" selected>Active</option>
              <option value="Inactive">Inactive</option>
=======
            <label for="joinDate">Join Date <span>*</span></label>
            <input type="date" id="joinDate" name="hire_date" value="{{ old('hire_date') }}" required>
          </div>
          <div class="form-group half">
            <label for="status">Employment Status</label>
            <select id="status" name="employee_status">
              <option value="active" {{ old('employee_status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ old('employee_status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
              <option value="terminated" {{ old('employee_status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
>>>>>>> chai-training
            </select>
          </div>
        </div>

        <div class="form-group">
<<<<<<< HEAD
          <label for="address">Address</label>
          <textarea id="address" name="address" rows="3" placeholder="Enter employee address"></textarea>
=======
          <label for="baseSalary">Base Salary <span>*</span></label>
          <input type="number" step="0.01" min="0" id="baseSalary" name="base_salary" value="{{ old('base_salary', '0') }}" placeholder="e.g., 5000.00" required>
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <textarea id="address" name="address" rows="3" placeholder="Enter employee address">{{ old('address') }}</textarea>
>>>>>>> chai-training
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Register Employee</button>
          <a href="{{ url('/admin/employee/list') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Directory</a>
        </div>
      </form>
    </div>

<<<<<<< HEAD
    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
=======
    <footer>&copy; 2025 Web-Based HRMS. All Rights Reserved.</footer>
>>>>>>> chai-training
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
