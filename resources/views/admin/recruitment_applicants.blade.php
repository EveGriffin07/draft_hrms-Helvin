<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Applicants - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

  <style>
    /* CSS FIX: Stack Name and Email Vertically */
    .user-info {
        display: flex;
        flex-direction: column; /* Puts email below name */
        justify-content: center;
    }
    .user-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
        line-height: 1.2;
    }
    .user-email {
        font-size: 12px;
        color: #6b7280; /* Grey color for email */
        margin-top: 2px;
    }

    /* CSS FIX: Align Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #4b5563;
        transition: 0.2s;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-icon:hover { background: #f3f4f6; color: #111827; }
    .btn-icon.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
  </style>
</head>
<body>

  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info-header">
      <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
        <i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name ?? 'HR Admin' }}
      </a>
    </div>
  </header>

  <div class="container">

    @include('admin.layout.sidebar')

    <main>
      <div class="breadcrumb">Home > Recruitment > Applicants</div>
      <h2>Applicant Management</h2>
      <p class="subtitle">Review and manage candidates for open positions.</p>

      <div class="table-container">
        
        <div class="table-actions">
           <input type="text" placeholder="Search applicants..." style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 250px;">
        </div>

        <table class="hr-table">
          <thead>
            <tr>
              <th>Applicant Name</th>
              <th>Applied For</th>
              <th>Applied Date</th>
              <th>Stage</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            
            @forelse($applications as $app)
            <tr>
              <td>
                <div class="user-profile">
                  {{-- FIXED: Flex-direction column handles the stacking --}}
                  <div class="user-info">
                    <span class="user-name">{{ $app->applicant->full_name ?? 'Unknown Applicant' }}</span>
                    <span class="user-email">{{ $app->applicant->user->email ?? 'No Email' }}</span>
                  </div>
                </div>
              </td>
              <td>
                <span style="font-weight: 500; color: #333;">{{ $app->job->job_title ?? 'Job Deleted' }}</span>
              </td>
              <td>{{ $app->created_at->format('d M Y') }}</td>
              <td>
                @if($app->app_stage == 'Hired')
                    <span class="badge badge-success">Hired</span>
                @elseif($app->app_stage == 'Interview')
                    <span class="badge badge-important">Interview</span>
                @elseif($app->app_stage == 'Rejected')
                    <span class="badge badge-critical">Rejected</span>
                @else
                    <span class="badge badge-normal">{{ $app->app_stage }}</span>
                @endif
              </td>
              <td>
                <div class="action-buttons">
                    <a href="{{ route('admin.applicants.show', $app->application_id) }}" class="btn-icon" title="View Profile">
                        <i class="fa-solid fa-eye"></i>
                    </a>

                    <form action="{{ route('admin.applicants.updateStatus', $app->application_id) }}" method="POST" style="margin:0;">
                        @csrf
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="btn-icon delete" title="Reject" onclick="return confirm('Reject this applicant?')">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding: 30px; color: #666;">
                    No applicants found yet.
                </td>
            </tr>
            @endforelse

          </tbody>
        </table>
      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>
</body>
</html>