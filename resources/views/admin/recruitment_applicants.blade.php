<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Applicants - HRMS</title>

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
      <div class="breadcrumb">Home > Recruitment > View Applicants</div>
      <h2>Applicant List</h2>
      <p class="subtitle">View all applicants who have applied for job postings and track their application status.</p>

      <div class="open-positions">
        <table>
          <thead>
            <tr>
              <th>Applicant Name</th>
              <th>Position Applied</th>
              <th>Department</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Nur Aisyah</td>
              <td>Software Engineer</td>
              <td>IT Department</td>
              <td>aisyah@example.com</td>
              <td>012-3456789</td>
              <td>Shortlisted</td>
              <td><a href="{{ route('admin.recruitment.applicants.show') }}" class="btn btn-primary">
                  View Details
                </a></td>
            </tr>
            <tr>
              <td>Daniel Lee</td>
              <td>Marketing Assistant</td>
              <td>Marketing</td>
              <td>daniellee@example.com</td>
              <td>019-8765432</td>
              <td>Pending Review</td>
              <td><button class="btn btn-primary">View Details</button></td>
            </tr>
            <tr>
              <td>Ahmad Faiz</td>
              <td>Finance Officer</td>
              <td>Finance</td>
              <td>ahmadfaiz@example.com</td>
              <td>017-4567890</td>
              <td>Interviewed</td>
              <td><button class="btn btn-success">Hired</button></td>
            </tr>
          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

 
</body>
</html>
