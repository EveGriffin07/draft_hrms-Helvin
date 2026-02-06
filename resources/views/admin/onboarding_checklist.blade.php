<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Checklist - HRMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
    <style>
        /* Keeping your existing CSS */
        .onboarding-header-card { background: #ffffff; border-radius: 12px; padding: 18px 20px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); border: 1px solid #e5e7eb; }
        .onboarding-header-top { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .onboarding-title { font-size: 18px; font-weight: 600; color: #111827; display: flex; align-items: center; gap: 10px; }
        .onboarding-title i { color: #2563eb; }
        .onboarding-meta { display: flex; flex-wrap: wrap; gap: 12px 20px; font-size: 13px; color: #4b5563; margin-top: 10px; }
        .onboarding-meta span i { margin-right: 4px; color: #9ca3af; }
        .onboarding-status-pill { padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 500; background: #eff6ff; color: #1d4ed8; display: inline-flex; align-items: center; gap: 6px; }
        .onboarding-progress-row { display: flex; align-items: center; gap: 12px; margin-top: 14px; font-size: 13px; color: #4b5563; }
        .progress-bar { flex: 1; height: 8px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: #2563eb; }
        .onboarding-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 14px; margin-bottom: 22px; }
        .onboarding-summary-card { background: #ffffff; padding: 14px 16px; border-radius: 12px; border: 1px solid #e5e7eb; }
        .onboarding-summary-label { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
        .onboarding-summary-value { font-size: 18px; font-weight: 600; color: #111827; }
        .checklist-section { background: #ffffff; border-radius: 12px; padding: 18px 20px; border: 1px solid #e5e7eb; box-shadow: 0 4px 10px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .checklist-section h3 { font-size: 16px; font-weight: 600; color: #1e3a8a; margin-bottom: 12px; }
        .checklist-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .checklist-table th, .checklist-table td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; }
        .checklist-table th { background: #f1f5f9; font-weight: 600; color: #374151; }
        .badge-status { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 999px; font-size: 11px; font-weight: 500; }
        .badge-completed { background: #ecfdf3; color: #15803d; }
        .badge-in-progress { background: #eff6ff; color: #1d4ed8; }
        .badge-pending { background: #fef9c3; color: #a16207; }
        .badge-overdue { background: #fef2f2; color: #b91c1c; }
        .onboarding-actions { display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 10px; }
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
        <div class="breadcrumb">Home > Onboarding > Employee Checklist</div>
        <h2>Onboarding Checklist</h2>
        <p class="subtitle">View detailed onboarding tasks and completion status for this new employee.</p>

        <div class="onboarding-header-card">
            <div class="onboarding-header-top">
                <div>
                    <div class="onboarding-title">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>{{ $onboarding->employee->user->name }} &mdash; {{ $onboarding->employee->department->department_name ?? 'No Dept' }}</span>
                    </div>
                    <div class="onboarding-meta">
                        <span><i class="fa-solid fa-id-card"></i> ID: {{ $onboarding->employee->employee_id }}</span>
                        <span><i class="fa-solid fa-briefcase"></i> Role: {{ $onboarding->employee->position->title ?? 'N/A' }}</span>
                        <span><i class="fa-solid fa-user-tie"></i> Assigned By: {{ $onboarding->assigned_by ?? 'Admin' }}</span>
                        <span><i class="fa-solid fa-calendar-day"></i> Start: {{ $onboarding->start_date }}</span>
                        <span><i class="fa-solid fa-calendar-check"></i> Deadline: {{ $onboarding->end_date }}</span>
                    </div>
                </div>

                <div class="onboarding-status-pill">
                    <i class="fa-solid fa-circle-dot"></i>
                    {{ $onboarding->status }}
                </div>
            </div>

            <div class="onboarding-progress-row">
                <span>Overall Progress</span>
                <div class="progress-bar">
                    <div class="progress-bar-fill" style="width: {{ $onboarding->progress }}%;"></div>
                </div>
                <span>{{ $onboarding->progress }}%</span>
            </div>
        </div>

        <div class="onboarding-summary">
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Total Tasks</div>
                <div class="onboarding-summary-value">{{ $totalTasks }}</div>
            </div>
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Completed</div>
                <div class="onboarding-summary-value">{{ $completedTasks }}</div>
            </div>
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Pending</div>
                <div class="onboarding-summary-value">{{ $pendingTasks }}</div>
            </div>
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Overdue</div>
                <div class="onboarding-summary-value">{{ $overdueTasks }}</div>
            </div>
        </div>

        <section class="checklist-section">
            <h3><i class="fa-solid fa-list-check"></i> Onboarding Tasks</h3>
            <p class="subtitle" style="margin-bottom: 10px;">
                Track the completion of tasks such as documents submission, orientation and system setup for this employee.
            </p>

            <table class="checklist-table">
                <thead>
                <tr>
                    <th>Task</th>
                    <th>Category</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                @forelse($onboarding->tasks as $task)
                <tr>
                    <td>{{ $task->task_name }}</td>
                    <td>{{ $task->category }}</td>
                    <td>{{ $task->due_date ?? '-' }}</td>
                    <td>
                        @if($task->is_completed)
                            <span class="badge-status badge-completed">Completed</span>
                        @elseif($task->due_date && $task->due_date < now())
                            <span class="badge-status badge-overdue">Overdue</span>
                        @else
                            <span class="badge-status badge-pending">Pending</span>
                        @endif
                    </td>
                    <td>{{ $task->remarks ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center">No tasks assigned yet.</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </section>

        <div class="onboarding-actions">
            <a href="{{ route('admin.onboarding') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Onboarding
            </a>
            </div>

        <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
</div>
</body>
</html>