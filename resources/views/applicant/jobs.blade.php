@extends('applicant.layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/applicant/jobs.css') }}">
@endpush

@section('content')

<h2 class="page-title">Available Job Positions</h2>
<p class="page-subtitle">Browse open roles and apply directly through this portal.</p>

{{-- =======================
     SEARCH & FILTER BAR
======================== --}}
<div class="filter-bar">

    {{-- SEARCH BAR --}}
    <div class="search-container">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search job title or department...">
    </div>

    {{-- JOB TYPE DROPDOWN --}}
    <select class="filter-select">
        <option value="">All Job Types</option>
        <option value="fulltime">Full-time</option>
        <option value="contract">Contract</option>
        <option value="internship">Internship</option>
    </select>

</div>


{{-- =======================
     JOB LIST
======================== --}}
<div class="jobs-container">

    <div class="job-card" data-type="fulltime">
        <div class="job-header">
            <h3 class="job-title">Software Engineer</h3>
            <span class="job-type-tag fulltime">Full-time</span>
        </div>
        <p class="job-dept"><i class="fa-solid fa-building"></i> IT Department</p>
        <div class="job-footer">
        <button class="job-btn view"
        onclick="window.location='{{ route('job.details') }}'">
    View Details
</button>

<button class="job-btn apply"
        onclick="window.location='{{ route('applicant.apply') }}'">
    Apply Now
</button>


        </div>
    </div>

    <div class="job-card" data-type="contract">
        <div class="job-header">
            <h3 class="job-title">HR Executive</h3>
            <span class="job-type-tag contract">Contract</span>
        </div>
        <p class="job-dept"><i class="fa-solid fa-building"></i> Human Resources</p>
        <div class="job-footer">
            <button class="job-btn view"
        onclick="window.location='{{ route('job.details') }}'">
    View Details
</button>

<button class="job-btn apply"
        onclick="window.location='{{ route('applicant.apply') }}'">
    Apply Now
</button>

        </div>
    </div>

    <div class="job-card" data-type="internship">
        <div class="job-header">
            <h3 class="job-title">Marketing Assistant</h3>
            <span class="job-type-tag internship">Internship</span>
        </div>
        <p class="job-dept"><i class="fa-solid fa-building"></i> Marketing</p>
        <div class="job-footer">
           <button class="job-btn view"
        onclick="window.location='{{ route('job.details') }}'">
    View Details
</button>

<button class="job-btn apply"
        onclick="window.location='{{ route('applicant.apply') }}'">
    Apply Now
</button>

        </div>
    </div>

</div>

@endsection
