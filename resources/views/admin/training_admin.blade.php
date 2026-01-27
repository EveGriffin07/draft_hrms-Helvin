<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training & Learning - HRMS</title>

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
      <div class="breadcrumb">Home > Training > Overview</div>
      <h2>Training & Learning</h2>
      <p class="subtitle">View and manage employee training programs and learning progress.</p>

      <div class="summary">
        <div class="card"><h3>Total Programs</h3><p>10</p></div>
        <div class="card"><h3>Ongoing</h3><p>3</p></div>
        <div class="card"><h3>Completed</h3><p>5</p></div>
        <div class="card"><h3>Upcoming</h3><p>2</p></div>
      </div>

      <div class="calendar-section">
        <h3>Training Schedule Calendar</h3>
        <iframe src="https://calendar.google.com/calendar/embed?src=en.malaysia%23holiday%40group.v.calendar.google.com&ctz=Asia%2FKuala_Lumpur"
          style="border:0" width="100%" height="400" frameborder="0" scrolling="no"></iframe>
      </div>

      <div class="training-list">
  <h3>All Training Programs</h3>
  <table>
    <thead>
      <tr>
        <th>Program Title</th>
        <th>Trainer</th>
        <th>Department</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Leadership Workshop</td>
        <td>John Tan</td>
        <td>HR</td>
        <td>2025-11-01</td>
        <td>2025-11-05</td>
        <td>Completed</td>
        <td>
          <a href="{{ route('admin.training.show') }}" class="btn btn-primary">
            View Details
          </a>
        </td>
      </tr>
      <tr>
        <td>System Security</td>
        <td>Aisha Lim</td>
        <td>IT</td>
        <td>2025-11-10</td>
        <td>2025-11-12</td>
        <td>Ongoing</td>
        <td>
          <a href="{{ route('admin.training.show') }}" class="btn btn-primary">
            View Details
          </a>
        </td>
      </tr>
      <tr>
        <td>Sales Techniques</td>
        <td>Michael Ong</td>
        <td>Sales</td>
        <td>2025-12-01</td>
        <td>2025-12-03</td>
        <td>Upcoming</td>
        <td>
          <a href="{{ route('admin.training.show') }}" class="btn btn-primary">
            View Details
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
