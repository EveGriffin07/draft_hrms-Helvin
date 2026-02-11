<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Overtime Requests</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    main { padding:2rem; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px; box-shadow:0 8px 18px rgba(15,23,42,0.08); margin-bottom:16px; }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:12px; }
    label { font-weight:600; color:#0f172a; font-size:0.95rem; }
    input, select, textarea { width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; font-size:0.95rem; }
    textarea { min-height:90px; resize:vertical; }
    .table-wrap { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th, td { padding:10px 12px; border-bottom:1px solid #e5e7eb; text-align:left; }
    thead { background:#0f172a; color:#38bdf8; }
    .status { padding:4px 10px; border-radius:999px; font-size:0.85rem; font-weight:700; display:inline-block; }
    .pending { background:#fef9c3; color:#854d0e; }
    .approved { background:#dcfce7; color:#166534; }
    .rejected { background:#fee2e2; color:#991b1b; }
    .actions { display:flex; gap:6px; }
  </style>
</head>
<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
      <span><i class="fa-regular fa-bell"></i> &nbsp; {{ Auth::user()->name ?? 'Employee' }}</span>
    </div>
  </header>
  <div class="container">
    @include('employee.layout.sidebar')
    <main>
      <div class="breadcrumb">Attendance · Overtime</div>
      <h2 style="margin:0 0 .3rem 0; color:#0ea5e9;">Overtime Requests</h2>
      <p class="subtitle">Submit overtime for approval and track its status.</p>

      @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="notice error">{{ $errors->first() }}</div>
      @endif

      <div class="card">
        <h3 style="margin-top:0;">New Overtime</h3>
        <form method="POST" action="{{ route('employee.attendance.overtime.store') }}">
          @csrf
          <div class="grid">
            <div>
              <label for="date">Date</label>
              <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
            </div>
            <div>
              <label for="hours">Hours</label>
              <input type="number" step="0.25" min="0.25" max="24" id="hours" name="hours" value="{{ old('hours', '1.00') }}" required>
            </div>
            <div>
              <label for="rate_type">Rate (multiplier)</label>
              <select id="rate_type" name="rate_type">
                @php($rateOld = old('rate_type', '1.5'))
                <option value="1.25" {{ $rateOld=='1.25' ? 'selected' : '' }}>1.25x</option>
                <option value="1.5" {{ $rateOld=='1.5' ? 'selected' : '' }}>1.5x (default)</option>
                <option value="2" {{ $rateOld=='2' ? 'selected' : '' }}>2.0x</option>
              </select>
            </div>
          </div>
          <div style="margin-top:12px;">
            <label for="reason">Reason / Work done (optional)</label>
            <textarea id="reason" name="reason" placeholder="E.g., Release deployment, client call">{{ old('reason') }}</textarea>
          </div>
          <div style="margin-top:14px; display:flex; gap:10px; align-items:center;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit Overtime</button>
            <span style="color:#64748b; font-size:0.9rem;">Pending until manager/HR approves. Linked to the payroll period for the selected date.</span>
          </div>
        </form>
      </div>

      <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h3 style="margin:0;">My Overtime History</h3>
          <span style="color:#64748b; font-size:0.9rem;">Most recent first</span>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Hours</th>
                <th>Rate</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($records as $record)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $record->date?->format('Y-m-d') }}</td>
                  <td>{{ number_format($record->hours, 2) }}</td>
                  <td>{{ rtrim(rtrim($record->rate_type, '0'), '.') }}x</td>
                  <td><span class="status {{ $record->ot_status }}">{{ ucfirst($record->ot_status) }}</span></td>
                  <td>{{ $record->reason ?? '—' }}</td>
                  <td>
                    @if($record->ot_status === 'pending')
                      <form method="POST" action="{{ route('employee.attendance.overtime.destroy', $record) }}" onsubmit="return confirm('Delete this pending request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-small">Delete</button>
                      </form>
                    @else
                      <span style="color:#94a3b8;">—</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" style="text-align:center; color:#94a3b8;">No overtime submitted yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
