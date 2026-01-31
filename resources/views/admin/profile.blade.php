<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Profile - HRMS</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
</head>

<body>

  <!-- Header -->
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

      <div class="breadcrumb">Home > My Profile</div>
      <h2>My Profile</h2>
      <p class="subtitle">View and update your personal information.</p>

      <div class="profile-container">

        <!-- LEFT: Avatar + Summary -->
        <div class="profile-sidebar">
          
          <div class="avatar-wrapper">
            <img src="https://via.placeholder.com/150" class="avatar-preview">
          </div>

          <button class="btn-upload">
            <i class="fa-solid fa-image"></i> Change Photo
          </button>

          <p class="avatar-note">JPG or PNG • Max 2MB</p>

          <h3 class="profile-name">HR Admin</h3>
          <p class="profile-role">Human Resources Department</p>

          <div class="profile-stats">
              <div class="stat">
                  <span class="num">12</span>
                  <span class="label">Announcements</span>
              </div>
              <div class="stat">
                  <span class="num">48</span>
                  <span class="label">Employees</span>
              </div>
              <div class="stat">
                  <span class="num">126</span>
                  <span class="label">Logins</span>
              </div>
          </div>

        </div>

        <!-- RIGHT SIDE: EDIT FORM -->
        <div class="profile-content">

          <form action="#" method="POST">
            @csrf

            {{-- PERSONAL INFO --}}
            <h3 class="section-title"><i class="fa-solid fa-user"></i> Personal Information</h3>

            <div class="form-row">
              <div class="form-group">
                <label>Full Name</label>
                <input type="text" value="HR Admin">
              </div>

              <div class="form-group">
                <label>Email Address</label>
                <input type="email" value="hradmin@example.com">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Phone Number</label>
                <input type="text" value="+60 12-345 6789">
              </div>

              <div class="form-group">
                <label>Department</label>
                <input type="text" value="Human Resources">
              </div>
            </div>

            {{-- ACCOUNT SECURITY --}}
            <h3 class="section-title"><i class="fa-solid fa-lock"></i> Account Security</h3>

            <div class="form-row">
              <div class="form-group">
                <label>Username</label>
                <input type="text" value="hr_admin">
              </div>

              <div class="form-group">
                <label>New Password</label>
                <input type="password" placeholder="Leave blank to keep current password">
              </div>

              <div class="form-group">
                <label>Confirm Password</label>
                <input type="password">
              </div>
            </div>

            <div class="form-actions">
              <button class="btn-save"><i class="fa-solid fa-save"></i> Save Changes</button>
            </div>

          </form>

        </div>

      </div>

      <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>

    </main>

  </div>

</body>
</html>
