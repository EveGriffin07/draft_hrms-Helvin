<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Announcements - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

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
      <div class="breadcrumb">Home > Dashboard > View Announcements</div>
      <h2>Announcements</h2>
      <p class="subtitle">List of announcements currently used in the dashboard.</p>

      <div class="table-container">

        <div class="table-actions">
          <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Announcement
          </a>
        </div>

        <table class="hr-table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Date</th>
              <th>Priority</th>
              <th>Audience</th>
            </tr>
          </thead>
          <tbody>
            @forelse($announcements as $announce)
            <tr>
              <td>
                <a href="{{ route('admin.announcements.show', $announce->announcement_id) }}" class="link-title">
                  {{ $announce->title }}
                </a>
              </td>
              <td>
                  {{ $announce->publish_at ? $announce->publish_at->format('Y-m-d') : $announce->created_at->format('Y-m-d') }}
              </td>
              <td>
                @if($announce->priority == 'Critical')
                    <span class="badge badge-critical">Critical</span>
                @elseif($announce->priority == 'Important')
                    <span class="badge badge-important">Important</span>
                @else
                    <span class="badge badge-normal">Normal</span>
                @endif
              </td>
              <td>{{ $announce->audience_type }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; padding: 20px; color: #666;">
                    No announcements found. Click "Add Announcement" to create one.
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