<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ isset($job) ? 'Edit Job' : 'Create Job' }} - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <style>
    .status-box { 
            background: #fff1f2; 
            padding: 20px; 
            border-radius: 8px; 
            border: 1px solid #fecaca; 
            margin-top: 30px; 
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
    <main>
      <div class="breadcrumb">Home > Recruitment > {{ isset($job) ? 'Edit Job' : 'Post New Job' }}</div>
      <h2>{{ isset($job) ? 'Edit Job Details' : 'Create New Job Post' }}</h2>
      <p class="subtitle">{{ isset($job) ? 'Update the details below. Changes are saved immediately.' : 'Fill in the details to publish a new job opening.' }}.</p>

      <div class="form-container">
        <form action="{{ isset($job) ? route('admin.recruitment.update', $job->job_id) : route('admin.recruitment.store') }}" method="POST" class="form-card">
          @csrf

          <h3><i class="fa-solid fa-briefcase"></i> Job Details</h3>

          <div class="form-group">
            <label for="job_title">Job Title <span>*</span></label>
            <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $job->job_title ?? '') }}" placeholder="E.g., Senior Software Engineer" required>
          </div>

          <div class="form-group">
            <label for="job_type">Job Type <span>*</span></label>
            <select id="job_type" name="job_type" required>
              <option value="Full-Time" {{ (old('job_type', $job->job_type ?? '') == 'Full-Time') ? 'selected' : '' }}>Full-Time</option>
                            <option value="Part-Time" {{ (old('job_type', $job->job_type ?? '') == 'Part-Time') ? 'selected' : '' }}>Part-Time</option>
                            <option value="Contract" {{ (old('job_type', $job->job_type ?? '') == 'Contract') ? 'selected' : '' }}>Contract</option>
                            <option value="Internship" {{ (old('job_type', $job->job_type ?? '') == 'Internship') ? 'selected' : '' }}>Internship</option>
            </select>
          </div>

          <div class="form-group">
            <label for="department">Department <span>*</span></label>
            <select id="department" name="department" required>
              <option value="Human Resources" {{ (old('department', $job->department ?? '') == 'Human Resources') ? 'selected' : '' }}>Human Resources</option>
              <option value="Finance" {{ (old('department', $job->department ?? '') == 'Finance') ? 'selected' : '' }}>Finance</option>
              <option value="IT" {{ (old('department', $job->department ?? '') == 'IT') ? 'selected' : '' }}>Information Technology</option>
              <option value="Sales" {{ (old('department', $job->department ?? '') == 'Sales') ? 'selected' : '' }}>Sales</option>
              <option value="Marketing" {{ (old('department', $job->department ?? '') == 'Marketing') ? 'selected' : '' }}>Marketing</option>
            </select>
          </div>

          <div class="form-group">
            <label for="location">Location <span>*</span></label>
            <input type="text" id="location" value="{{ old('location', $job->location ?? '') }}" name="location" placeholder="E.g., Kuala Lumpur (Hybrid)" required>
          </div>

          <div class="form-group">
            <label for="salary_range">Salary Range <span>*</span></label>
            <input type="text" id="salary_range" value="{{ old('salary_range', $job->salary_range ?? '') }}" name="salary_range" placeholder="E.g., RM 4,000 - RM 6,000" required>
          </div>

          <div class="form-group full-width">
            <label for="job_description">Job Description <span>*</span></label>
            <textarea id="job_description" name="job_description" rows="5" required>{{ old('job_description', $job->job_description ?? '') }}</textarea>
          </div>

          <div class="form-group full-width">
            <label for="requirements">Requirements <span>*</span></label>
            <textarea id="requirements" name="requirements" rows="5" required>{{ old('requirements', $job->requirements ?? '') }}</textarea>
          </div>

          <div class="form-group">
                        <label>Closing Date</label>
                        {{-- If editing, show the existing date. If new, default to empty. --}}
                        <input type="date" name="closing_date" value="{{ old('closing_date', $job->closing_date ?? '') }}" required>
                    </div>

          {{-- STATUS (Only for Editing) --}}
                    @if(isset($job))
                    <div class="status-box">
                        <label style="color: #991b1b;">Job Status</label>
                        <select name="job_status" style="width:100%; border-color: #fca5a5;">
                            <option value="Open" {{ $job->job_status == 'Open' ? 'selected' : '' }}>Open (Accepting Applicants)</option>
                            <option value="Closed" {{ $job->job_status == 'Closed' ? 'selected' : '' }}>Closed (Hidden from public)</option>
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="job_status" value="Open">
                    @endif

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-floppy-disk"></i> {{ isset($job) ? 'Update Job Post' : 'Publish Job Post' }}
            </button>
            <a href="{{ route('admin.recruitment.index') }}" class="btn btn-secondary">
              <i class="fa-solid fa-arrow-left"></i> Cancel
            </a>
          </div>

        </form>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  
</body>
</html>
