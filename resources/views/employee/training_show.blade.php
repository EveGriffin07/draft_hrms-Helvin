<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $enrollment->training->training_name }} - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/hrms-theme.css') }}">
  <style>
    body { background:#f3f6fb; }
    .dashboard-shell { display:flex; min-height:calc(100vh - 64px); }
    .dashboard-main { flex:1; padding:28px 32px; max-width:100%; margin:0 auto; }
    
    .detail-card { background: white; border-radius: 16px; border: 1px solid #e5e7eb; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .t-title { font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
    
    /* Status Badges */
    .t-status { padding: 6px 14px; border-radius: 99px; font-size: 13px; font-weight: 600; }
    .status-enrolled { background: #dbeafe; color: #1e40af; }
    .status-completed { background: #dcfce7; color: #166534; }
    .status-failed { background: #fee2e2; color: #991b1b; }
    
    /* Information Grid */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #f1f5f9; }
    .info-item { background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #f1f5f9; }
    .info-label { font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 6px; }
    .info-value { font-size: 15px; color: #0f172a; font-weight: 500; word-break: break-word; }
    
    .desc-box { margin-top: 30px; }
    .desc-heading { font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .desc-content { color: #475569; line-height: 1.7; font-size: 14px; background: white; }

    /* Action Buttons */
    .action-section { margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    .action-btn { 
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 12px 24px; color: white; border-radius: 8px; 
        font-weight: 600; text-decoration: none; transition: 0.2s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .action-btn:hover { opacity: 0.9; transform: translateY(-1px); }
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
      
      {{-- BACK BUTTON --}}
      <div style="margin-bottom: 20px;">
        <a href="{{ route('employee.training.index') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
            <div style="width: 24px; height: 24px; background: white; border-radius: 50%; display: grid; place-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-arrow-left"></i>
            </div>
            Back to Training List
        </a>
      </div>

      <div class="detail-card">
        {{-- HEADER --}}
        <div class="header-row">
            <div>
                <h1 class="t-title">{{ $enrollment->training->training_name }}</h1>
                <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    {{-- Status Badge --}}
                    @if($enrollment->completion_status == 'completed')
                        <span class="t-status status-completed"><i class="fa-solid fa-circle-check"></i> Completed</span>
                    @elseif($enrollment->completion_status == 'failed')
                        <span class="t-status status-failed"><i class="fa-solid fa-circle-xmark"></i> Failed</span>
                    @else
                        <span class="t-status status-enrolled"><i class="fa-solid fa-spinner"></i> Ongoing (Enrolled)</span>
                    @endif

                    <span style="color: #94a3b8;">|</span>

                    {{-- Date Range --}}
                    <span style="color: #64748b; font-size: 14px; font-weight: 500;">
                        <i class="fa-regular fa-calendar"></i> &nbsp;
                        {{ \Carbon\Carbon::parse($enrollment->training->start_date)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($enrollment->training->end_date)->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- DESCRIPTION --}}
        <div class="desc-box">
            <h4 class="desc-heading"><i class="fa-solid fa-align-left"></i> About this Course</h4>
            <div class="desc-content">
                {{ $enrollment->training->tr_description ?? 'No description provided.' }}
            </div>
        </div>

        {{-- INFO GRID --}}
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Trainer / Provider</div>
                <div class="info-value"><i class="fa-solid fa-user-tie" style="color:#64748b; margin-right:6px;"></i> {{ $enrollment->training->provider }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Training Mode</div>
                <div class="info-value">
                    @if($enrollment->training->mode == 'Online')
                        <i class="fa-solid fa-laptop" style="color:#0ea5e9; margin-right:6px;"></i> Online
                    @else
                        <i class="fa-solid fa-building" style="color:#16a34a; margin-right:6px;"></i> Onsite
                    @endif
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Location / Platform</div>
                {{-- Just displays whatever text the admin entered in 'location' --}}
                <div class="info-value">
                    <i class="fa-solid fa-location-dot" style="color:#ef4444; margin-right:6px;"></i> 
                    {{ $enrollment->training->location }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Your Result / Remarks</div>
                <div class="info-value">
                    {{ $enrollment->remarks ?? 'No remarks yet.' }}
                </div>
            </div>
        </div>

        {{-- SMART ACTION BUTTON --}}
        <div class="action-section">
            
            {{-- CHECK: Is the location a URL? (Starts with http or https) --}}
            @if(Str::startsWith($enrollment->training->location, ['http://', 'https://']))
                
                {{-- CASE 1: IT IS A LINK (Online) --}}
                <a href="{{ $enrollment->training->location }}" target="_blank" class="action-btn" style="background-color: #2563eb;">
                    <i class="fa-solid fa-video"></i> &nbsp; Join Meeting
                </a>
                <p style="margin-top: 10px; font-size: 13px; color: #64748b;">
                    <i class="fa-solid fa-circle-info"></i> Click the button above to join the online session.
                </p>

            @else
                
                {{-- CASE 2: IT IS A PLACE (Onsite) --}}
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($enrollment->training->location) }}" target="_blank" class="action-btn" style="background-color: #0f172a;">
                    <i class="fa-solid fa-map-location-dot"></i> &nbsp; View Map Location
                </a>
                <p style="margin-top: 10px; font-size: 13px; color: #64748b;">
                    <i class="fa-solid fa-location-arrow"></i> This training is held in-person at <strong>{{ $enrollment->training->location }}</strong>.
                </p>

            @endif
        </div>

      </div>

      <footer style="text-align: center; margin-top: 40px; color: #94a3b8; font-size: 12px;">
        © 2026 Web-Based HRMS. All Rights Reserved.
      </footer>
    </main>
  </div>

</body>
</html>