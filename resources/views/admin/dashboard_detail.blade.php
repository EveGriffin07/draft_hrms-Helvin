<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcement Details - HRMS</title>

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

    <main>
      <div class="breadcrumb">Home > Dashboard > Announcement Details</div>

      <h2>Announcement Details</h2>
      <p class="subtitle">View the full content of this announcement.</p>

      <div class="detail-card">

        <h3 class="detail-title">
          <i class="fa-solid fa-bullhorn"></i> System Maintenance Notice
        </h3>

        <div class="detail-meta">
          <span><i class="fa-solid fa-calendar"></i> Date: 2025-11-20</span>
          <span class="badge badge-critical">Critical</span>
          <span><i class="fa-solid fa-users"></i> Audience: All Employees</span>
        </div>

        <div class="detail-body">
          <p>
            Please be informed that the HRMS system will undergo scheduled system maintenance
            on **25 November** from **10:00 PM to 12:00 AM**. During this time, the system will be
            temporarily unavailable.
          </p>

          <p>
            We recommend saving your work and logging out 10 minutes before maintenance begins.
            We apologize for any inconvenience caused.
          </p>
        </div>

        <div class="detail-actions">
          <a href="{{ route('admin.dashboard.announcement.view') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Announcements
          </a>
        </div>

      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

</body>
</html>
