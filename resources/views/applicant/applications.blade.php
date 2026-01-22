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

      {{-- Row 1 --}}
      <tr>
        <td>Software Engineer</td>
        <td>IT Department</td>
        <td>12 Feb 2025</td>
        <td><span class="badge submitted">Submitted</span></td>
        <td>
          <a href="#" class="btn-view js-view-application" data-target="app-details-1" aria-expanded="false">View</a>
        </td>
      </tr>

      {{-- Details Row 1 --}}
      <tr id="app-details-1" class="app-details-row">
        <td colspan="5" class="app-details-cell">
          <div class="app-details-card">
            <div class="app-details-head">
              <div class="app-details-title">
                Application Details
                <span class="badge submitted">Submitted</span>
              </div>
              <button type="button" class="app-close-btn" data-close-details>&times;</button>
            </div>

            <div class="app-details-grid">
              <div class="app-section">
                <h4>Submitted Information</h4>
                <div class="app-kv"><div class="k">Full Name</div><div class="v">John Lim</div></div>
                <div class="app-kv"><div class="k">Email</div><div class="v">johnlim@email.com</div></div>
                <div class="app-kv"><div class="k">Phone</div><div class="v">012-3456789</div></div>
                <div class="app-kv"><div class="k">Cover Letter</div><div class="v">I am interested in this role and would like to contribute to your team.</div></div>

                <a href="javascript:void(0)" class="app-file-btn disabled">
                  <i class="fa-solid fa-file-pdf"></i> View Resume (PDF)
                </a>
              </div>

              <div class="app-section">
                <h4>Status Tracking</h4>
                <div class="timeline">
                  <div class="step active">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Submitted</div>
                      <div class="d">Your application has been received.</div>
                    </div>
                  </div>
                  <div class="step">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Under Review</div>
                      <div class="d">HR is reviewing your application.</div>
                    </div>
                  </div>
                  <div class="step">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Decision</div>
                      <div class="d">Shortlisted / Rejected / Hired.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </td>
      </tr>


      {{-- Row 2 --}}
      <tr>
        <td>HR Executive</td>
        <td>Human Resource</td>
        <td>28 Jan 2025</td>
        <td><span class="badge reviewing">Under Review</span></td>
        <td>
          <a href="#" class="btn-view js-view-application" data-target="app-details-2" aria-expanded="false">View</a>
        </td>
      </tr>

      {{-- Details Row 2 --}}
      <tr id="app-details-2" class="app-details-row">
        <td colspan="5" class="app-details-cell">
          <div class="app-details-card">
            <div class="app-details-head">
              <div class="app-details-title">
                Application Details
                <span class="badge reviewing">Under Review</span>
              </div>
              <button type="button" class="app-close-btn" data-close-details>&times;</button>
            </div>

            <div class="app-details-grid">
              <div class="app-section">
                <h4>Submitted Information</h4>
                <div class="app-kv"><div class="k">Full Name</div><div class="v">Nur Aisyah</div></div>
                <div class="app-kv"><div class="k">Email</div><div class="v">aisyah@email.com</div></div>
                <div class="app-kv"><div class="k">Phone</div><div class="v">013-1112233</div></div>
                <div class="app-kv"><div class="k">Cover Letter</div><div class="v">I am excited to join HR and support recruitment activities.</div></div>

                <a href="javascript:void(0)" class="app-file-btn disabled">
                  <i class="fa-solid fa-file-pdf"></i> View Resume (PDF)
                </a>
              </div>

              <div class="app-section">
                <h4>Status Tracking</h4>
                <div class="timeline">
                  <div class="step done">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Submitted</div>
                      <div class="d">Application received.</div>
                    </div>
                  </div>
                  <div class="step active">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Under Review</div>
                      <div class="d">Currently being reviewed.</div>
                    </div>
                  </div>
                  <div class="step">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Decision</div>
                      <div class="d">Shortlisted / Rejected / Hired.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </td>
      </tr>


      {{-- Row 3 --}}
      <tr>
        <td>Marketing Assistant</td>
        <td>Marketing</td>
        <td>10 Jan 2025</td>
        <td><span class="badge rejected">Rejected</span></td>
        <td>
          <a href="#" class="btn-view js-view-application" data-target="app-details-3" aria-expanded="false">View</a>
        </td>
      </tr>

      {{-- Details Row 3 --}}
      <tr id="app-details-3" class="app-details-row">
        <td colspan="5" class="app-details-cell">
          <div class="app-details-card">
            <div class="app-details-head">
              <div class="app-details-title">
                Application Details
                <span class="badge rejected">Rejected</span>
              </div>
              <button type="button" class="app-close-btn" data-close-details>&times;</button>
            </div>

            <div class="app-details-grid">
              <div class="app-section">
                <h4>Submitted Information</h4>
                <div class="app-kv"><div class="k">Full Name</div><div class="v">Adam Lee</div></div>
                <div class="app-kv"><div class="k">Email</div><div class="v">adamlee@email.com</div></div>
                <div class="app-kv"><div class="k">Phone</div><div class="v">014-9988776</div></div>
                <div class="app-kv"><div class="k">Cover Letter</div><div class="v">I am passionate about marketing and would like to join your team.</div></div>

                <a href="javascript:void(0)" class="app-file-btn disabled">
                  <i class="fa-solid fa-file-pdf"></i> View Resume (PDF)
                </a>
              </div>

              <div class="app-section">
                <h4>Status Tracking</h4>
                <div class="timeline">
                  <div class="step done">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Submitted</div>
                      <div class="d">Application received.</div>
                    </div>
                  </div>
                  <div class="step done">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Under Review</div>
                      <div class="d">Reviewed by HR.</div>
                    </div>
                  </div>
                  <div class="step active">
                    <div class="dot"></div>
                    <div class="txt">
                      <div class="t">Rejected</div>
                      <div class="d">This application was not selected.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </td>
      </tr>

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
