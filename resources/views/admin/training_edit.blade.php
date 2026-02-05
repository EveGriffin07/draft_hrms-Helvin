<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Training Program - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>
<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info"><a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;"><i class="fa-regular fa-bell"></i> &nbsp; HR Admin</a></div>
  </header>

  <div class="container">
    @include('admin.layout.sidebar')
    <main>
      <div class="breadcrumb">Home > Training > Edit Training Program</div>
      <h2>Edit Training Program</h2>

      <div class="form-container">
        <form action="{{ route('admin.training.update', $program->training_id) }}" method="POST" class="form-card">
          @csrf 
          {{-- IMPORTANT: Update requires specific route --}}
          
          <h3><i class="fa-solid fa-pen-to-square"></i> Edit Details</h3>

          <div class="form-group">
            <label>Training Title <span>*</span></label>
            <input type="text" name="trainingTitle" value="{{ $program->training_name }}" required>
          </div>

          <div class="form-group">
            <label>Trainer Name <span>*</span></label>
            <input type="text" name="trainerName" value="{{ $program->provider }}" required>
          </div>

          <div class="form-group">
            <label for="department">Department <span>*</span></label>
            <select id="department" name="department" required>
              <option value="" disabled selected>Select Department</option>

              {{-- Loop through the database results --}}
              @foreach($departments as $dept)
                <option value="{{ $dept->department_name }}" {{ old('department') == $dept->department_name ? 'selected' : '' }}>
                    {{ $dept->department_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>Start Date <span>*</span></label>
              <input type="date" name="startDate" value="{{ $program->start_date }}" required>
            </div>
            <div class="form-group half">
              <label>End Date <span>*</span></label>
              <input type="date" name="endDate" value="{{ $program->end_date }}" required>
            </div>
          </div>

          <div class="form-group">
            <label>Mode <span>*</span></label>
            <select name="mode" required>
              <option value="Online" {{ $program->mode == 'Online' ? 'selected' : '' }}>Online</option>
              <option value="Onsite" {{ $program->mode == 'Onsite' ? 'selected' : '' }}>Onsite</option>
            </select>
          </div>

          <div class="form-group">
            <label>Location <span>*</span></label>
            <input type="text" name="location" value="{{ $program->location }}" required>
          </div>

          <div class="form-group full-width">
            <label>Training Description</label>
            <textarea name="description" rows="4">{{ $program->tr_description }}</textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Program</button>
            <a href="{{ route('admin.training.show', $program->training_id) }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>