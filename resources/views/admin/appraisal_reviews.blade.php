<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Performance Reviews - HRMS</title>

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
      <div class="breadcrumb">Home > Appraisal > Performance Reviews</div>
      <h2>Performance Reviews</h2>
      <p class="subtitle">Evaluate employee performance and record scores based on KPI achievements.</p>

      <div class="form-container">
        <form action="#" method="POST" class="form-card">
          <h3><i class="fa-solid fa-user-check"></i> Employee Performance Review</h3>

          <div class="form-group">
            <label for="employeeName">Select Employee <span>*</span></label>
            <select id="employeeName" name="employeeName" required>
              <option value="" disabled selected>Select Employee</option>
              <option value="Aisyah">Nur Aisyah (Finance)</option>
              <option value="Daniel">Daniel Tan (Marketing)</option>
              <option value="Rahman">Rahman Ali (IT)</option>
              <option value="Siti">Siti Noor (HR)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="reviewPeriod">Review Period <span>*</span></label>
            <select id="reviewPeriod" name="reviewPeriod" required>
              <option value="" disabled selected>Select Period</option>
              <option value="Q1">Quarter 1 (Jan - Mar)</option>
              <option value="Q2">Quarter 2 (Apr - Jun)</option>
              <option value="Q3">Quarter 3 (Jul - Sep)</option>
              <option value="Q4">Quarter 4 (Oct - Dec)</option>
            </select>
          </div>

          <div class="form-group full-width">
            <label for="kpiReference">KPI Reference</label>
            <textarea id="kpiReference" name="kpiReference" rows="3" placeholder="e.g., Achieved 85% sales target or completed system upgrade phase 1"></textarea>
          </div>

          <div class="form-group">
            <label for="score">Performance Score (%) <span>*</span></label>
            <input type="number" id="score" name="score" min="0" max="100" placeholder="Enter percentage score" required>
          </div>

          <small class="field-help">
    This value will be shown as <strong>Performance Score (%)</strong>
    in the Employee KPI Records and Appraisal Reports.
</small>

<p></p>
          <div class="form-group">
            <label for="rating">Rating <span>*</span></label>
            <select id="rating" name="rating" required>
              <option value="" disabled selected>Select Rating</option>
              <option value="Excellent">Excellent</option>
              <option value="Good">Good</option>
              <option value="Average">Average</option>
              <option value="Below Average">Below Average</option>
              <option value="Poor">Poor</option>
            </select>
          </div>

          <div class="form-group full-width">
            <label for="comments">Comments & Feedback</label>
            <textarea id="comments" name="comments" rows="4" placeholder="Provide detailed comments or recommendations for improvement"></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Review</button>
            <a href="{{ route('admin.appraisal') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Appraisal</a>
          </div>
        </form>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

</body>
</html>
