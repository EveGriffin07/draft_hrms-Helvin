<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Performance Appraisal - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

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

      {{-- ✅ 1. SUCCESS MESSAGE ALERT --}}
      @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0; display:flex; align-items:center; gap:10px;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.2em;"></i>
            <span>{{ session('success') }}</span>
        </div>
      @endif

      {{-- ✅ 2. DYNAMIC SUMMARY CARDS --}}
      <div class="summary">
        <div class="card">
            <h3>Total Employees</h3>
            <p>{{ $totalEmployees ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>Total KPIs Created</h3>
            <p>{{ $totalKpis ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>In Progress</h3>
            <p>{{ $underReview ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>Completed</h3>
            <p>{{ $completed ?? 0 }}</p>
        </div>
      </div>

      <div class="open-positions">
        <h3>Recent Department Goals</h3>

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
            {{-- ✅ 3. DYNAMIC TABLE LOOP --}}
            @if(isset($deptKpis) && count($deptKpis) > 0)
                @foreach($deptKpis as $kpi)
                <tr>
                  <td>{{ $kpi->department->department_name ?? 'N/A' }}</td>
                  <td>
                      <strong>{{ $kpi->template->kpi_title ?? 'Untitled' }}</strong><br>
                      <span style="font-size:12px; color:#666;">{{ Str::limit($kpi->template->kpi_description, 50) }}</span>
                  </td>
                  <td>{{ $kpi->target }}</td>
                  <td>{{ $kpi->progress }}%</td>
                  <td>
                    <a href="{{ route('admin.appraisal.department-kpi', ['id' => $kpi->dept_kpi_id]) }}" class="btn btn-primary btn-small">
                      <i class="fa-solid fa-clipboard-check"></i> Review
                    </a>
                  </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                        No Department KPIs found. <a href="{{ route('admin.appraisal.add-kpi') }}">Create one now</a>.
                    </td>
                </tr>
            @endif
          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>
</body>
</html>