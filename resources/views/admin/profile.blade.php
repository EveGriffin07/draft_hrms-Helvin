<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - HRMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
  <style>
    body { background:#f5f7fb; }
    .container { width:100%; max-width:none; }
    main { padding:28px 32px; width:100%; max-width:none; }
    .card {
      background:#fff;
      border:1px solid #e5e7eb;
      border-radius:14px;
      box-shadow:0 14px 30px rgba(15,23,42,0.08);
      padding:22px;
      margin-bottom:16px;
    }
    h2 { margin-bottom:4px; }
    .subtitle { color:#6b7280; margin-bottom:12px; }
    .profile-grid {
      display:grid;
      grid-template-columns:280px 1fr;
      gap:22px;
      align-items:start;
    }
    @media (max-width:1024px) { .profile-grid { grid-template-columns:1fr; } }
    .avatar {
      display:grid;
      place-items:center;
      border:2px dashed #d1d5db;
      border-radius:14px;
      padding:28px 18px;
      background:#f8fafc;
      text-align:center;
    }
    .avatar .circle {
      width:120px; height:120px;
      border-radius:50%;
      background:#e2e8f0;
      display:grid;
      place-items:center;
      color:#94a3b8;
      font-size:40px;
      margin-bottom:12px;
    }
    .btn-upload {
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 14px;
      background:#2563eb;
      color:#fff;
      border:none;
      border-radius:10px;
      font-weight:700;
      cursor:pointer;
      box-shadow:0 10px 24px rgba(37,99,235,0.25);
    }
    .muted { color:#6b7280; font-size:13px; }
    .form-grid {
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
      gap:14px 16px;
      margin-top:6px;
    }
    label { display:block; font-weight:600; margin-bottom:6px; color:#0f172a; }
    input, select, textarea {
      width:100%;
      padding:12px 14px;
      border:1px solid #d1d5db;
      border-radius:12px;
      background:#f8fafc;
      font-size:14px;
    }
    .two-col { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:12px; }
    .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:12px; }
    .btn-secondary { background:#f1f5f9; color:#0f172a; border:1px solid #e5e7eb; padding:10px 14px; border-radius:10px; font-weight:700; cursor:pointer; }
    .btn-primary { background:#2563eb; color:#fff; border:none; padding:10px 14px; border-radius:10px; font-weight:700; cursor:pointer; box-shadow:0 10px 24px rgba(37,99,235,0.25); }
  </style>
</head>
<body>
  <header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info"><i class="fa-regular fa-user"></i> &nbsp; HR Admin</div>
  </header>

  <div class="container">
    @include('partials.sidebar')

    <main>
      <div class="breadcrumb">Home > Employee Management > Profile</div>
      <h2>My Profile</h2>
      <p class="subtitle">You can edit your personal contact details. Job data is read-only.</p>

      <div class="card profile-grid">
        <div class="avatar">
          <div class="circle"><i class="fa-regular fa-user"></i></div>
          <button class="btn-upload"><i class="fa-solid fa-camera"></i> Change Photo</button>
          <div class="muted" style="margin-top:6px;">JPG or PNG · Max 2MB</div>
        </div>

        <div>
          <h4 style="margin:0 0 8px;">Personal Information</h4>
          <div class="form-grid">
            <div><label>Full Name</label><input type="text" value="Jane Employee"></div>
            <div><label>Email Address</label><input type="email" value="jane.employee@example.com"></div>
            <div><label>Phone Number</label><input type="text" value="+60 12-345 6789"></div>
            <div><label>Address</label><input type="text" value="21, Jalan Ampang"></div>
            <div><label>City</label><input type="text" value="Kuala Lumpur"></div>
            <div><label>State</label><input type="text" value="WP Kuala Lumpur"></div>
            <div><label>Postal Code</label><input type="text" value="50450"></div>
            <div><label>Department</label><input type="text" value="Product" readonly></div>
            <div><label>Job Title</label><input type="text" value="Product Specialist" readonly></div>
            <div><label>Employee ID</label><input type="text" value="EMP-1045" readonly></div>
          </div>
        </div>
      </div>

      <div class="card">
        <h4 style="margin:0 0 8px;">Account Security</h4>
        <div class="two-col">
          <div><label>Username</label><input type="text" value="jane_emp" readonly></div>
          <div>
            <label>Password</label>
            <input type="password" value="********" readonly>
          </div>
          <div>
            <label>Two-Factor</label>
            <select disabled>
              <option>Email</option>
              <option>SMS</option>
              <option>Authenticator App</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card">
        <h4 style="margin:0 0 8px;">Emergency & Notes</h4>
        <div class="two-col">
          <div><label>Emergency Contact</label><input type="text" value="Sam Employee" readonly></div>
          <div><label>Relationship</label><input type="text" value="Spouse" readonly></div>
          <div><label>Contact Number</label><input type="text" value="+60 17-555 8899" readonly></div>
        </div>
        <div style="margin-top:10px;">
          <label>Notes</label>
          <textarea rows="3" placeholder="Add notes" readonly>Prefers morning meetings; remote on Fridays.</textarea>
        </div>
        <div class="actions">
          <button class="btn-secondary" type="reset"><i class="fa-solid fa-rotate-left"></i> Reset</button>
          <button class="btn-primary" type="button"><i class="fa-regular fa-floppy-disk"></i> Save Changes</button>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
