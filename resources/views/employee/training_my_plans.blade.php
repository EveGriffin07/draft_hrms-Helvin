<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Training - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/hrms-theme.css') }}">
  <style>
    body { background:#f3f6fb; }
    .dashboard-shell { display:flex; min-height:calc(100vh - 64px); }
    .dashboard-main { flex:1; padding:28px 32px; max-width:100%; margin:0 auto; }
    
    .page-header { margin-bottom: 24px; }
    .page-title { font-size: 20px; font-weight: 700; color: #0f172a; }
    .page-subtitle { color: #6b7280; font-size: 13px; margin-top: 4px; }

    /* CARDS FOR UPCOMING TRAINING */
    .training-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 40px; }
    .training-card { 
        background: white; border-radius: 12px; border: 1px solid #e5e7eb; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); overflow: hidden; transition: transform 0.2s; display: flex; flex-direction: column;
    }
    .training-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px rgba(0,0,0,0.05); }
    
    .t-header { padding: 16px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: start; }
    .t-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 99px; text-transform: uppercase; }
    .badge-online { background: #e0f2fe; color: #0369a1; }
    .badge-onsite { background: #f0fdf4; color: #15803d; }

    .t-body { padding: 20px; flex: 1; }
    .t-title { font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px; display: block; }
    .t-info { font-size: 13px; color: #64748b; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
    .t-desc { font-size: 13px; color: #94a3b8; margin-top: 12px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    .t-footer { padding: 12px 20px; border-top: 1px solid #f1f5f9; background: white; text-align: right; }
    .btn-view { font-size: 13px; font-weight: 600; color: #2563eb; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    .btn-view:hover { text-decoration: underline; }

    /* TABLE FOR HISTORY */
    .history-section { background: white; border-radius: 14px; border: 1px solid #e5e7eb; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    .section-title { font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 16px; }
    
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { text-align: left; padding: 12px; font-size: 12px; text-transform: uppercase; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 14px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
    .status-pass { color: #166534; font-weight: 600; }
    .status-fail { color: #991b1b; font-weight: 600; }
    
    .empty-state { text-align: center; padding: 40px; color: #94a3b8; font-size: 14px; }
  </style>
</head>
<body>

  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
        <i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name }}
    </div>
  </header>

  <div class="container dashboard-shell">
    @include('employee.layout.sidebar')

    <main class="dashboard-main">
      
      <div class="page-header">
        <h1 class="page-title">My Training Plans</h1>
        <p class="page-subtitle">View your upcoming courses and training history.</p>
      </div>

      {{-- SECTION 1: UPCOMING / ACTIVE --}}
      <h3 class="section-title"><i class="fa-solid fa-chalkboard-user"></i> &nbsp;Current & Upcoming Training</h3>
      
      @if($upcoming->count() > 0)
        <div class="training-grid">
            @foreach($upcoming as $enrollment)
            <div class="training-card">
                <div class="t-header">
                    <span class="t-badge {{ $enrollment->training->mode == 'Online' ? 'badge-online' : 'badge-onsite' }}">
                        {{ $enrollment->training->mode }}
                    </span>
                    <span style="font-size:12px; color:#64748b; font-weight:500;">
                        {{ \Carbon\Carbon::parse($enrollment->training->start_date)->format('d M') }} - 
                        {{ \Carbon\Carbon::parse($enrollment->training->end_date)->format('d M') }}
                    </span>
                </div>
                <div class="t-body">
                    <span class="t-title">{{ $enrollment->training->training_name }}</span>
                    <div class="t-info"><i class="fa-solid fa-user-tie"></i> {{ $enrollment->training->provider }}</div>
                    <div class="t-info"><i class="fa-solid fa-location-dot"></i> {{ $enrollment->training->location }}</div>
                    <p class="t-desc">{{ $enrollment->training->tr_description }}</p>
                </div>
                
                {{-- NEW: FOOTER WITH VIEW BUTTON --}}
                <div class="t-footer">
                    <a href="{{ route('employee.training.show', $enrollment->training->training_id) }}" class="btn-view">
                        View Details <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
      @else
        <div class="empty-state" style="margin-bottom: 40px; background: white; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <i class="fa-regular fa-calendar-xmark" style="font-size: 24px; margin-bottom: 10px;"></i><br>
            You have no upcoming training sessions assigned.
        </div>
      @endif


      {{-- SECTION 2: HISTORY --}}
      <div class="history-section">
        <h3 class="section-title"><i class="fa-solid fa-clock-rotate-left"></i> &nbsp;Training History</h3>
        
        @if($history->count() > 0)
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Training Title</th>
                    <th>Date Completed</th>
                    <th>Trainer</th>
                    <th>Result</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $record)
                <tr>
                    <td style="font-weight: 500;">{{ $record->training->training_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->training->end_date)->format('d M Y') }}</td>
                    <td>{{ $record->training->provider }}</td>
                    <td>
                        @if($record->completion_status == 'completed')
                            <span class="status-pass"><i class="fa-solid fa-circle-check"></i> Passed</span>
                        @else
                            <span class="status-fail"><i class="fa-solid fa-circle-xmark"></i> Failed</span>
                        @endif
                    </td>
                    <td>{{ $record->remarks ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">No training history available.</div>
        @endif
      </div>

      <footer>&copy; 2026 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
  </div>

</body>
</html>