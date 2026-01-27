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
        .onboarding-header-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
            border: 1px solid #e5e7eb;
        }

        .onboarding-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .onboarding-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .onboarding-title i {
            color: #2563eb;
        }

        .onboarding-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 20px;
            font-size: 13px;
            color: #4b5563;
            margin-top: 10px;
        }

        .onboarding-meta span i {
            margin-right: 4px;
            color: #9ca3af;
        }

        .onboarding-status-pill {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            background: #eff6ff;
            color: #1d4ed8;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .onboarding-progress-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 14px;
            font-size: 13px;
            color: #4b5563;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: #2563eb;
            width: 80%; /* dummy value */
        }

        .onboarding-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .onboarding-summary-card {
            background: #ffffff;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .onboarding-summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .onboarding-summary-value {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .checklist-section {
            background: #ffffff;
            border-radius: 12px;
            padding: 18px 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }

        .checklist-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 12px;
        }

        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .checklist-table th,
        .checklist-table td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
        }

        .checklist-table th {
            background: #f1f5f9;
            font-weight: 600;
            color: #374151;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-completed {
            background: #ecfdf3;
            color: #15803d;
        }

        .badge-in-progress {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-pending {
            background: #fef9c3;
            color: #a16207;
        }

        .badge-overdue {
            background: #fef2f2;
            color: #b91c1c;
        }

        .onboarding-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .onboarding-header-top {
                flex-direction: column;
                align-items: flex-start;
            }
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
        <div class="breadcrumb">Home > Onboarding > Employee Checklist</div>
        <h2>Onboarding Checklist</h2>
        <p class="subtitle">View detailed onboarding tasks and completion status for this new employee.</p>

        <!-- Employee header / summary -->
        <div class="onboarding-header-card">
            <div class="onboarding-header-top">
                <div>
                    <div class="onboarding-title">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Nur Aisyah &mdash; Finance Department</span>
                    </div>
                    <div class="onboarding-meta">
                        <span><i class="fa-solid fa-id-card"></i> Employee ID: EMP-0134</span>
                        <span><i class="fa-solid fa-briefcase"></i> Role: Junior Accountant</span>
                        <span><i class="fa-solid fa-user-tie"></i> Reporting Manager: Mr. Daniel Tan</span>
                        <span><i class="fa-solid fa-calendar-day"></i> Start Date: 01 Nov 2025</span>
                        <span><i class="fa-solid fa-calendar-check"></i> Deadline: 10 Nov 2025</span>
                    </div>
                </div>

                <div class="onboarding-status-pill">
                    <i class="fa-solid fa-circle-dot"></i>
                    In Progress
                </div>
            </div>

            <div class="onboarding-progress-row">
                <span>Overall Progress</span>
                <div class="progress-bar">
                    <div class="progress-bar-fill"></div>
                </div>
                <span>80%</span>
            </div>
        </div>

        <!-- Small stats -->
        <div class="onboarding-summary">
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Total Tasks</div>
                <div class="onboarding-summary-value">5</div>
            </div>
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Completed</div>
                <div class="onboarding-summary-value">3</div>
            </div>
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Pending</div>
                <div class="onboarding-summary-value">1</div>
            </div>
            <div class="onboarding-summary-card">
                <div class="onboarding-summary-label">Overdue</div>
                <div class="onboarding-summary-value">1</div>
            </div>
        </div>

        <!-- Checklist table -->
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
                    <th>Owner</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Submit required documents</td>
                    <td>Documents</td>
                    <td>2025-11-02</td>
                    <td><span class="badge-status badge-completed">Completed</span></td>
                    <td>HR</td>
                    <td>All forms submitted and verified.</td>
                </tr>
                <tr>
                    <td>Attend company orientation</td>
                    <td>Orientation</td>
                    <td>2025-11-03</td>
                    <td><span class="badge-status badge-completed">Completed</span></td>
                    <td>HR</td>
                    <td>Joined full half-day session.</td>
                </tr>
                <tr>
                    <td>Setup system credentials</td>
                    <td>IT Setup</td>
                    <td>2025-11-04</td>
                    <td><span class="badge-status badge-in-progress">In Progress</span></td>
                    <td>IT Department</td>
                    <td>Laptop issued, waiting for VPN access.</td>
                </tr>
                <tr>
                    <td>Meet assigned buddy</td>
                    <td>Team Integration</td>
                    <td>2025-11-05</td>
                    <td><span class="badge-status badge-pending">Not Started</span></td>
                    <td>Reporting Manager</td>
                    <td>Schedule meeting in first week.</td>
                </tr>
                <tr>
                    <td>Review and acknowledge HR policies</td>
                    <td>HR Policy</td>
                    <td>2025-11-06</td>
                    <td><span class="badge-status badge-overdue">Overdue</span></td>
                    <td>Employee</td>
                    <td>Reminder email sent.</td>
                </tr>
                </tbody>
            </table>
        </section>

        <!-- Actions -->
        <div class="onboarding-actions">
            <a href="{{ route('admin.onboarding') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Onboarding
            </a>
            <button type="button" class="btn btn-primary">
                <i class="fa-solid fa-check-circle"></i> Mark Onboarding as Completed
            </button>
        </div>

        <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
</div>



</body>
</html>