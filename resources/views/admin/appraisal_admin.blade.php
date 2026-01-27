<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Performance Appraisal - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <!-- Page-only fix: keep Action buttons aligned & consistent -->
  <style>
    .kpi-overview .open-positions th:last-child,
    .kpi-overview .open-positions td:last-child{
      text-align: right;
      width: 160px;
      white-space: nowrap;
    }
    .kpi-overview .open-positions td:last-child .btn{
      justify-content: center;
    }
  </style>
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

    <main class="kpi-overview">
      <div class="breadcrumb">Home > Appraisal > KPI Overview</div>
      <h2>Performance Appraisal</h2>
      <p class="subtitle">Monitor and evaluate employee performance based on department KPIs.</p>

      <div class="summary">
        <div class="card"><h3>Total Employees</h3><p>25</p></div>
        <div class="card"><h3>KPIs Assigned</h3><p>18</p></div>
        <div class="card"><h3>Under Review</h3><p>7</p></div>
        <div class="card"><h3>Completed</h3><p>4</p></div>
      </div>

      <div class="open-positions">
        <h3>Current KPI Goals</h3>

        <table>
          <thead>
            <tr>
              <th>Department</th>
              <th>KPI Description</th>
              <th>Target</th>
              <th>Progress</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>IT</td>
              <td>Develop internal HR portal</td>
              <td>100%</td>
              <td>70%</td>
              <td>
                <a href="{{ route('admin.appraisal.department-kpi') }}"
                   class="btn btn-primary btn-small">
                  <i class="fa-solid fa-clipboard-check"></i>
                  Review
                </a>
              </td>
            </tr>

            <tr>
              <td>Sales</td>
              <td>Achieve monthly revenue of RM 50,000</td>
              <td>100%</td>
              <td>60%</td>
              <td>
                <a href="{{ route('admin.appraisal.department-kpi') }}"
                   class="btn btn-primary btn-small">
                  <i class="fa-solid fa-clipboard-check"></i>
                  Review
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>
</body>
</html>
