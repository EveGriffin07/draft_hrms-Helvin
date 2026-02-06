<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Onboarding - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    /* Simple Progress Bar Styles */
    .progress-container { background: #e5e7eb; border-radius: 99px; height: 12px; width: 100%; overflow: hidden; margin: 10px 0; }
    .progress-fill { background: #2563eb; height: 100%; transition: width 0.3s ease; }
    
    /* Task Item Card */
    .task-card {
        background: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; margin-bottom: 12px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .task-info h4 { margin: 0 0 4px 0; font-size: 15px; color: #111827; }
    .task-info p { margin: 0; font-size: 13px; color: #6b7280; }
    .badge-done { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: 500; }
  </style>
</head>

<body>
<header>
  <div class="title">Web-Based HRMS</div>
  <div class="user-info">
    <span>Welcome, {{ Auth::user()->name }}</span>
  </div>
</header>

<div class="container">
  {{-- Sidebar Include --}}
  @include('employee.layout.sidebar')

  <main>
    <div class="breadcrumb">Home > My Onboarding</div>
    
    @if($onboarding)
        <h2>My Onboarding Journey</h2>
        <p class="subtitle">Complete these tasks to get settled in your new role.</p>

        {{-- Progress Card --}}
        <div class="card" style="margin-bottom: 24px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                <strong>Overall Progress</strong>
                <span>{{ $onboarding->progress }}%</span>
            </div>
            <div class="progress-container">
                <div class="progress-fill" style="width: {{ $onboarding->progress }}%;"></div>
            </div>
            <div style="font-size:13px; color:#6b7280;">
                Deadline: {{ \Carbon\Carbon::parse($onboarding->end_date)->format('d M Y') }}
            </div>
        </div>

        {{-- Tasks List --}}
        <h3>Your Checklist</h3>
        
        @foreach($onboarding->tasks as $task)
        <div class="task-card">
            <div class="task-info">
                <h4>{{ $task->task_name }}</h4>
                <p>
                    <i class="fa-solid fa-tag"></i> {{ $task->category }} &nbsp;|&nbsp; 
                    <i class="fa-regular fa-calendar"></i> Due: {{ $task->due_date ?? 'No Date' }}
                </p>
                @if($task->remarks)
                    <p style="margin-top:4px; color:#4b5563; font-style:italic;">"{{ $task->remarks }}"</p>
                @endif
            </div>

            <div class="task-action">
                @if($task->is_completed)
                    <span class="badge-done"><i class="fa-solid fa-check"></i> Completed</span>
                @else
                    <form action="{{ route('employee.onboarding.complete', $task->task_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size:13px;">
                            Mark as Done
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach

    @else
        {{-- Empty State --}}
        <div class="card" style="text-align:center; padding: 40px;">
            <i class="fa-solid fa-clipboard-check" style="font-size: 40px; color: #d1d5db; margin-bottom: 16px;"></i>
            <h3>No Active Onboarding</h3>
            <p style="color:#6b7280;">You have no onboarding tasks assigned at the moment.</p>
        </div>
    @endif

    <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
  </main>
</div>
</body>
</html>