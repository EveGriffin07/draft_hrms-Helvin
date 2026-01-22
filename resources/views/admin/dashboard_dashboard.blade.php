<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>
<body>

  <!-- Header -->
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info"><i class="fa-regular fa-bell"></i> &nbsp; HR Admin</div>
  </header>

  <div class="container">

    @include('admin.layout.sidebar')

    <main>

      <!-- Top heading row -->
      <div class="dashboard-top">
        <div>
          <h2>Admin Dashboard</h2>
          <p class="subtitle">Overview of recruitment, appraisal, training, onboarding and announcements.</p>
        </div>
        <div class="dashboard-top-right">
          <div class="dashboard-date">
            <i class="fa-regular fa-calendar"></i>
            <span>{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
          </div>
          <button class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Quick Action
          </button>
        </div>
      </div>

      <!-- Metric cards -->
      <div class="dashboard-metrics">
        <div class="metric-card">
          <div class="metric-icon metric-icon-blue">
            <i class="fa-solid fa-users"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Total Employees</span>
            <span class="metric-value">128</span>
            <span class="metric-trend"><i class="fa-solid fa-arrow-up"></i> +5 this month</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-icon metric-icon-orange">
            <i class="fa-solid fa-briefcase"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Active Job Posts</span>
            <span class="metric-value">6</span>
            <span class="metric-trend"><i class="fa-solid fa-arrow-up"></i> 2 new</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-icon metric-icon-green">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Ongoing Training</span>
            <span class="metric-value">4</span>
            <span class="metric-trend"><i class="fa-solid fa-arrow-right"></i> On track</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-icon metric-icon-red">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Pending Reviews</span>
            <span class="metric-value">9</span>
            <span class="metric-trend"><i class="fa-solid fa-circle-exclamation"></i> Need attention</span>
          </div>
        </div>
      </div>

      <!-- Main grid: analytics + side widgets -->
      <div class="dashboard-main-grid">

        <!-- LEFT SIDE: Analytics & module snapshot -->
        <div class="dashboard-main-left">

          <!-- Analytics charts row -->
          <div class="analytics-grid">

            <!-- Employee growth chart -->
            <div class="panel analytics-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-chart-area"></i> Employee Growth (Last 6 Months)</h3>
              </div>
              <div class="chart-placeholder">
                <div class="chart-line-placeholder">
                  <span>Line chart placeholder</span>
                </div>
              </div>
            </div>

            <!-- Department distribution chart -->
            <div class="panel analytics-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-chart-pie"></i> Department Distribution</h3>
              </div>
              <div class="chart-placeholder chart-pie-placeholder">
                <div class="chart-pie"></div>
                <ul class="chart-legend">
                  <li><span class="legend-dot dot-blue"></span> IT (22)</li>
                  <li><span class="legend-dot dot-green"></span> HR (12)</li>
                  <li><span class="legend-dot dot-orange"></span> Finance (17)</li>
                  <li><span class="legend-dot dot-purple"></span> Sales (35)</li>
                  <li><span class="legend-dot dot-gray"></span> Marketing (18)</li>
                </ul>
              </div>
            </div>

          </div>

          <!-- Module overview cards -->
          <div class="module-grid">

            <div class="panel module-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-briefcase"></i> Recruitment Overview</h3>
              </div>
              <ul class="module-list">
                <li><span>Active Job Posts</span><strong>6</strong></li>
                <li><span>New Applicants (This Week)</span><strong>22</strong></li>
                <li><span>Interviews Scheduled</span><strong>8</strong></li>
              </ul>
              <a href="{{ route('admin.recruitment') }}" class="module-link">Go to Recruitment</a>
            </div>

            <div class="panel module-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-chart-line"></i> Appraisal Overview</h3>
              </div>
              <ul class="module-list">
                <li><span>Pending Reviews</span><strong>9</strong></li>
                <li><span>Completed This Cycle</span><strong>47</strong></li>
                <li><span>Average KPI Score</span><strong>78%</strong></li>
              </ul>
              <a href="{{ route('admin.appraisal') }}" class="module-link">Go to Appraisal</a>
            </div>

            <div class="panel module-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-graduation-cap"></i> Training Overview</h3>
              </div>
              <ul class="module-list">
                <li><span>Ongoing Trainings</span><strong>4</strong></li>
                <li><span>Completed Trainings</span><strong>18</strong></li>
                <li><span>Total Participants</span><strong>54</strong></li>
              </ul>
              <a href="{{ route('admin.training') }}" class="module-link">Go to Training</a>
            </div>

          </div>

        </div>

        <!-- RIGHT SIDE: announcements + activity -->
        <div class="dashboard-main-right">

          <!-- Upcoming Interviews -->
          <div class="panel announcement-widget">
            <div class="panel-header">
              <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Interviews</h3>
            </div>
            <ul class="announcement-list">
              <li>
                <div class="announcement-title">Software Engineer – Ali Bin Abu</div>
                <div class="announcement-meta">12 Dec 2025 • 10:00 AM • Online</div>
              </li>
              <li>
                <div class="announcement-title">HR Assistant – Siti Binti Ahmad</div>
                <div class="announcement-meta">13 Dec 2025 • 2:30 PM • Office</div>
              </li>
            </ul>
          </div>

          <!-- Announcements -->
          <div class="panel announcement-widget">
            <div class="panel-header">
              <h3><i class="fa-solid fa-bullhorn"></i> Latest Announcements</h3>
            </div>
            <ul class="announcement-list">
              <li>
                <div class="announcement-title">System Maintenance – 25 Nov</div>
                <div class="announcement-meta">Posted by HR Admin • 2 days ago</div>
              </li>
              <li>
                <div class="announcement-title">New Overtime Policy</div>
                <div class="announcement-meta">Posted by HR Manager • 1 week ago</div>
              </li>
              <li>
                <div class="announcement-title">Public Holiday Notice – 1 Dec</div>
                <div class="announcement-meta">Posted by HR Admin • 1 week ago</div>
              </li>
            </ul>
            <a href="{{ url('/admin/dashboard/announcement') }}" class="module-link">View All Announcements</a>
          </div>

        </div>

      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>

    </main>
  </div>

</body>
</html>
