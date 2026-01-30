<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Training Program - HRMS</title>

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
      <div class="breadcrumb">Home > Training > Add Training Program</div>
      <h2>Add Training Program</h2>
      <p class="subtitle">Plan, schedule, and record employee development training sessions.</p>

      {{-- Display Validation Errors if any --}}
      @if ($errors->any())
        <div style="background-color: #fef2f2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif

      <div class="form-container">
        <form action="{{ route('admin.training.store') }}" method="POST" class="form-card">
          @csrf 
          
          <h3><i class="fa-solid fa-graduation-cap"></i> Training Details</h3>

          <div class="form-group">
            <label for="trainingTitle">Training Title <span>*</span></label>
            <input type="text" id="trainingTitle" name="trainingTitle" placeholder="e.g., Communication Skills Workshop" value="{{ old('trainingTitle') }}" required>
          </div>

          <div class="form-group">
            <label for="trainerName">Trainer Name <span>*</span></label>
            <input type="text" id="trainerName" name="trainerName" placeholder="e.g., John Tan" value="{{ old('trainerName') }}" required>
          </div>

          <div class="form-group">
            <label for="department">Department <span>*</span></label>
            <select id="department" name="department" required>
              <option value="" disabled selected>Select Department</option>
              <option value="HR">Human Resources</option>
              <option value="IT">Information Technology</option>
              <option value="Finance">Finance</option>
              <option value="Sales">Sales</option>
              <option value="Marketing">Marketing</option>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label for="startDate">Start Date <span>*</span></label>
              <input type="date" id="startDate" name="startDate" value="{{ old('startDate') }}" required>
            </div>
            <div class="form-group half">
              <label for="endDate">End Date <span>*</span></label>
              <input type="date" id="endDate" name="endDate" value="{{ old('endDate') }}" required>
            </div>
          </div>

          <div class="form-group">
            <label for="mode">Mode <span>*</span></label>
            <select id="mode" name="mode" required>
              <option value="" disabled selected>Select Mode</option>
              <option value="Online">Online</option>
              <option value="Onsite">Onsite</option>
            </select>
          </div>

          <div class="form-group">
            <label for="location">Training Location <span>*</span></label>
            <input
              type="text"
              id="location"
              name="location"
              placeholder="e.g., HR Training Room 1, Kuala Lumpur or Zoom / MS Teams"
              value="{{ old('location') }}"
              required
            >
          </div>

          <div class="form-group full-width">
            <label for="description">Training Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Enter brief description of training objectives">{{ old('description') }}</textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-floppy-disk"></i> Save Training
            </button>
            <a href="{{ route('admin.training') }}" class="btn btn-secondary">
              <i class="fa-solid fa-arrow-left"></i> Back to Training
            </a>
          </div>
        </form>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>
</body>
</html>