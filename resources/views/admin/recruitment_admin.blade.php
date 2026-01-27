<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recruitment Management - HRMS</title>
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

    <main>
      <div class="breadcrumb">Home > Recruitment > Overview</div>
      <h2>Recruitment Management</h2>
      <p class="subtitle">Manage open job postings, applicant tracking, and hiring progress.</p>

      <div class="summary">
        <div class="card"><h3>Open Positions</h3><p>4</p></div>
        <div class="card"><h3>Applications Received</h3><p>32</p></div>
        <div class="card"><h3>Interviews Scheduled</h3><p>5</p></div>
        <div class="card"><h3>Hires Completed</h3><p>2</p></div>
      </div>

      <div class="open-positions">
        <h3>Open Positions & Departments</h3>
        <table>
          <thead>
            <tr>
              <th>Job Title</th>
              <th>Department</th>
              <th>Applicants</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Software Engineer</td>
              <td>IT Department</td>
              <td>12</td>
              <td>Open</td>
              <td><button class="btn btn-primary">View Applicants</button></td>
            </tr>
            <tr>
              <td>HR Executive</td>
              <td>Human Resources</td>
              <td>8</td>
              <td>Screening</td>
              <td><button class="btn btn-primary">View Applicants</button></td>
            </tr>
            <tr>
              <td>Marketing Assistant</td>
              <td>Marketing</td>
              <td>6</td>
              <td>Open</td>
              <td><button class="btn btn-primary">View Applicants</button></td>
            </tr>
            <tr>
              <td>Finance Officer</td>
              <td>Finance</td>
              <td>4</td>
              <td>Interviewing</td>
              <td><button class="btn btn-success">Hire</button></td>
            </tr>
          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>



</body>
</html>

