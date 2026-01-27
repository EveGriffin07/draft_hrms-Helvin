<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Program Details - HRMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

    <style>
        /* Main detail card */
        .training-detail-card,
        .participants-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
            margin-bottom: 24px;
        }

        .training-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .training-header-left h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .training-header-left .detail-subtitle {
            font-size: 13px;
            color: #6b7280;
        }

        .training-header-right {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .training-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px 24px;
            margin-top: 18px;
        }

        .meta-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #9ca3af;
            display: block;
        }

        .meta-value {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
        }

        .training-section-title {
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .training-detail-card ul {
            margin-left: 18px;
            margin-top: 6px;
        }

        /* Participants card */
        .participants-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .pill-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pill {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            background: #f3f4f6;
            color: #374151;
        }

        .pill-green {
            background: #dcfce7;
            color: #166534;
        }

        .pill-gray {
            background: #e5e7eb;
            color: #4b5563;
        }

        .participants-card .open-positions table {
            margin-top: 0;
        }

        .participants-card th,
        .participants-card td {
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .training-header {
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
        <div class="breadcrumb">Home > Training > Training Details</div>
        <h2>Training Program Details</h2>
        <p class="subtitle">View full information and participants for this employee training program.</p>

        {{-- MAIN TRAINING INFO CARD --}}
        <div class="training-detail-card">
            <div class="training-header">
                <div class="training-header-left">
                    <h3>
                        <i class="fa-solid fa-chalkboard-user"></i>
                        &nbsp;Leadership Workshop
                    </h3>
                    <p class="detail-subtitle">
                        Core leadership and communication skills for supervisors.
                    </p>
                </div>

                <div class="training-header-right">
                    <span class="status-badge status-completed">Completed</span>
                    <a href="{{ route('admin.training') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Training
                    </a>
                </div>
            </div>

            <div class="training-meta-grid">
    <div>
        <span class="meta-label">Trainer</span>
        <span class="meta-value">John Tan</span>
    </div>
    <div>
        <span class="meta-label">Department</span>
        <span class="meta-value">Human Resources</span>
    </div>
    <div>
        <span class="meta-label">Mode</span>
        <span class="meta-value">On-site</span>
    </div>
    <div>
        <span class="meta-label">Location</span>
        <span class="meta-value">HR Training Room 1, Kuala Lumpur</span>
        {{-- later this can come from the "location" field in database --}}
    </div>
    <div>
        <span class="meta-label">Start Date</span>
        <span class="meta-value">01 Nov 2025</span>
    </div>
    <div>
        <span class="meta-label">End Date</span>
        <span class="meta-value">05 Nov 2025</span>
    </div>
    <div>
        <span class="meta-label">Duration</span>
        <span class="meta-value">5 days</span>
    </div>
</div>

            <h3 class="training-section-title">
                <i class="fa-solid fa-book-open"></i>
                Training Description & Objectives
            </h3>
            <p>
                This workshop focuses on developing essential leadership, communication, and people
                management skills for new and existing supervisors. Participants will learn how to
                give feedback, handle conflicts and motivate their team members.
            </p>
        </div>

        {{-- PARTICIPANTS CARD --}}
        <div class="participants-card">
            <div class="participants-header">
                <h3 class="training-section-title" style="margin-top:0;">
                    <i class="fa-solid fa-users"></i>
                    Participants
                </h3>
                <div class="pill-group">
                    <span class="pill">Total: 18</span>
                    <span class="pill pill-green">Completed: 16</span>
                    <span class="pill pill-gray">No-show: 2</span>
                </div>
            </div>

            <div class="open-positions">
                <table>
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Attendance Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nur Aisyah</td>
                            <td>IT</td>
                            <td>Software Engineer</td>
                            <td>Completed</td>
                            <td>Attended all sessions</td>
                        </tr>
                        <tr>
                            <td>Daniel Lee</td>
                            <td>Sales</td>
                            <td>Sales Executive</td>
                            <td>Completed</td>
                            <td>Good participation</td>
                        </tr>
                        <tr>
                            <td>Ahmad Faiz</td>
                            <td>Finance</td>
                            <td>Accountant</td>
                            <td>No-show</td>
                            <td>Absent due to urgent work</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
</div>



</body>
</html>
