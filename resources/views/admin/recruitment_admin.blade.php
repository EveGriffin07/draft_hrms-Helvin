<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recruitment Management - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
      /* Quick fix to align buttons horizontally */
      .action-buttons { display: flex; gap: 5px; }
  </style>
</head>
<body>

  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
      <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
        <i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name ?? 'HR Admin' }}
      </a>
    </div>
  </header>

  <div class="container">

    @include('admin.layout.sidebar')

    <main>
      <div class="breadcrumb">Home > Dashboard > Recruitment</div>
      <h2>Recruitment Management</h2>
      <p class="subtitle">Manage job postings and track applicant status.</p>

      @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #a7f3d0;">
          <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
      @endif

      <div class="table-container">

        <div class="table-actions">
          <a href="{{ route('admin.recruitment.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Post New Job
          </a>
        </div>

        <table class="hr-table">
          <thead>
            <tr>
              <th>Job Title</th>
              <th>Department</th>
              <th>Type</th>
              <th>Posted Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            
            @forelse($jobPosts as $job)
            <tr>
              <td>
                <div class="user-profile">
                  <div class="user-info">
                    <span class="user-name">{{ $job->job_title }}</span>
                    <span class="user-role">{{ $job->location }}</span>
                  </div>
                </div>
              </td>
              <td>{{ $job->department }}</td>
              <td>
                <span class="badge badge-normal">{{ $job->job_type }}</span>
              </td>
              <td>{{ $job->created_at->format('d M Y') }}</td>
              <td>
                @if($job->job_status === 'Open')
                    <span class="badge badge-success">Open</span>
                @else
                    <span class="badge badge-critical">{{ $job->job_status }}</span>
                @endif
              </td>
              <td>
                <div class="action-buttons">
                    {{-- 1. View Applicants (Link) --}}
                    <a href="{{ route('admin.applicants.index') }}" class="btn-icon" title="View Applicants">
                        <i class="fa-solid fa-users"></i>
                    </a>

                    {{-- 2. Edit Job (Link) --}}
                    <a href="{{ route('admin.recruitment.edit', $job->job_id) }}" class="btn-icon" title="Edit Job">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    {{-- 3. Delete Job (Form) --}}
                    <form action="{{ route('admin.recruitment.destroy', $job->job_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon delete" title="Delete Job" onclick="return confirm('Are you sure you want to delete this job post?');">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 30px; color: #666;">
                    <i class="fa-solid fa-briefcase" style="font-size: 24px; margin-bottom: 10px; color: #ccc;"></i><br>
                    No job posts found. Click "Post New Job" to create one.
                </td>
            </tr>
            @endforelse

          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const groups = document.querySelectorAll('.sidebar-group');
      groups.forEach(group => {
        const toggle = group.querySelector('.sidebar-toggle');
        if (!toggle) return;
        toggle.addEventListener('click', function (e) {
          e.preventDefault();
          groups.forEach(g => { if (g !== group) g.classList.remove('open'); });
          group.classList.toggle('open');
        });
      });
    });
  </script>

</body>
</html>