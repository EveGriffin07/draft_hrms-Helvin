<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
      <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
        <i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name }}
      </a>
    </div>
  </header>

  <div class="container">

    @include('admin.layout.sidebar')

    <main>

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
          <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-plus"></i> Quick Action
          </a>
        </div>
      </div>

      <div class="dashboard-metrics">
        <div class="metric-card">
          <div class="metric-icon metric-icon-blue">
            <i class="fa-solid fa-users"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Total Employees</span>
            <span class="metric-value">{{ $totalEmployees }}</span>
            <span class="metric-trend"><i class="fa-solid fa-arrow-up"></i> +{{ $newEmployeesThisMonth }} this month</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-icon metric-icon-orange">
            <i class="fa-solid fa-briefcase"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Active Job Posts</span>
            <span class="metric-value">{{ $activeJobPosts }}</span>
            <span class="metric-trend"><i class="fa-solid fa-arrow-up"></i> Open roles</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-icon metric-icon-green">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Ongoing Training</span>
            <span class="metric-value">{{ $ongoingTraining }}</span>
            <span class="metric-trend"><i class="fa-solid fa-arrow-right"></i> Active programs</span>
          </div>
        </div>

        <div class="metric-card">
          <div class="metric-icon metric-icon-red">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <div class="metric-content">
            <span class="metric-label">Pending Reviews</span>
            <span class="metric-value">{{ $pendingReviews }}</span>
            <span class="metric-trend"><i class="fa-solid fa-circle-exclamation"></i> Need attention</span>
          </div>
        </div>
      </div>

      <div class="dashboard-main-grid">

        <div class="dashboard-main-left">

          <div class="analytics-grid">

            <div class="panel analytics-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-chart-area"></i> Employee Growth</h3>
              </div>
              <div class="chart-container" style="height: 200px; position: relative;">
                  <canvas id="employeeGrowthChart"></canvas>
              </div>
            </div>

            <div class="panel analytics-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-chart-pie"></i> Department Dist.</h3>
              </div>
              <div class="chart-container" style="height: 200px; position: relative;">
                  <canvas id="deptDistChart"></canvas>
              </div>
            </div>

          </div>

          <div class="module-grid">

            <div class="panel module-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-briefcase"></i> Recruitment</h3>
              </div>
              <ul class="module-list">
                <li><span>Active Job Posts</span><strong>{{ $activeJobPosts }}</strong></li>
                <li><span>New Applicants (Week)</span><strong>{{ $newApplicants }}</strong></li>
                <li><span>Interviews Scheduled</span><strong>{{ $interviewsScheduled }}</strong></li>
              </ul>
              <a href="#" class="module-link">Go to Recruitment</a>
            </div>

            <div class="panel module-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-chart-line"></i> Appraisal</h3>
              </div>
              <ul class="module-list">
                <li><span>Pending Reviews</span><strong>{{ $pendingReviews }}</strong></li>
                <li><span>Completed This Cycle</span><strong>{{ $completedReviews }}</strong></li>
                <li><span>Average KPI Score</span><strong>{{ number_format($avgKpiScore, 1) }}%</strong></li>
              </ul>
              <a href="#" class="module-link">Go to Appraisal</a>
            </div>

            <div class="panel module-card">
              <div class="panel-header">
                <h3><i class="fa-solid fa-graduation-cap"></i> Training</h3>
              </div>
              <ul class="module-list">
                <li><span>Ongoing Trainings</span><strong>{{ $ongoingTraining }}</strong></li>
                <li><span>Completed Trainings</span><strong>{{ $completedTrainings }}</strong></li>
                <li><span>Total Participants</span><strong>{{ $totalParticipants }}</strong></li>
              </ul>
              <a href="#" class="module-link">Go to Training</a>
            </div>

          </div>

        </div>

        <div class="dashboard-main-right">

          <div class="panel announcement-widget">
            <div class="panel-header">
              <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Interviews</h3>
            </div>
            <ul class="announcement-list">
              @forelse($upcomingInterviews as $interview)
              <li>
                <div class="announcement-title">
                    {{ $interview->job->job_title ?? 'Job' }} – {{ $interview->applicant->full_name ?? 'Applicant' }}
                </div>
                <div class="announcement-meta">
                    {{ $interview->updated_at->format('d M Y') }} • Online
                </div>
              </li>
              @empty
              <li style="padding: 10px; text-align: center; color: #999;">No interviews scheduled.</li>
              @endforelse
            </ul>
          </div>

          <div class="panel announcement-widget">
            <div class="panel-header">
              <h3><i class="fa-solid fa-bullhorn"></i> Latest Announcements</h3>
            </div>
            <ul class="announcement-list">
              @forelse($announcements as $announce)
              <li>
                <div class="announcement-title">{{ $announce->title }}</div>
                <div class="announcement-meta">
                    Posted {{ $announce->publish_at ? $announce->publish_at->diffForHumans() : $announce->created_at->diffForHumans() }}
                </div>
              </li>
              @empty
               <li style="padding: 10px; text-align: center; color: #999;">No announcements found.</li>
              @endforelse
            </ul>
            <a href="{{ route('admin.announcements.index') }}" class="module-link">View All Announcements</a>
          </div>

        </div>

      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>

    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Employee Growth Chart
        const ctxGrowth = document.getElementById('employeeGrowthChart');
        if (ctxGrowth) {
            new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: @json($growthLabels), 
                    datasets: [{
                        label: 'New Hires',
                        data: @json($growthData),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { display: false } } }
                }
            });
        }

        // 2. Department Distribution Chart
        const ctxDept = document.getElementById('deptDistChart');
        if (ctxDept) {
            new Chart(ctxDept, {
                type: 'doughnut',
                data: {
                    labels: @json($deptLabels),
                    datasets: [{
                        data: @json($deptData),
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'right', labels: { boxWidth: 10, usePointStyle: true } } 
                    }
                }
            });
        }
    });
  </script>

</body>
</html>