<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Self Evaluation - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <link rel="stylesheet" href="{{ asset('css/kpi_employee.css') }}">
</head>
<body>

  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
        <a href="#" style="text-decoration: none; color: inherit;">
            <i class="fa-regular fa-user"></i> &nbsp; {{ Auth::user()->name }}
        </a>
    </div>
  </header>

  <div class="container">
    {{-- Ensure this points to your EMPLOYEE sidebar --}}
    @include('employee.layout.sidebar') 

    <main>
      <div class="breadcrumb">Home > Performance > Self Evaluation</div>
      <h2>Self Evaluation</h2>
      <p class="subtitle">Rate your own performance and provide evidence of your achievements.</p>

      @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
      @endif

      <div class="kpi-table-container">
        <table class="kpi-table">
          <thead>
            <tr>
              <th style="width: 25%;">KPI Goal</th>
              <th style="width: 10%;">Target</th>
              <th style="width: 45%;">My Comments & Evidence</th>
              <th style="width: 10%;">My Score</th>
              <th style="width: 10%;">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kpis as $kpi)
            <tr>
              {{-- FORM STARTS HERE --}}
              <form action="{{ route('employee.kpis.store-eval', $kpi->emp_kpi_id) }}" method="POST">
                @csrf
                
                {{-- 1. KPI Info --}}
                <td style="vertical-align: top; padding-top: 15px;">
                    <strong>{{ $kpi->template->kpi_title }}</strong>
                    <p style="font-size:12px; color:#666; margin-top:5px;">{{ $kpi->template->kpi_description }}</p>
                    
                    @if($kpi->kpi_status == 'completed')
                        <span class="kpi-badge kpi-completed" style="margin-top:5px;">Manager Reviewed</span>
                    @else
                        <span class="kpi-badge kpi-pending" style="margin-top:5px;">Pending Review</span>
                    @endif
                </td>

                {{-- 2. Target --}}
                <td style="vertical-align: top; padding-top: 15px;">
                    {{ $kpi->template->default_target }}
                </td>

                {{-- 3. Input: Comments --}}
                <td>
                    <textarea name="employee_comments" rows="3" 
                        style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit;"
                        placeholder="Describe your achievements...">{{ $kpi->employee_comments }}</textarea>
                </td>

                {{-- 4. Input: Rating --}}
                <td>
                    <input type="number" name="self_rating" value="{{ $kpi->self_rating }}" min="0" max="100" 
                        style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;"
                        placeholder="0-100">
                </td>

                {{-- 5. Submit Button --}}
                <td>
                    <button type="submit" class="btn btn-primary btn-small" style="white-space: nowrap;">
                        <i class="fa-solid fa-floppy-disk"></i> Save
                    </button>
                </td>

              </form>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; padding:20px;">No KPIs to evaluate.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>