<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Announcement - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

</head>
<body>

  <!-- Header -->
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

      <div class="breadcrumb">Home > Dashboard > Add Announcement</div>
      <h2>Add Announcement</h2>
      <p class="subtitle">Create an announcement to display on the dashboard for all employees or admins.</p>

      <div class="form-container">

        <form action="#" method="POST" class="form-card">

          <h3><i class="fa-solid fa-bullhorn"></i> Announcement Details</h3>

          <!-- Title -->
          <div class="form-group">
            <label for="title">Announcement Title <span>*</span></label>
            <input type="text" id="title" name="title" placeholder="E.g., System Maintenance Notice" required>
          </div>

          <!-- Message -->
          <div class="form-group full-width">
            <label for="message">Message <span>*</span></label>
            <textarea id="message" name="message" rows="4" placeholder="Write the announcement message..." required></textarea>
          </div>

          <!-- Priority -->
          <div class="form-group">
            <label for="priority">Priority <span>*</span></label>
            <select id="priority" name="priority" required>
              <option value="" disabled selected>Select Priority</option>
              <option value="Normal">Normal</option>
              <option value="Important">Important</option>
              <option value="Critical">Critical</option>
            </select>
          </div>

          <!-- Target Audience -->
          <div class="form-group">
            <label for="audience">Target Audience <span>*</span></label>
            <select id="audience" name="audience" required>
              <option value="" disabled selected>Select Audience</option>
              <option value="All Employees">All Employees</option>
              <option value="Admins Only">Admins Only</option>
              <option value="Specific Department">Specific Department</option>
            </select>
          </div>

          <!-- Optional: Department -->
          <div class="form-group">
            <label for="department">Department (Optional)</label>
            <select id="department" name="department">
              <option value="" selected>All Departments</option>
              <option value="Human Resources">Human Resources</option>
              <option value="Finance">Finance</option>
              <option value="IT">Information Technology</option>
              <option value="Sales">Sales</option>
              <option value="Marketing">Marketing</option>
            </select>
          </div>

          <!-- Expiry -->
          <div class="form-group">
            <label for="expires">Expiry Date (Optional)</label>
            <input type="date" id="expires" name="expires">
          </div>

          <!-- Remarks -->
          <div class="form-group full-width">
            <label for="remarks">Additional Notes</label>
            <textarea id="remarks" name="remarks" rows="3" placeholder="Optional notes or references..."></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-floppy-disk"></i> Save Announcement
            </button>

            <a href="{{ url('/admin/dashboard/announcement') }}" class="btn btn-secondary">
              <i class="fa-solid fa-arrow-left"></i> Back to Announcements
            </a>
          </div>

        </form>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const groups = document.querySelectorAll('.sidebar-group');

      groups.forEach(group => {
        const toggle = group.querySelector('.sidebar-toggle');
        if (!toggle) return;

        toggle.addEventListener('click', function (e) {
          e.preventDefault();
          groups.forEach(g => { if (g !== group) g.classList.remove('open'); });
          group.classList.toggle('open');
        });
      });
    });
  </script>

</body>
</html>
