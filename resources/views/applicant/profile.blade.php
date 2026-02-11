@extends('applicant.layout')

@section('content')

<h2 class="page-title">My Profile</h2>
<p class="page-subtitle">Manage your personal information, skills, languages, and resume.</p>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
<div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- ERROR MESSAGES --}}
@if ($errors->any())
<div style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- 
    FIX: The FORM tag now has the 'profile-container' class and inline flex styles.
    This ensures the Sidebar (Left) and Content (Right) sit side-by-side.
--}}
<form action="{{ route('applicant.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-container" style="display: flex; gap: 30px; align-items: flex-start;">
    @csrf

    {{-- LEFT SIDEBAR --}}
    <div class="profile-sidebar" style="flex: 1; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
        
        {{-- 
            FIX: Added 'display: flex' and 'justify-content: center' 
            This forces the image to be perfectly centered, fixing the misalignment.
        --}}
        <div class="avatar-container" style="margin: 0 auto 20px auto; width: 130px; height: 130px; display: flex; justify-content: center; align-items: center;">
            @if($profile->avatar_path)
                <img src="{{ asset('storage/' . $profile->avatar_path) }}" class="avatar-preview" id="avatarPreview" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #f3f4f6;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff" class="avatar-preview" id="avatarPreview" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #f3f4f6;">
            @endif
        </div>

        {{-- HIDDEN INPUT --}}
        <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display: none;">

        {{-- BUTTON --}}
        <button type="button" class="btn-upload" onclick="document.getElementById('avatarInput').click();" style="background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px;">
            <i class="fa-solid fa-image"></i> Change Photo
        </button>

        <p class="avatar-note" style="margin-top: 10px; font-size: 12px; color: #888;">JPG or PNG • Max 2MB</p>

        <h3 class="profile-name" style="margin-top: 15px; font-weight: 600;">{{ $user->name }}</h3>
        <p class="profile-role" style="color: #666;">Applicant</p>
    </div>

    {{-- RIGHT CONTENT --}}
    <div class="profile-content" style="flex: 3; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">

        {{-- PERSONAL INFORMATION --}}
        <div class="card-section" style="margin-bottom: 30px;">
            <h3 class="section-title" style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; font-size: 18px; color: #1f2937;"><i class="fa-solid fa-user"></i> Personal Information</h3>

            <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Full Name</label>
                    <input type="text" value="{{ $user->name }}" readonly style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #f9fafb; cursor: not-allowed;">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Email Address</label>
                    <input type="email" value="{{ $user->email }}" readonly style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #f9fafb; cursor: not-allowed;">
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" placeholder="e.g. +60 12-345 6789" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Location</label>
                    <input type="text" name="location" value="{{ old('location', $profile->location ?? '') }}" placeholder="City, State" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
            </div>
        </div>

        {{-- RESUME --}}
        <div class="card-section">
            <h3 class="section-title" style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; font-size: 18px; color: #1f2937;"><i class="fa-solid fa-file-arrow-up"></i> Resume</h3>

            <div class="resume-box" style="border: 2px dashed #e5e7eb; padding: 20px; border-radius: 8px; text-align: center;">
                <p style="margin-bottom: 15px; color: #666;">Upload your latest resume (PDF only)</p>

                <input type="file" name="resume" accept=".pdf,.doc,.docx" class="resume-input" style="margin-bottom: 10px;">

                @if($profile->resume_path)
                    <div class="resume-item" style="margin-top: 15px; background: #f8fafc; padding: 10px; border-radius: 6px; display: flex; align-items: center; justify-content: space-between; gap: 10px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 24px;"></i>
                            <span style="font-weight: 500; color: #333;">Current Resume</span>
                        </div>
                        
                        <div style="display: flex; gap: 15px;">
                            <a href="{{ asset('storage/' . $profile->resume_path) }}" target="_blank" class="resume-view" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                <i class="fa-solid fa-eye"></i> View
                            </a>

                            <a href="{{ route('applicant.resume.delete') }}" class="resume-delete" style="color: #dc2626; text-decoration: none; font-weight: 500;" onclick="return confirm('Are you sure you want to remove your resume?');">
                                <i class="fa-solid fa-trash"></i> Remove
                            </a>
                        </div>
                    </div>
                @else
                    <p style="margin-top: 10px; color: #888; font-style: italic;">No resume uploaded yet.</p>
                @endif
            </div>
        </div>

        {{-- SAVE BUTTON --}}
        <div class="form-actions" style="margin-top: 30px; text-align: right;">
            <button type="submit" class="btn-save" style="background: #2563eb; color: #fff; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500;">
                Save Changes
            </button>
        </div>

    </div>

</form>

{{-- SCRIPT TO PREVIEW IMAGE IMMEDIATELY --}}
<script>
    document.getElementById('avatarInput').addEventListener('change', function(event){
        const [file] = event.target.files;
        if (file) {
            document.getElementById('avatarPreview').src = URL.createObjectURL(file);
        }
    });
</script>

@endsection