<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Job Posting - HRMS</title>

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
      <div class="breadcrumb">Home > Recruitment > Add Job Posting</div>
      <h2>Add Job Posting</h2>
      <p class="subtitle">Create a new job listing to attract applicants for your organization.</p>

      <div class="form-container">
        <form action="#" method="POST" class="form-card">
          <h3><i class="fa-solid fa-file-circle-plus"></i> Job Details</h3>

          <div class="form-group">
            <label for="jobTitle">Job Title <span>*</span></label>
            <input type="text" id="jobTitle" name="jobTitle" placeholder="Enter job title" required>
          </div>

          <div class="form-group">
            <label for="department">Department <span>*</span></label>
            <select id="department" name="department" required>
              <option value="" disabled selected>Select Department</option>
              <option value="Human Resources">Human Resources</option>
              <option value="Finance">Finance</option>
              <option value="IT">Information Technology</option>
              <option value="Sales">Sales</option>
              <option value="Marketing">Marketing</option>
            </select>
          </div>

          <div class="form-group">
            <label for="employmentType">Employment Type <span>*</span></label>
            <select id="employmentType" name="employmentType" required>
              <option value="" disabled selected>Select Type</option>
              <option value="Full-Time">Full-Time</option>
              <option value="Part-Time">Part-Time</option>
              <option value="Contract">Contract</option>
              <option value="Internship">Internship</option>
            </select>
          </div>

          <div class="form-group">
            <label for="location">Job Location <span>*</span></label>
            <input type="text" id="location" name="location" placeholder="e.g., Kuala Lumpur, Malaysia" required>
          </div>

          <div class="form-group">
            <label for="salaryRange">Salary Range (MYR)</label>
            <input type="text" id="salaryRange" name="salaryRange" placeholder="e.g., 3500 - 5000">
          </div>

          <div class="form-group full-width">
            <label for="description">Job Description <span>*</span></label>
            <textarea id="description" name="description" rows="5" placeholder="Enter detailed job description and responsibilities" required></textarea>
          </div>

          <div class="form-group full-width">
            <label for="requirements">Requirements</label>
            <textarea id="requirements" name="requirements" rows="4" placeholder="List required skills, qualifications, and experience"></textarea>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label for="closingDate">Application Closing Date</label>
              <input type="date" id="closingDate" name="closingDate">
            </div>

            <div class="form-group half">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="Open">Open</option>
                <option value="Closed">Closed</option>
              </select>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Job Posting</button>
            <a href="{{ route('admin.recruitment') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Recruitment</a>
          </div>
        </form>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  
</body>
</html>
