<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training & Learning - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
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
      <div class="breadcrumb">Home > Training > Overview</div>
      <h2>Training & Learning</h2>
      <p class="subtitle">View and manage employee training programs and learning progress.</p>

      @if(session('success'))
        <div style="background-color: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
      @endif

      <div class="summary">
        <div class="card"><h3>Total Programs</h3><p>{{ $total }}</p></div>
        <div class="card"><h3>Active</h3><p>{{ $ongoing }}</p></div>
        <div class="card"><h3>Completed</h3><p>{{ $completed }}</p></div>
        <div class="card"><h3>Upcoming</h3><p>{{ $upcoming }}</p></div>
      </div>

      <div class="calendar-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0;">Training Schedule</h3>
        </div>
        
        <div id="calendar"></div>
      </div>

      <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

      <style>
        /* 1. Container Card Style */
        #calendar {
            background: #ffffff;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05); /* Soft shadow */
            border: 1px solid #f3f4f6;
            font-family: 'Poppins', sans-serif;
        }

        /* 2. Remove default ugly borders */
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #f3f4f6; /* Very light gray borders */
        }

        /* 3. Header Title (e.g., "October 2025") */
        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 600;
            color: #1f2937;
        }

        /* 4. Buttons (Prev, Next, Today) */
        .fc-button {
            background-color: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
            text-transform: capitalize;
            transition: all 0.2s;
        }
        .fc-button:hover {
            background-color: #f9fafb !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
        }
        .fc-button-active {
            background-color: #eff6ff !important; /* Light blue when active */
            border-color: #bfdbfe !important;
            color: #1d4ed8 !important;
        }
        .fc-button:focus {
            box-shadow: none !important; /* Remove blue glow */
        }

        /* 5. Weekday Headers (Mon, Tue...) */
        .fc-col-header-cell-cushion {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af; /* Soft gray */
            text-decoration: none !important;
        }

        /* 6. The Day Cells */
        .fc-daygrid-day-top {
            justify-content: center; /* Center the date number */
            padding-top: 8px;
        }
        .fc-daygrid-day-number {
            font-size: 0.9rem;
            color: #4b5563;
            text-decoration: none !important;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        /* Highlight Today */
        .fc-day-today {
            background-color: #fafafa !important;
        }
        .fc-day-today .fc-daygrid-day-number {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
        }

        /* 7. Events (The training bars) */
        .fc-event {
            border: none !important;
            border-radius: 6px !important;
            padding: 4px 8px !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            margin-bottom: 4px !important;
            cursor: pointer;
            transition: transform 0.1s;
        }
        .fc-event:hover {
            transform: scale(1.02); /* Slight zoom on hover */
            filter: brightness(0.95);
        }
      </style>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var calendarEl = document.getElementById('calendar');

          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'standard',
            headerToolbar: {
              left: 'prev,next today',
              center: 'title',
              right: 'dayGridMonth,listWeek'
            },
            height: 'auto', // Adjusts height automatically
            contentHeight: 600,
            
            // Events Source
            events: "{{ route('admin.training.events') }}",

            // Event Click Behavior
            eventClick: function(info) {
              if (info.event.url) {
                window.location.href = info.event.url;
                info.jsEvent.preventDefault();
              }
            },

            // Make events look like "pills"
            eventDisplay: 'block', 
            displayEventTime: false // Hide time for cleaner look since it's date-based
          });

          calendar.render();
        });
      </script>
      
      <div class="training-list">
        <h3>All Training Programs</h3>
        <table>
            <thead>
            <tr>
                <th>Program Title</th>
                <th>Trainer</th>
                <th>Department</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($programs as $program)
            <tr>
                <td>{{ $program->training_name }}</td>
                <td>{{ $program->provider }}</td>
                {{-- FIX: Using department_name --}}
                <td>{{ $program->department->department_name ?? 'General' }}</td>
                <td>{{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}</td>
                <td>
                    @if($program->tr_status == 'completed')
                        <span style="color: #166534; background: #dcfce7; padding: 4px 8px; border-radius: 99px; font-size: 12px; font-weight: 500;">Completed</span>
                    @elseif($program->tr_status == 'active')
                        <span style="color: #1e40af; background: #dbeafe; padding: 4px 8px; border-radius: 99px; font-size: 12px; font-weight: 500;">Ongoing</span>
                    @else
                        <span style="color: #9a3412; background: #ffedd5; padding: 4px 8px; border-radius: 99px; font-size: 12px; font-weight: 500;">Upcoming</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.training.show', $program->training_id) }}" class="btn btn-primary">
                        View Details
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #6b7280; padding: 20px;">No training programs found.</td>
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