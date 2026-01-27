<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Announcements - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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

    <main>
      <div class="breadcrumb">Home > Dashboard > View Announcements</div>
      <h2>Announcements</h2>
      <p class="subtitle">List of announcements currently used in the dashboard.</p>

      <div class="table-container">

        <div class="table-actions">
          <a href="{{ route('admin.dashboard.announcement.add') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Announcement
          </a>
        </div>

        <table class="hr-table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Date</th>
              <th>Priority</th>
              <th>Audience</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dummy data row 1 -->
            <tr>
              <td>
                <a href="{{ route('admin.dashboard.announcement.detail') }}" class="link-title"
                   class="link-title">
                  System Maintenance Notice
                </a>
              </td>
              <td>2025-11-20</td>
              <td><span class="badge badge-critical">Critical</span></td>
              <td>All Employees</td>
            </tr>

            <!-- Dummy data row 2 -->
            <tr>
              <td>
                <a href="#"
                   class="link-title">
                  New Training: Advanced Excel
                </a>
              </td>
              <td>2025-11-15</td>
              <td><span class="badge badge-important">Important</span></td>
              <td>Finance Department</td>
            </tr>

            <!-- Dummy data row 3 -->
            <tr>
              <td>
                <a href="#"
                   class="link-title">
                  HR Policy Update – WFH Guidelines
                </a>
              </td>
              <td>2025-11-10</td>
              <td><span class="badge badge-normal">Normal</span></td>
              <td>All Employees</td>
            </tr>

          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  
</body>
</html>
