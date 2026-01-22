<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports Dashboard - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    .report-tabs{
      display:flex;
      gap:15px;
      margin-bottom:25px;
      border-bottom:2px solid #e5e7eb;
      padding-bottom:10px;
      flex-wrap: wrap;
    }
    .tab-btn{
      padding:10px 18px;
      background:#f3f4f6;
      border-radius:6px;
      cursor:pointer;
      font-weight:600;
      color:#374151;
      transition:.2s;
      user-select:none;
    }
    .tab-btn.active{ background:#2563eb; color:#fff; }
    .tab-btn:hover{ background:#e5e7eb; }

    .tab-content{ display:none; }
    .tab-content.active{ display:block; }

    .report-card{
      background:#fff;
      padding:20px;
      border-radius:12px;
      margin-bottom:25px;
      border:1px solid #e5e7eb;
      box-shadow:0 4px 10px rgba(0,0,0,.05);
    }
    .report-card h3{
      margin: 6px 0 10px;
      font-size:18px;
      font-weight:600;
      color:#1e3a8a;
    }
    .section-desc{
      font-size:13px;
      color:#6b7280;
      margin-bottom:10px;
    }

    .summary-grid{
      display:grid;
      grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
      gap:14px;
      margin-bottom:18px;
    }
    .summary-item{
      background:#f9fafb;
      border-radius:10px;
      padding:12px 14px;
      border:1px solid #e5e7eb;
    }
    .summary-label{ font-size:12px; color:#6b7280; }
    .summary-value{ font-size:18px; font-weight:600; color:#111827; }

    .filter-bar{
      display:grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap:12px;
      margin-bottom:15px;
    }
    .filter-group label{
      display:block;
      font-size:12px;
      margin-bottom:3px;
      color:#4b5563;
      font-weight: 500;
    }
    .filter-group input, .filter-group select{
      width:100%;
      border-radius:8px;
      border:1px solid #d1d5db;
      padding:8px 10px;
      font-size:13px;
      background:#fff;
    }
    .filter-group-button{ display:flex; align-items:flex-end; }
    .filter-apply-btn{
      background:#2563eb;
      color:#fff;
      padding:9px 14px;
      border-radius:8px;
      border:none;
      cursor:pointer;
      font-size:13px;
      width: 100%;
    }

    .report-table{
      width:100%;
      border-collapse:collapse;
      font-size:13px;
    }
    .report-table th, .report-table td{
      border:1px solid #e5e7eb;
      padding:8px 10px;
      text-align:left;
      vertical-align: top;
    }
    .report-table th{
      background:#f1f5f9;
      font-weight:600;
    }
    .table-title{
      margin:18px 0 8px;
      font-size:15px;
      font-weight:600;
      color:#111827;
    }

    .two-col-layout{
      display:grid;
      grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
      gap:20px;
      margin-top:10px;
      margin-bottom:18px;
    }
    @media (max-width: 900px){
      .two-col-layout{ grid-template-columns: 1fr; }
    }

    .chart-container{ width:100%; padding:10px 0; }
    canvas{ width:100%; height:300px; }

    .export-area{
      margin-bottom: 10px;
      display:flex;
      justify-content:flex-end;
    }
    .btn-export{
      background:#2563eb;
      color:white;
      padding:8px 14px;
      border-radius:6px;
      cursor:pointer;
      border:none;
      font-size:14px;
      display:inline-flex;
      align-items:center;
      gap:6px;
    }

    .btn-view{
      background:#334155;
      color:#fff;
      padding:6px 10px;
      border-radius:6px;
      cursor:pointer;
      border:none;
      font-size:13px;
      display:inline-flex;
      align-items:center;
      gap:6px;
      text-decoration:none;
      white-space: nowrap;
    }
    .btn-view:hover{ opacity:.92; }

    .badge{
      display:inline-block;
      padding:3px 8px;
      border-radius:999px;
      font-size:11px;
      font-weight:600;
    }
    .badge-ok{ background:#dcfce7; color:#166534; }
    .badge-warn{ background:#fef9c3; color:#92400e; }
    .badge-bad{ background:#fee2e2; color:#b91c1c; }
  </style>
</head>

<body>
<header>
  <div class="title">Web-Based HRMS</div>
  <div class="user-info"><i class="fa-regular fa-bell"></i> &nbsp; HR Admin</div>
</header>

<div class="container">
  @include('partials.sidebar')

  <main>
    <div class="breadcrumb">Home > Reports &amp; Analytics</div>
    <h2>Central Reports &amp; Analytics</h2>
    <p class="subtitle">
      View consolidated reports and analytics across recruitment, training, appraisal and onboarding modules.
    </p>

    <div class="report-tabs">
      <div class="tab-btn active" data-tab="recruitment">Recruitment</div>
      <div class="tab-btn" data-tab="training">Training</div>
      <div class="tab-btn" data-tab="appraisal">Appraisal / KPI</div>
      <div class="tab-btn" data-tab="onboarding">Onboarding</div>
    </div>

    <div id="tab-recruitment" class="tab-content active">
      <div class="report-card">
        <div class="export-area">
          <button class="btn-export" type="button">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
          </button>
        </div>

        <form class="filter-bar" onsubmit="event.preventDefault();">
          <div class="filter-group">
            <label>Job Position</label>
            <select>
              <option value="">All Positions</option>
              <option>Software Engineer</option>
              <option>HR Executive</option>
              <option>Marketing Assistant</option>
            </select>
          </div>

          <div class="filter-group">
            <label>Stage</label>
            <select>
              <option value="">All Stages</option>
              <option>Applied</option>
              <option>Pending Review</option>
              <option>Shortlisted</option>
              <option>Interview Scheduled</option>
              <option>Interviewed</option>
              <option>Hired</option>
              <option>Rejected</option>
            </select>
          </div>

          <div class="filter-group">
            <label>Date From</label>
            <input type="date">
          </div>

          <div class="filter-group">
            <label>Date To</label>
            <input type="date">
          </div>

          <div class="filter-group filter-group-button">
            <button class="filter-apply-btn" type="submit">Apply Filters</button>
          </div>
        </form>

        <h3>Recruitment Summary</h3>
        <p class="section-desc">Key recruitment metrics for the selected period.</p>

        <div class="summary-grid">
          <div class="summary-item">
            <div class="summary-label">Open Positions</div>
            <div class="summary-value">6</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Applications Received</div>
            <div class="summary-value">120</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Interviews Scheduled</div>
            <div class="summary-value">12</div>
          </div>
          <div class="summary-item">
            <div class="summary-label">Hires Completed</div>
            <div class="summary-value">5</div>
          </div>
        </div>

        <div class="table-title">Applicants</div>
        <table class="report-table">
          <thead>
            <tr>
              <th>Candidate</th>
              <th>Job Title</th>
              <th>Department</th>
              <th>Application Date</th>
              <th>Stage</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Ali Bin Abu</td>
              <td>Software Engineer</td>
              <td>IT</td>
              <td>2025-12-01</td>
              <td><span class="badge badge-warn">Shortlisted</span></td>
              <td>
                <a href="#" class="btn-view">
                  <i class="fa-solid fa-eye"></i> View
                </a>
              </td>
            </tr>
            <tr>
              <td>Siti Binti Ahmad</td>
              <td>HR Executive</td>
              <td>Human Resources</td>
              <td>2025-12-03</td>
              <td><span class="badge badge-ok">Interview Scheduled</span></td>
              <td>
                <a href="#" class="btn-view">
                  <i class="fa-solid fa-eye"></i> View
                </a>
              </td>
            </tr>
          </tbody>
        </table>

        <h3 style="margin-top:20px;">Recruitment Analytics</h3>
        <p class="section-desc">Pipeline distribution and candidate performance insights.</p>

        <div class="two-col-layout">
          <div>
            <div class="table-title">Pipeline Stage Counts</div>
            <table class="report-table">
              <thead>
                <tr>
                  <th>Stage</th>
                  <th>Count</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>Applied</td><td>120</td></tr>
                <tr><td>Shortlisted</td><td>50</td></tr>
                <tr><td>Interviewed</td><td>20</td></tr>
                <tr><td>Hired</td><td>5</td></tr>
              </tbody>
            </table>
          </div>

          <div class="chart-container">
            <canvas id="recruitmentStageChart"></canvas>
          </div>
        </div>

        <div class="two-col-layout">
          <div>
            <div class="table-title">Candidate Scores</div>
            <table class="report-table">
              <thead>
                <tr>
                  <th>Candidate</th>
                  <th>Job Title</th>
                  <th>Test Score</th>
                  <th>Interview Score</th>
                  <th>Overall</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>Ali Bin Abu</td><td>Software Engineer</td><td>85</td><td>90</td><td>88</td></tr>
                <tr><td>Siti Binti Ahmad</td><td>HR Executive</td><td>78</td><td>80</td><td>79</td></tr>
              </tbody>
            </table>
          </div>

          <div class="chart-container">
            <canvas id="recruitmentScoreChart"></canvas>
          </div>
        </div>

      </div>
    </div>

    <div id="tab-training" class="tab-content">
  <div class="report-card">
    <div class="export-area">
      <button class="btn-export" type="button">
        <i class="fa-solid fa-file-pdf"></i> Export PDF
      </button>
    </div>

    <form class="filter-bar" onsubmit="event.preventDefault();">
      <div class="filter-group">
        <label>Date From</label>
        <input type="date">
      </div>
      <div class="filter-group">
        <label>Date To</label>
        <input type="date">
      </div>
      <div class="filter-group">
        <label>Program Status</label>
        <select>
          <option value="">All</option>
          <option>Upcoming</option>
          <option>Ongoing</option>
          <option>Completed</option>
        </select>
      </div>
      <div class="filter-group filter-group-button">
        <button class="filter-apply-btn" type="submit">Apply Filters</button>
      </div>
    </form>

    <h3>Training Summary</h3>
    <p class="section-desc">Training programs summary for the selected period.</p>

    <div class="summary-grid">
      <div class="summary-item">
        <div class="summary-label">Total Programs</div>
        <div class="summary-value">10</div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Ongoing</div>
        <div class="summary-value">3</div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Completed</div>
        <div class="summary-value">5</div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Upcoming</div>
        <div class="summary-value">2</div>
      </div>
    </div>

    <div class="two-col-layout">
      <div>
        <div class="table-title">Training Programs</div>
        <table class="report-table">
          <thead>
            <tr>
              <th>Program Title</th>
              <th>Trainer</th>
              <th>Department</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Leadership Workshop</td>
              <td>John Tan</td>
              <td>Human Resources</td>
              <td>2025-11-01</td>
              <td>2025-11-05</td>
              <td>Completed</td>
            </tr>
            <tr>
              <td>System Security</td>
              <td>Aisha Lim</td>
              <td>IT</td>
              <td>2025-11-10</td>
              <td>2025-11-12</td>
              <td>Ongoing</td>
            </tr>
            <tr>
              <td>Sales Techniques</td>
              <td>Michael Ong</td>
              <td>Sales</td>
              <td>2025-12-01</td>
              <td>2025-12-03</td>
              <td>Upcoming</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="chart-container">
        <canvas id="trainingParticipantsChart"></canvas>
      </div>
    </div>

  </div>
</div>


    <div id="tab-appraisal" class="tab-content">
      <div class="report-card">
        <div class="export-area">
          <button class="btn-export" type="button">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
          </button>
        </div>

        <form class="filter-bar" onsubmit="event.preventDefault();">
          <div class="filter-group">
            <label>Department</label>
            <select>
              <option value="">All</option>
              <option>IT</option>
              <option>Human Resources</option>
              <option>Finance</option>
              <option>Sales</option>
            </select>
          </div>
          <div class="filter-group">
            <label>KPI Status</label>
            <select>
              <option value="">All</option>
              <option>Pending</option>
              <option>In Progress</option>
              <option>Under Review</option>
              <option>Completed</option>
              <option>Overdue</option>
            </select>
          </div>
          <div class="filter-group filter-group-button">
            <button class="filter-apply-btn" type="submit">Apply Filters</button>
          </div>
        </form>

        <h3>Appraisal Summary</h3>
        <p class="section-desc">KPI monitoring and review progress for the selected department.</p>

        <div class="summary-grid">
          <div class="summary-item"><div class="summary-label">Total Employees</div><div class="summary-value">25</div></div>
          <div class="summary-item"><div class="summary-label">KPIs Assigned</div><div class="summary-value">18</div></div>
          <div class="summary-item"><div class="summary-label">Under Review</div><div class="summary-value">7</div></div>
          <div class="summary-item"><div class="summary-label">Completed</div><div class="summary-value">4</div></div>
        </div>

        <div class="table-title">Department KPI Goals</div>
        <table class="report-table">
          <thead>
            <tr>
              <th>Department</th>
              <th>KPI Description</th>
              <th>Target</th>
              <th>Progress</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>IT</td><td>Develop internal HR portal</td><td>100%</td><td>70%</td><td><span class="badge badge-warn">In Progress</span></td></tr>
            <tr><td>Sales</td><td>Achieve monthly revenue RM 50,000</td><td>100%</td><td>60%</td><td><span class="badge badge-warn">Under Review</span></td></tr>
          </tbody>
        </table>

        <div class="two-col-layout">
          <div>
            <div class="table-title">Employee KPI Records</div>
            <table class="report-table">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Department</th>
                  <th>KPI Goal</th>
                  <th>Score</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>Nur Aisyah</td><td>IT</td><td>System Uptime &amp; Support</td><td>78%</td><td><span class="badge badge-warn">Under Review</span></td></tr>
                <tr><td>Ravi Kumar</td><td>IT</td><td>Complete Module Integration</td><td>72%</td><td><span class="badge badge-bad">Overdue</span></td></tr>
              </tbody>
            </table>
          </div>

          <div class="chart-container">
            <canvas id="appraisalDistributionChart"></canvas>
          </div>
        </div>

      </div>
    </div>

    <div id="tab-onboarding" class="tab-content">
      <div class="report-card">
        <div class="export-area">
          <button class="btn-export" type="button">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
          </button>
        </div>

        <form class="filter-bar" onsubmit="event.preventDefault();">
          <div class="filter-group">
            <label>Status</label>
            <select>
              <option value="">All</option>
              <option>Pending</option>
              <option>In Progress</option>
              <option>Completed</option>
              <option>Overdue</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Start Date From</label>
            <input type="date">
          </div>
          <div class="filter-group">
            <label>Start Date To</label>
            <input type="date">
          </div>
          <div class="filter-group filter-group-button">
            <button class="filter-apply-btn" type="submit">Apply Filters</button>
          </div>
        </form>

        <h3>Onboarding Summary</h3>
        <p class="section-desc">Onboarding progress overview for the selected period.</p>

        <div class="summary-grid">
          <div class="summary-item"><div class="summary-label">New Onboarding Records</div><div class="summary-value">6</div></div>
          <div class="summary-item"><div class="summary-label">In Progress</div><div class="summary-value">2</div></div>
          <div class="summary-item"><div class="summary-label">Completed</div><div class="summary-value">4</div></div>
          <div class="summary-item"><div class="summary-label">Overdue</div><div class="summary-value">1</div></div>
        </div>

        <div class="two-col-layout">
          <div>
            <div class="table-title">Onboarding List</div>
            <table class="report-table">
              <thead>
                <tr>
                  <th>Employee</th>
                  <th>Department</th>
                  <th>Start Date</th>
                  <th>Deadline</th>
                  <th>Progress</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>Adam Lee</td><td>Marketing</td><td>2025-02-01</td><td>2025-02-15</td><td>60%</td><td><span class="badge badge-warn">In Progress</span></td></tr>
                <tr><td>John Lim</td><td>IT</td><td>2025-01-10</td><td>2025-01-24</td><td>100%</td><td><span class="badge badge-ok">Completed</span></td></tr>
              </tbody>
            </table>
          </div>

          <div class="chart-container">
            <canvas id="onboardingStatusChart"></canvas>
          </div>
        </div>

        <h3 style="margin-top:18px;">Overdue Tasks</h3>
        <p class="section-desc">Tasks that exceeded the due date and require follow-up.</p>

        <div class="table-title">Overdue Task Records</div>
        <table class="report-table">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Task</th>
              <th>Owner</th>
              <th>Due Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Adam Lee</td><td>Submit employee documents</td><td>Employee</td><td>2025-02-05</td><td><span class="badge badge-bad">Overdue</span></td></tr>
          </tbody>
        </table>

      </div>
    </div>

    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
  </main>
</div>

<script>
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

      btn.classList.add('active');
      document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
  });

  const stageCtx = document.getElementById('recruitmentStageChart');
  if(stageCtx){
    new Chart(stageCtx, {
      type: 'bar',
      data: {
        labels: ['Applied', 'Shortlisted', 'Interviewed', 'Hired'],
        datasets: [{ label: 'Candidates', data: [120, 50, 20, 5] }]
      }
    });
  }

  const scoreCtx = document.getElementById('recruitmentScoreChart');
  if(scoreCtx){
    new Chart(scoreCtx, {
      type: 'bar',
      data: {
        labels: ['Software Engineer', 'HR Executive'],
        datasets: [{ label: 'Avg Overall Score', data: [88, 79] }]
      }
    });
  }

  const trainingCtx = document.getElementById('trainingParticipantsChart');
  if(trainingCtx){
    new Chart(trainingCtx, {
      type: 'bar',
      data: {
        labels: ['Leadership Workshop', 'System Security', 'Sales Techniques'],
        datasets: [{ label: 'Participants', data: [18, 22, 15] }]
      }
    });
  }

  const appraisalCtx = document.getElementById('appraisalDistributionChart');
  if(appraisalCtx){
    new Chart(appraisalCtx, {
      type: 'bar',
      data: {
        labels: ['<60%', '60–79%', '80–89%', '90–100%'],
        datasets: [{ label: 'Employees', data: [2, 10, 8, 5] }]
      }
    });
  }

  const onboardCtx = document.getElementById('onboardingStatusChart');
  if(onboardCtx){
    new Chart(onboardCtx, {
      type: 'pie',
      data: {
        labels: ['Completed', 'In Progress', 'Overdue'],
        datasets: [{ label: 'Onboarding', data: [4, 2, 1] }]
      }
    });
  }
</script>
</body>
</html>
