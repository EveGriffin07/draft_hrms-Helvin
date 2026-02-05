<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee KPI Records - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <link rel="stylesheet" href="{{ asset('css/kpi_employee.css') }}">
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
      <div class="breadcrumb">Home > Appraisal > Employee KPI Records</div>

      <h2>Employee KPI Records</h2>
      <p class="subtitle">List of KPI goals assigned to this employee.</p>

      {{-- Success Alert --}}
      @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
      @endif

      {{-- Employee Header Card --}}
      <div class="kpi-employee-header">
        <h3 class="detail-title">
          <i class="fa-solid fa-user"></i> {{ $employee->user->name ?? 'Unknown' }}
        </h3>
        <div class="meta-row">
          <span><i class="fa-solid fa-id-card"></i> ID: {{ $employee->employee_id }}</span>
          
          {{-- ✅ FIXED: Added ->position_name to show text instead of code --}}
          <span>
            <i class="fa-solid fa-briefcase"></i> 
            Position: {{ optional($employee->position)->position_name ?? 'N/A' }}
          </span>

          <span><i class="fa-solid fa-building"></i> Dept: {{ $employee->department->department_name ?? 'N/A' }}</span>
        </div>
      </div>

      <div class="kpi-table-container">
        <a href="{{ route('admin.appraisal.add-kpi') }}" class="kpi-add-btn">
          <i class="fa-solid fa-plus"></i> Assign New KPI
        </a>

        <table class="kpi-table">
          <thead>
            <tr>
              <th>KPI Title</th>
              <th>Target Value</th>
              <th>Type</th>
              <th>Status</th>
              <th>Score</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            @forelse($kpis as $kpi)
            <tr>
              <td>
                <a href="#" class="kpi-link">{{ $kpi->template->kpi_title }}</a>
                <div style="font-size: 12px; color: #666;">{{ Str::limit($kpi->template->kpi_description, 60) }}</div>
              </td>
              <td>{{ $kpi->template->default_target }}</td>
              <td>{{ $kpi->template->kpi_type }}</td>
              
              {{-- Status Badge Logic --}}
              <td>
                @if($kpi->kpi_status == 'completed')
                    <span class="kpi-badge kpi-completed">Completed</span>
                @elseif($kpi->kpi_status == 'in_progress')
                    <span class="kpi-badge kpi-in-progress">In Progress</span>
                @else
                    <span class="kpi-badge kpi-pending">Pending</span>
                @endif
              </td>

              <td>{{ $kpi->actual_score ?? '–' }}%</td>
              
              <td>
                <div class="kpi-actions">
                  <button class="kpi-btn-small kpi-btn-review" type="button"
                    onclick="openModal(
                        '{{ $kpi->emp_kpi_id }}', 
                        '{{ addslashes($kpi->template->kpi_title) }}', 
                        '{{ $kpi->actual_score }}', 
                        '{{ $kpi->kpi_status }}',
                        '{{ addslashes($kpi->comments) }}'
                    )">
                    <i class="fa-solid fa-edit"></i> Review
                  </button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">No KPIs assigned yet.</td>
            </tr>
            @endforelse

          </tbody>
        </table>
      </div>

      {{-- Review Modal --}}
      <div class="kpi-modal-overlay" id="kpiReviewOverlay" aria-hidden="true" style="display: none;">
        <div class="kpi-modal" role="dialog">
          <div class="kpi-modal-header">
            <div>
              <h3 id="kpiReviewTitle">Review KPI</h3>
              <p>Update score, status and reviewer notes.</p>
            </div>
            <button class="kpi-modal-close" type="button" onclick="closeModal()">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>

          <form id="reviewForm" method="POST" action="">
            @csrf 
            <div class="kpi-modal-body">
              <div class="form-group">
                <label>KPI Title</label>
                <input type="text" id="modalKpiTitle" disabled style="background:#f3f4f6;">
              </div>

              <div class="form-group">
                <label>Performance Score (%)</label>
                <input name="score" id="modalScore" type="number" min="0" max="100" placeholder="0 - 100" required>
              </div>

              <div class="form-group">
                <label>Status</label>
                <select name="status" id="modalStatus">
                  <option value="pending">Pending</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                </select>
              </div>

              <div class="form-group">
                <label>Reviewer Comment</label>
                <textarea name="comment" id="modalComment" rows="3" placeholder="Notes..."></textarea>
              </div>
            </div>

            <div class="kpi-modal-footer">
              <button class="btn btn-secondary btn-small" type="button" onclick="closeModal()">Cancel</button>
              <button class="btn btn-primary btn-small" type="submit">
                <i class="fa-solid fa-paper-plane"></i> Submit Review
              </button>
            </div>
          </form>

        </div>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <script>
    const overlay = document.getElementById('kpiReviewOverlay');
    const form = document.getElementById('reviewForm');

    function openModal(id, title, score, status, comment) {
        form.action = "/admin/appraisal/update-score/" + id;
        document.getElementById('modalKpiTitle').value = title;
        document.getElementById('modalScore').value = score || '';
        document.getElementById('modalStatus').value = status || 'pending';
        document.getElementById('modalComment').value = comment || '';
        overlay.style.display = 'flex';
        overlay.classList.add('show');
    }

    function closeModal() {
        overlay.style.display = 'none';
        overlay.classList.remove('show');
    }

    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeModal();
    });
  </script>

</body>
</html>