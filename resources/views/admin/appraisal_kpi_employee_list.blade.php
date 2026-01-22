<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Employee - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>

<body>

<header>
  <div class="title">Web-Based HRMS</div>
  <div class="user-info"><i class="fa-regular fa-bell"></i> HR Admin</div>
</header>

<div class="container">

@include('partials.sidebar')

<main>

  <div class="breadcrumb">Home > Appraisal > Choose Employee</div>

  <h2>Select Employee</h2>
  <p class="subtitle">Choose an employee to view their KPI records.</p>

  <div class="table-container">

    <!-- (Optional) Search Section -->
    <div class="table-actions">
      <input type="text" placeholder="Search employee..." style="
          padding: 10px;
          width: 250px;
          border-radius: 6px;
          border: 1px solid #ccc;
        ">
    </div>

    <table class="hr-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Department</th>
          <th>Position</th>
          <th>Employee ID</th>
          <th>KPI Count</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>

        <tr>
          <td>John Lim</td>
          <td>IT</td>
          <td>Software Engineer</td>
          <td>EMP0021</td>
          <td>3</td>
          <td>
            <a href="{{ route('admin.appraisal.employee-kpis') }}" class="btn btn-primary btn-small">
              <i class="fa-solid fa-chart-line"></i>
    View KPI
</a>
          </td>
        </tr>

        <tr>
          <td>Nur Aisyah</td>
          <td>Finance</td>
          <td>Accountant</td>
          <td>EMP0134</td>
          <td>4</td>
          <td>
            <a href="/admin/appraisal_employee-kpis?emp=EMP0134" class="btn btn-primary btn-small">
              <i class="fa-solid fa-chart-line"></i> View KPI
            </a>
          </td>
        </tr>

        <tr>
          <td>Zainal Abidin</td>
          <td>Human Resources</td>
          <td>HR Officer</td>
          <td>EMP0099</td>
          <td>5</td>
          <td>
            <a href="/admin/appraisal_employee-kpis?emp=EMP0099" class="btn btn-primary btn-small">
              <i class="fa-solid fa-chart-line"></i> View KPI
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
