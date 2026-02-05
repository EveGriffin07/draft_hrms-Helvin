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
        .training-detail-card, .participants-card {
            background: #ffffff; border-radius: 16px; padding: 20px 24px;
            border: 1px solid #e5e7eb; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06); margin-bottom: 24px;
        }
        .training-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
        .training-header-left h3 { font-size: 20px; font-weight: 600; margin-bottom: 4px; }
        .training-header-left .detail-subtitle { font-size: 13px; color: #6b7280; }
        .training-header-right { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .status-badge { padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 500; }
        .status-completed { background: #dcfce7; color: #166534; }
        .status-active { background: #dbeafe; color: #1e40af; }
        .status-planned { background: #ffedd5; color: #9a3412; }
        .training-meta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px 24px; margin-top: 18px; }
        .meta-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #9ca3af; display: block; }
        .meta-value { font-size: 14px; font-weight: 500; color: #111827; }
        .training-section-title { font-size: 16px; font-weight: 600; margin-top: 20px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .participants-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .pill-group { display: flex; gap: 8px; flex-wrap: wrap; }
        .pill { padding: 4px 10px; border-radius: 999px; font-size: 12px; background: #f3f4f6; color: #374151; }
        .pill-green { background: #dcfce7; color: #166534; }
        
        /* Modal Overlay Styles */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;
        }
        .modal-content {
            background: white; padding: 24px; border-radius: 12px; width: 400px; max-width: 90%; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) { .training-header { flex-direction: column; align-items: flex-start; } }
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

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <div style="background-color: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
        @endif

        {{-- MAIN TRAINING INFO CARD --}}
        <div class="training-detail-card">
            <div class="training-header">
                <div class="training-header-left">
                    <h3><i class="fa-solid fa-chalkboard-user"></i> &nbsp;{{ $program->training_name }}</h3>
                    <p class="detail-subtitle">{{ Str::limit($program->tr_description, 100) }}</p>
                </div>
                <div class="training-header-right">
                    @if($program->tr_status == 'completed')
                        <span class="status-badge status-completed">Completed</span>
                    @elseif($program->tr_status == 'active')
                        <span class="status-badge status-active">Ongoing</span>
                    @else
                        <span class="status-badge status-planned">Upcoming</span>
                    @endif

                    <button onclick="openEnrollModal()" class="btn btn-primary" style="background-color: #3b82f6; border: none; cursor: pointer;">
                        <i class="fa-solid fa-plus"></i> Enroll Employee
                    </button>

                    <a href="{{ route('admin.training.edit', $program->training_id) }}" class="btn btn-secondary" style="border: 1px solid #d1d5db; background: white; color: #374151;">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>

                    <form action="{{ route('admin.training.delete', $program->training_id) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete the program and ALL participant records.');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" style="border: 1px solid #fee2e2; background: #fef2f2; color: #dc2626;">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </form>

                    <a href="{{ route('admin.training') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="training-meta-grid">
                <div><span class="meta-label">Trainer</span><span class="meta-value">{{ $program->provider }}</span></div>
                <div>
                    <span class="meta-label">Department</span>
                    <span class="meta-value">{{ $program->department->department_name ?? 'General' }}</span>
                </div>
                <div><span class="meta-label">Mode</span><span class="meta-value">{{ $program->mode }}</span></div>
                <div><span class="meta-label">Location</span><span class="meta-value">{{ $program->location }}</span></div>
                <div><span class="meta-label">Start Date</span><span class="meta-value">{{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}</span></div>
                <div><span class="meta-label">End Date</span><span class="meta-value">{{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}</span></div>
                <div>
                    <span class="meta-label">Duration</span>
                    <span class="meta-value">{{ \Carbon\Carbon::parse($program->start_date)->diffInDays(\Carbon\Carbon::parse($program->end_date)) + 1 }} days</span>
                </div>
            </div>

            <h3 class="training-section-title"><i class="fa-solid fa-book-open"></i> Training Description & Objectives</h3>
            <p>{{ $program->tr_description ?? 'No description provided.' }}</p>
        </div>

        {{-- PARTICIPANTS CARD --}}
        <div class="participants-card">
            <div class="participants-header">
                <h3 class="training-section-title" style="margin-top:0;"><i class="fa-solid fa-users"></i> Participants</h3>
                <div class="pill-group">
                    <span class="pill">Total: {{ $program->enrollments->count() }}</span>
                    <span class="pill pill-green">Completed: {{ $program->enrollments->where('completion_status', 'completed')->count() }}</span>
                </div>
            </div>

            <div class="open-positions">
                <table>
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($program->enrollments as $enrollment)
                        <tr>
                            {{-- NAME FIX: Uses user relationship --}}
                            <td>{{ $enrollment->employee->user->name ?? 'Unknown' }}</td>
                            <td>{{ $enrollment->employee->department->department_name ?? '-' }}</td>
                            <td>{{ $enrollment->employee->position->job_title ?? '-' }}</td>
                            <td>
                                @if($enrollment->completion_status == 'completed')
                                    <span style="color: #166534; font-weight: 600; background: #dcfce7; padding: 2px 8px; border-radius: 4px; font-size: 12px;">Completed</span>
                                @elseif($enrollment->completion_status == 'failed')
                                    <span style="color: #991b1b; font-weight: 600; background: #fee2e2; padding: 2px 8px; border-radius: 4px; font-size: 12px;">Failed</span>
                                @else
                                    <span style="color: #1e40af; font-weight: 600; background: #dbeafe; padding: 2px 8px; border-radius: 4px; font-size: 12px;">Enrolled</span>
                                @endif
                            </td>
                            <td>{{ $enrollment->remarks ?? '-' }}</td>
                            <td style="text-align: center;">
                                {{-- EDIT BUTTON --}}
                                <button onclick="openUpdateModal({{ $enrollment->enrollment_id }}, '{{ $enrollment->completion_status }}', '{{ $enrollment->remarks ?? '' }}')" 
                                        class="btn btn-secondary" 
                                        style="padding: 4px 10px; font-size: 12px; border: 1px solid #d1d5db; cursor: pointer;">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">No employees enrolled yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
</div>

{{-- MODAL 1: ENROLL EMPLOYEE --}}
<div id="enrollModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Enroll Employee</h3>
            <button onclick="closeEnrollModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form action="{{ route('admin.training.enroll', $program->training_id) }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: #374151;">Select Employee</label>
                <select name="employee_id" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit;">
                    <option value="" disabled selected>Choose an employee...</option>
                    {{-- DROPDOWN: Uses user name --}}
                    @if(isset($potentialTrainees))
                        @foreach($potentialTrainees as $trainee)
                            <option value="{{ $trainee->employee_id }}">
                                {{ $trainee->user->name ?? 'Unknown' }} - {{ $trainee->department->department_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
                <button type="button" onclick="closeEnrollModal()" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #d1d5db; background: white; cursor: pointer; font-weight: 500;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="border: none; padding: 8px 16px; border-radius: 6px; background-color: #3b82f6; color: white; cursor: pointer; font-weight: 500;">Confirm Enrollment</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: UPDATE STATUS --}}
<div id="updateModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Update Status</h3>
            <button onclick="closeUpdateModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form id="updateStatusForm" action="" method="POST">
            @csrf
            
            {{-- Status Dropdown --}}
            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: #374151;">Completion Status</label>
                <select id="modalStatus" name="completion_status" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit;">
                    <option value="enrolled">Enrolled (Ongoing)</option>
                    <option value="completed">Completed (Pass)</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            {{-- Remarks Input --}}
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; color: #374151;">Remarks (Optional)</label>
                <textarea id="modalRemarks" name="remarks" rows="3" placeholder="e.g., Attended all sessions, scored 85%" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit; box-sizing: border-box;"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
                <button type="button" onclick="closeUpdateModal()" style="padding: 8px 16px; border-radius: 6px; border: 1px solid #d1d5db; background: white; cursor: pointer; font-weight: 500;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="border: none; padding: 8px 16px; border-radius: 6px; background-color: #166534; color: white; cursor: pointer; font-weight: 500;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- JAVASCRIPT --}}
<script>
    // --- ENROLL MODAL FUNCTIONS ---
    function openEnrollModal() {
        document.getElementById('enrollModal').style.display = 'flex';
    }
    function closeEnrollModal() {
        document.getElementById('enrollModal').style.display = 'none';
    }

    // --- UPDATE STATUS MODAL FUNCTIONS ---
    function openUpdateModal(id, currentStatus, currentRemarks) {
        var modal = document.getElementById('updateModal');
        var form = document.getElementById('updateStatusForm');
        var statusSelect = document.getElementById('modalStatus');
        var remarksInput = document.getElementById('modalRemarks');

        // 1. Set the Form Action URL dynamically using the ID
        // Note: We use a placeholder ':id' logic or simple replacement
        var url = "{{ route('admin.training.updateStatus', ':id') }}";
        url = url.replace(':id', id);
        form.action = url;

        // 2. Pre-fill the current values
        statusSelect.value = currentStatus;
        // Handle remarks being null/empty strings correctly
        remarksInput.value = (currentRemarks === 'null' || !currentRemarks) ? '' : currentRemarks;

        // 3. Show Modal
        modal.style.display = 'flex';
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').style.display = 'none';
    }

    // Global: Close modals if clicking outside content box
    window.onclick = function(event) {
        var enrollModal = document.getElementById('enrollModal');
        var updateModal = document.getElementById('updateModal');
        if (event.target == enrollModal) {
            enrollModal.style.display = "none";
        }
        if (event.target == updateModal) {
            updateModal.style.display = "none";
        }
    }
</script>

</body>
</html>
