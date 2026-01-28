<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcement Details - HRMS</title>

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
      <div class="breadcrumb">Home > Dashboard > Announcement Details</div>

      <h2>Announcement Details</h2>
      <p class="subtitle">View the full content of this announcement.</p>

      <div class="detail-card">

        <h3 class="detail-title">
          <i class="fa-solid fa-bullhorn"></i> {{ $announcement->title }}
        </h3>

        <div class="detail-meta">
          <span><i class="fa-solid fa-calendar"></i> Date: {{ $announcement->publish_at ? $announcement->publish_at->format('d M Y') : $announcement->created_at->format('d M Y') }}</span>
          
          @if($announcement->priority == 'Critical')
            <span class="badge badge-critical">Critical</span>
          @elseif($announcement->priority == 'Important')
            <span class="badge badge-important">Important</span>
          @else
            <span class="badge badge-normal">Normal</span>
          @endif

          <span><i class="fa-solid fa-users"></i> Audience: {{ ucfirst($announcement->audience_type) }}</span>
        </div>

        <div class="detail-body">
          <p>{!! nl2br(e($announcement->content)) !!}</p>

          @if($announcement->remarks)
            <hr style="margin-top: 20px; border: 0; border-top: 1px solid #eee;">
            <p><strong style="color: #666;">Internal Notes:</strong> <br> {{ $announcement->remarks }}</p>
          @endif
        </div>

        <div class="detail-actions">
          <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Announcements
          </a>
        </div>

      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

</body>
</html>