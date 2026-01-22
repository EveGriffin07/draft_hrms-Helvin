@extends('applicant.layout')

@section('content')

<h2 class="page-title">My Profile</h2>
<p class="page-subtitle">Manage your personal information, skills, languages, and resume.</p>

<div class="profile-container">

    {{-- LEFT SIDEBAR --}}
    <div class="profile-sidebar">
        <div class="avatar-wrapper">
            <img src="https://via.placeholder.com/150" class="avatar-preview">
        </div>

        <button class="btn-upload">
            <i class="fa-solid fa-image"></i> Change Photo
        </button>

        <p class="avatar-note">JPG or PNG • Max 2MB</p>

        <h3 class="profile-name">Applicant Name</h3>
        <p class="profile-role">Job Seeker</p>
    </div>

    {{-- RIGHT CONTENT --}}
    <div class="profile-content">

        {{-- PERSONAL INFORMATION --}}
        <div class="card-section">
            <h3 class="section-title"><i class="fa-solid fa-user"></i> Personal Information</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="John Doe">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" value="john@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" value="+60 12-345 6789">
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" placeholder="City, State">
                </div>
            </div>
        </div>

        {{-- SKILLS --}}
        <div class="card-section">
            <h3 class="section-title"><i class="fa-solid fa-bolt"></i> Skills</h3>

            <div class="skills-box">
                <span class="skill-tag">Communication</span>
                <span class="skill-tag">Python</span>
                <span class="skill-tag">Leadership</span>
                <span class="skill-tag">Teamwork</span>

                <input type="text" class="skill-input" placeholder="Add a skill...">
            </div>
        </div>

        {{-- LANGUAGES --}}
        <div class="card-section">
            <h3 class="section-title"><i class="fa-solid fa-language"></i> Languages</h3>

            <div class="languages-box">
                <div class="language-item">
                    <span>English</span>
                    <span class="proficiency">Fluent</span>
                </div>

                <div class="language-item">
                    <span>Malay</span>
                    <span class="proficiency">Native</span>
                </div>

                <input type="text" class="language-input"
                       placeholder="Add a language (e.g., Japanese - Intermediate)">
            </div>
        </div>

        {{-- RESUME --}}
        <div class="card-section">
            <h3 class="section-title"><i class="fa-solid fa-file-arrow-up"></i> Resume</h3>

            <div class="resume-box">
                <p>Upload your latest resume (PDF only)</p>

                <input type="file" accept=".pdf" class="resume-input">

                <div class="resume-item">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>MyResume2025.pdf</span>
                    <a href="#" class="resume-view">View</a>
                    <a href="#" class="resume-delete">Delete</a>
                </div>
            </div>
        </div>

        {{-- SAVE BUTTON --}}
        <div class="form-actions">
            <button class="btn-save">Save Changes</button>
        </div>

    </div>

</div>

@endsection
