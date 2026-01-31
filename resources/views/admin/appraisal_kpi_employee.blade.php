<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee KPI Records - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Global HRMS CSS -->
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <!-- Page-specific CSS -->
  <link rel="stylesheet" href="{{ asset('css/kpi_employee.css') }}">
</head>

<body>

  <!-- Header -->
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

      <!-- Breadcrumb -->
      <div class="breadcrumb">Home > Appraisal > Employee KPI Records</div>

      <!-- Page Title -->
      <h2>Employee KPI Records</h2>
      <p class="subtitle">List of KPI goals assigned to this employee.</p>

      <!-- Employee Header Card -->
      <div class="kpi-employee-header">
        <h3 class="detail-title">
          <i class="fa-solid fa-user"></i> John Lim (IT Department)
        </h3>

        <div class="meta-row">
          <span><i class="fa-solid fa-id-card"></i> Employee ID: EMP-0021</span>
          <span><i class="fa-solid fa-briefcase"></i> Position: Software Engineer</span>
          <span><i class="fa-solid fa-building"></i> Department: IT</span>
        </div>
      </div>

      <!-- KPI Table -->
      <div class="kpi-table-container">

        <a href="#" class="kpi-add-btn">
          <i class="fa-solid fa-plus"></i> Assign New KPI
        </a>

        <table class="kpi-table">
          <thead>
            <tr>
              <th>KPI Title</th>
              <th>Target Value</th>
              <th>Type</th>
              <th>Status</th>
              <th>Performance Score</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>

            <!-- EXAMPLE ROW 1 -->
            <tr>
              <td>
                <a href="#" class="kpi-link">Improve System Efficiency</a>
              </td>
              <td>90% Uptime</td>
              <td>Quantitative</td>
              <td><span class="kpi-badge kpi-in-progress">In Progress</span></td>
              <td>–</td>
              <td>
                <div class="kpi-actions">
                  <button class="kpi-btn-small kpi-btn-review" type="button">
                    <i class="fa-solid fa-edit"></i> Review
                  </button>
                </div>
              </td>
            </tr>

            <!-- EXAMPLE ROW 2 -->
            <tr>
              <td>
                <a href="#" class="kpi-link">Enhance System Security</a>
              </td>
              <td>Reduce vulnerabilities 40%</td>
              <td>Qualitative</td>
              <td><span class="kpi-badge kpi-pending">Pending</span></td>
              <td>–</td>
              <td>
                <div class="kpi-actions">
                  <button class="kpi-btn-small kpi-btn-review" type="button">
                    <i class="fa-solid fa-edit"></i> Review
                  </button>
                </div>
              </td>
            </tr>

            <!-- EXAMPLE ROW 3 -->
            <tr>
              <td>
                <a href="#" class="kpi-link">Complete Module Integration</a>
              </td>
              <td>100% by Q4</td>
              <td>Quantitative</td>
              <td><span class="kpi-badge kpi-overdue">Overdue</span></td>
              <td>72%</td>
              <td>
                <div class="kpi-actions">
                  <button class="kpi-btn-small kpi-btn-review" type="button">
                    <i class="fa-solid fa-edit"></i> Review
                  </button>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <!-- ✅ KPI Review Modal (Same Page) -->
      <div class="kpi-modal-overlay" id="kpiReviewOverlay" aria-hidden="true">
        <div class="kpi-modal" role="dialog" aria-modal="true" aria-labelledby="kpiReviewTitle">
          <div class="kpi-modal-header">
            <div>
              <h3 id="kpiReviewTitle">Review KPI</h3>
              <p>Update score, status and reviewer notes for this KPI.</p>
            </div>
            <button class="kpi-modal-close" type="button" id="kpiReviewCloseBtn">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>

          <div class="kpi-modal-body">
            <div class="kpi-meta-grid">
              <div class="kpi-meta-item">
                <span class="label">KPI Title</span>
                <span class="value" id="metaTitle">—</span>
              </div>
              <div class="kpi-meta-item">
                <span class="label">Target</span>
                <span class="value" id="metaTarget">—</span>
              </div>
              <div class="kpi-meta-item">
                <span class="label">Type</span>
                <span class="value" id="metaType">—</span>
              </div>
            </div>

            <div class="form-group">
              <label>Actual Result / Achievement</label>
              <textarea id="reviewActual" rows="3" placeholder="Example: Uptime achieved 93% (Jan–Mar)"></textarea>
            </div>

            <div class="form-group">
              <label>Performance Score (%)</label>
              <input id="reviewScore" type="number" min="0" max="100" placeholder="0 - 100">
            </div>

            <div class="form-group">
              <label>Status</label>
              <select id="reviewStatus">
                <option>Pending</option>
                <option>In Progress</option>
                <option>Under Review</option>
                <option>Completed</option>
                <option>Overdue</option>
              </select>
            </div>

            <div class="form-group">
              <label>Reviewer Comment</label>
              <textarea id="reviewComment" rows="3" placeholder="Short, factual notes and evidence reference"></textarea>
            </div>
          </div>

          <div class="kpi-modal-footer">
            <button class="btn btn-secondary btn-small" type="button" id="btnCancelReview">
              Cancel
            </button>

            <button class="btn btn-secondary btn-small" type="button" id="btnSaveDraft">
              <i class="fa-solid fa-floppy-disk"></i> Save Draft
            </button>

            <button class="btn btn-primary btn-small" type="button" id="btnSubmitReview">
              <i class="fa-solid fa-paper-plane"></i> Submit
            </button>
          </div>
        </div>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>

    </main>

  </div>

  <!-- ✅ KPI Review Modal JS -->
  <script>
  (function () {
    const overlay = document.getElementById('kpiReviewOverlay');
    const closeBtn = document.getElementById('kpiReviewCloseBtn');
    const cancelBtn = document.getElementById('btnCancelReview');

    const metaTitle = document.getElementById('metaTitle');
    const metaTarget = document.getElementById('metaTarget');
    const metaType = document.getElementById('metaType');

    const inpActual = document.getElementById('reviewActual');
    const inpScore = document.getElementById('reviewScore');
    const selStatus = document.getElementById('reviewStatus');
    const inpComment = document.getElementById('reviewComment');

    const btnSave = document.getElementById('btnSaveDraft');
    const btnSubmit = document.getElementById('btnSubmitReview');

    let activeRow = null;

    function openModal(row) {
      activeRow = row;

      const title = row.querySelector('.kpi-link')?.textContent?.trim() || '—';
      const cells = row.querySelectorAll('td');

      const target = cells[1]?.textContent?.trim() || '—';
      const type = cells[2]?.textContent?.trim() || '—';
      const statusText = row.querySelector('.kpi-badge')?.textContent?.trim() || 'Pending';
      const scoreText = cells[4]?.textContent?.trim() || '–';

      metaTitle.textContent = title;
      metaTarget.textContent = target;
      metaType.textContent = type;

      // reset form fields
      inpActual.value = '';
      inpComment.value = '';

      // prefill score if exists
      inpScore.value = (scoreText !== '–' && scoreText !== '—') ? parseInt(scoreText, 10) : '';

      // prefill status
      selStatus.value = ['Pending','In Progress','Under Review','Completed','Overdue'].includes(statusText)
        ? statusText
        : 'Pending';

      overlay.classList.add('show');
      overlay.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
      overlay.classList.remove('show');
      overlay.setAttribute('aria-hidden', 'true');
      activeRow = null;
    }

    function setBadgeClass(badge, statusVal) {
      badge.classList.remove('kpi-pending','kpi-in-progress','kpi-overdue','kpi-under-review','kpi-completed');

      if (statusVal === 'Pending') badge.classList.add('kpi-pending');
      else if (statusVal === 'In Progress') badge.classList.add('kpi-in-progress');
      else if (statusVal === 'Overdue') badge.classList.add('kpi-overdue');
      else if (statusVal === 'Under Review') badge.classList.add('kpi-under-review');
      else if (statusVal === 'Completed') badge.classList.add('kpi-completed');

      badge.textContent = statusVal;
    }

    function applyToRow(mode) {
      if (!activeRow) return;

      const cells = activeRow.querySelectorAll('td');
      const badge = activeRow.querySelector('.kpi-badge');

      // Update score column
      const scoreVal = inpScore.value ? `${inpScore.value}%` : '–';
      cells[4].textContent = scoreVal;

      // Save Draft -> force Under Review (common UX)
      const statusVal = (mode === 'draft') ? 'Under Review' : selStatus.value;

      setBadgeClass(badge, statusVal);
      closeModal();
    }

    // Bind Review buttons
    document.querySelectorAll('.kpi-btn-review').forEach(btn => {
      btn.addEventListener('click', function () {
        const row = btn.closest('tr');
        if (row) openModal(row);
      });
    });

    // Close interactions
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeModal();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
    });

    // Footer buttons
    btnSave.addEventListener('click', () => applyToRow('draft'));
    btnSubmit.addEventListener('click', () => applyToRow('submit'));
  })();
  </script>

</body>
</html>
