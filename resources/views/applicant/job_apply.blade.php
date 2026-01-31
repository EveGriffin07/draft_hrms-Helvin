@extends('applicant.layout')

@push('styles')
<style>
    .apply-form-container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        max-width: 700px;
        margin: 0 auto;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 500; margin-bottom: 8px; color: #333; }
    .form-group label span { color: #dc2626; } /* Red asterisk for required */
    .form-group input, .form-group textarea {
        width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;
    }
    .form-group input:read-only { background-color: #f9fafb; color: #666; cursor: not-allowed; }
    .btn-submit {
        background: #2563eb; color: #fff; border: none; padding: 12px 24px; border-radius: 6px; 
        font-weight: 600; cursor: pointer; width: 100%; transition: 0.2s;
    }
    .btn-submit:hover { background: #1d4ed8; }
    .back-link { display: block; margin-bottom: 20px; color: #666; text-decoration: none; }
    .back-link:hover { color: #333; }
</style>
@endpush

@section('content')

<a href="{{ route('applicant.jobs.show', $job->job_id) }}" class="back-link">
    <i class="fa-solid fa-arrow-left"></i> Back to Job Details
</a>

<h2 class="page-title">Applying for: <span style="color: #2563eb;">{{ $job->job_title }}</span></h2>
<p class="page-subtitle">Please review your details and upload your resume.</p>

<div class="apply-form-container">

    {{-- ERROR MESSAGES --}}
    @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="apply-form" action="{{ route('applicant.jobs.submit', $job->job_id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Full Name (Read-only from Auth) --}}
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" value="{{ Auth::user()->name }}" readonly>
            <small style="color: #888;">Name is taken from your account profile.</small>
        </div>

        {{-- Email (Read-only from Auth) --}}
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" value="{{ Auth::user()->email }}" readonly>
        </div>

        {{-- Phone Number (NEW FIELD - REQUIRED) --}}
        <div class="form-group">
            <label for="phone">Phone Number <span>*</span></label>
            <input type="text" id="phone" name="phone" placeholder="e.g. 012-3456789" required>
        </div>

        {{-- Resume Upload --}}
        <div class="form-group">
            <label for="resume">Upload Resume (PDF, DOCX) <span>*</span></label>
            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
            <small style="color: #666;">Max file size: 2MB</small>
        </div>

        {{-- Cover Letter --}}
        <div class="form-group">
            <label for="cover_letter">Cover Letter (Optional)</label>
            <textarea id="cover_letter" name="cover_letter" rows="5" placeholder="Briefly introduce yourself and why you are a good fit..."></textarea>
        </div>

        {{-- Submit Button --}}
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-paper-plane"></i> Submit Application
            </button>
        </div>

    </form>
</div>

@endsection