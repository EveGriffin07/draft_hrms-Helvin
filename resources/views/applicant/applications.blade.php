@extends('applicant.layout')

@section('content')

<style>
  /* Details row (expand) */
  .app-details-row { display: none; }
  .app-details-cell { padding: 14px 0; background: #f6f8fb; }

  .app-details-card{
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:16px 18px;
    box-shadow:0 6px 16px rgba(0,0,0,.06);
  }

  .app-details-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:12px;
  }

  .app-details-title{
    font-size:16px;
    font-weight:600;
    color:#0f172a;
    display:flex;
    align-items:center;
    gap:10px;
  }

  .app-close-btn{
    width:34px;
    height:34px;
    border-radius:10px;
    border:1px solid #e5e7eb;
    background:#fff;
    cursor:pointer;
    font-size:18px;
    line-height:1;
  }

  .app-details-grid{
    display:grid;
    grid-template-columns: 1.2fr 1fr;
    gap:16px;
  }

  @media (max-width: 900px){
    .app-details-grid{ grid-template-columns: 1fr; }
  }

  .app-section h4{
    font-size:13px;
    font-weight:600;
    color:#1e3a8a;
    margin:0 0 10px;
  }

  .app-kv{
    display:grid;
    grid-template-columns:160px 1fr;
    gap:10px;
    padding:8px 0;
    border-bottom:1px dashed #e5e7eb;
    font-size:13px;
  }
  .app-kv:last-child{ border-bottom:0; }
  .app-kv .k{ color:#64748b; font-weight:600; }
  .app-kv .v{ color:#0f172a; font-weight:600; }

  .app-file-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 12px;
    border-radius:10px;
    border:1px solid #e5e7eb;
    background:#0f172a;
    color:#fff;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    margin-top:10px;
  }
  .app-file-btn.disabled{
    opacity:.55;
    pointer-events:none;
    cursor:not-allowed;
    background:#334155;
  }

  /* Simple timeline */
  .timeline{ display:flex; flex-direction:column; gap:10px; }
  .step{ display:flex; gap:10px; align-items:flex-start; }
  .dot{
    width:10px; height:10px; border-radius:999px;
    background:#cbd5e1; margin-top:5px;
  }
  .step.done .dot{ background:#16a34a; }
  .step.active .dot{ background:#2563eb; }
  .step .txt{ font-size:13px; }
  .step .txt .t{ font-weight:600; color:#0f172a; }
  .step .txt .d{ color:#64748b; font-size:12px; margin-top:2px; }
</style>

<div class="applications-header">
  <h2 class="page-title">My Applications</h2>
  <p class="page-subtitle">View all your submitted job applications and track their progress.</p>
</div>

<div class="applications-container">

  <table class="applications-table">
    <thead>
      <tr>
        <th>Position</th>
        <th>Department</th>
        <th>Date Applied</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
            @forelse($applications as $app)
            <tr>
              <td>
                <div style="font-weight: 600; color: #1e293b;">
                    {{ $app->job->job_title ?? 'Job Removed' }}
                </div>
              </td>
              <td>{{ $app->job->department ?? '-' }}</td>
              <td>{{ $app->created_at->format('d M Y') }}</td>
              <td>
                @php
                    $statusClass = match($app->app_stage) {
                        'Hired' => 'badge-success',
                        'Rejected' => 'badge-critical',
                        'Interview' => 'badge-warning', // You might need to add CSS for this
                        default => 'badge-normal'
                    };
                @endphp
                <span class="badge {{ $statusClass }}">{{ $app->app_stage }}</span>
              </td>
              <td>
                {{-- View Details Button (We can make this modal dynamic later) --}}
                <a href="#" class="btn-view" onclick="alert('Details view coming soon!')">
                    View
                </a>
              </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; color: #64748b;">
                    <i class="fa-solid fa-folder-open" style="font-size: 24px; margin-bottom: 10px; opacity: 0.5;"></i>
                    <p>You haven't applied for any jobs yet.</p>
                    <a href="{{ route('applicant.jobs') }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                        Browse Jobs
                    </a>
                </td>
            </tr>
            @endforelse
          </tbody>
  </table>

</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const detailRows = () => document.querySelectorAll('.app-details-row');

    document.querySelectorAll('.js-view-application').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();

        const targetId = btn.getAttribute('data-target');
        const row = document.getElementById(targetId);
        if (!row) return;

        // close other opened details
        detailRows().forEach(r => {
          if (r !== row) r.style.display = 'none';
        });

        const isOpen = row.style.display === 'table-row';
        row.style.display = isOpen ? 'none' : 'table-row';
        btn.setAttribute('aria-expanded', String(!isOpen));

        if (!isOpen) row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      });
    });

    document.querySelectorAll('[data-close-details]').forEach(closeBtn => {
      closeBtn.addEventListener('click', () => {
        const row = closeBtn.closest('tr.app-details-row');
        if (row) row.style.display = 'none';
      });
    });
  });
</script>

@endsection
