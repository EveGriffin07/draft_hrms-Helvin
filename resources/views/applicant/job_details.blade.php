@extends('applicant.layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/applicant/job_details.css') }}">
@endpush

@section('content')

<div class="job-details-container">

    {{-- ======================
         HEADER
    ======================= --}}
    <div class="job-header">
        <h2 class="job-title">Software Engineer</h2>
        <span class="job-type-tag fulltime">Full-time</span>
    </div>

    <div class="job-meta">
        <span><i class="fa-solid fa-building"></i> IT Department</span>
        <span><i class="fa-solid fa-calendar"></i> Posted 3 days ago</span>
        <span><i class="fa-solid fa-location-dot"></i> Kuala Lumpur</span>
    </div>

    {{-- ======================
         DESCRIPTION
    ======================= --}}
    <div class="job-section">
        <h3>Description</h3>
        <p>
            We are looking for a passionate Software Engineer to join our development team.
            You will be involved in designing, developing, and maintaining enterprise-level
            HRMS systems used across the organization.
        </p>
    </div>

    {{-- ======================
         RESPONSIBILITIES
    ======================= --}}
    <div class="job-section">
        <h3>Responsibilities</h3>
        <ul>
            <li>Develop and maintain HRMS-related web applications.</li>
            <li>Collaborate with the UI/UX team to refine interface designs.</li>
            <li>Conduct system testing and performance optimization.</li>
            <li>Write clean, scalable, and reusable code.</li>
        </ul>
    </div>

    {{-- ======================
         REQUIREMENTS
    ======================= --}}
    <div class="job-section">
        <h3>Requirements</h3>
        <ul>
            <li>Minimum 1 year experience in software development.</li>
            <li>Good understanding of Laravel or MVC frameworks.</li>
            <li>Strong problem-solving and debugging skills.</li>
            <li>Ability to work collaboratively in a team environment.</li>
        </ul>
    </div>

    {{-- ======================
         APPLY BUTTON
    ======================= --}}
    <div class="apply-btn-container">
        <a href="{{ route('applicant.apply') }}" class="apply-btn">Apply Now</a>
    </div>

</div>

@endsection
