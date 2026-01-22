<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Details - HRMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

    <style>
        .page-header {
            margin-bottom: 18px;
        }
        .page-header h2 {
            font-size: 22px;
            margin-bottom: 4px;
        }
        .page-header .subtitle {
            font-size: 13px;
            color: #6b7280;
        }

        .panel {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 18px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }
        .panel-header h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        .module-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .module-list li {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
            border-bottom: 1px dashed #e5e7eb;
        }
        .module-list li:last-child {
            border-bottom: none;
        }
        .module-list span {
            color: #6b7280;
        }

        .evaluation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin-top: 12px;
        }
        .evaluation-group label {
            display: block;
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .evaluation-group input,
        .evaluation-group select,
        .evaluation-group textarea {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            font-size: 13px;
        }
        .evaluation-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .evaluation-actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-save {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Resume actions */
.resume-actions{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  justify-content:flex-end;
  align-items:center;
}

.btn-view-pdf{
  background:#111827;
  color:#fff;
  border:none;
  border-radius:8px;
  padding:8px 14px;
  font-size:13px;
  cursor:pointer;
  display:inline-flex;
  align-items:center;
  gap:6px;
  text-decoration:none;
}

.btn-view-pdf:hover{
  opacity:.92;
}

.btn-download-pdf{
  background:#2563eb;
  color:#fff;
  border:none;
  border-radius:8px;
  padding:8px 14px;
  font-size:13px;
  cursor:pointer;
  display:inline-flex;
  align-items:center;
  gap:6px;
  text-decoration:none;
}
.btn-download-pdf:hover{
  opacity:.92;
}

    </style>
</head>
<body>

<header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info"><i class="fa-regular fa-bell"></i> &nbsp; HR Admin</div>
</header>

<div class="container">

    @include('partials.sidebar')

    <main>

        <div class="breadcrumb">Home > Recruitment > View Applicants > Applicant Details</div>

        <div class="page-header">
            <h2>Applicant Details</h2>
            <p class="subtitle">Review applicant information and record evaluation scores for recruitment decisions.</p>
        </div>

        {{-- Applicant info --}}
        <div class="panel">
            <div class="panel-header">
                <h3>Application Information</h3>
            </div>
            <ul class="module-list">
                {{-- Dummy data for UI; replace with variables later --}}
                <li><span>Name</span><strong>Nur Aisyah</strong></li>
                <li><span>Position Applied</span><strong>Software Engineer</strong></li>
                <li><span>Department</span><strong>IT Department</strong></li>
                <li><span>Email</span><strong>aisyah@example.com</strong></li>
                <li><span>Phone</span><strong>012-3456789</strong></li>
                <li>
  <span>Resume (PDF)</span>
  <div class="resume-actions">
    <!-- UI dummy link (replace later with real file URL) -->
    <a href="{{ asset('dummy/resume-sample.pdf') }}" target="_blank" class="btn-view-pdf">
      <i class="fa-solid fa-file-pdf"></i> View PDF
    </a>

    <a href="{{ asset('dummy/resume-sample.pdf') }}" download class="btn-download-pdf">
      <i class="fa-solid fa-download"></i> Download
    </a>
  </div>
</li>

                <li><span>Current Stage</span><strong>Shortlisted</strong></li>
            </ul>
        </div>

        {{-- Evaluation / scores UI only --}}
        <div class="panel">
            <div class="panel-header">
                <h3>Evaluation &amp; Application Stage</h3>
            </div>

            <form onsubmit="event.preventDefault();">
                <div class="evaluation-grid">
                    <div class="evaluation-group">
                        <label>Application Stage</label>
                        <select name="status">
                            <option>Applied</option>
                            <option selected>Shortlisted</option>
                            <option>Interviewed</option>
                            <option>Hired</option>
                            <option>Rejected</option>
                        </select>
                    </div>

                    <div class="evaluation-group">
                        <label>Test Score (0-100)</label>
                        <input type="number" name="test_score" placeholder="e.g. 80" min="0" max="100">
                    </div>

                    <div class="evaluation-group">
                        <label>Interview Score (0-100)</label>
                        <input type="number" name="interview_score" placeholder="e.g. 85" min="0" max="100">
                    </div>

                    <div class="evaluation-group">
                        <label>Overall Score</label>
                        <input type="number" name="overall_score" placeholder="Auto-calculated later" disabled>
                    </div>

                    <div class="evaluation-group" style="grid-column: 1 / -1;">
                        <label>Evaluation Notes</label>
                        <textarea name="notes" placeholder="Comments about the candidate's strengths, weaknesses or suitability."></textarea>
                    </div>
                </div>

                <div class="evaluation-actions">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Evaluation
                    </button>
                </div>
            </form>
        </div>

        <footer>&copy; 2025 Web-Based HRMS. All Rights Reserved.</footer>

    </main>
</div>

</body>
</html>
