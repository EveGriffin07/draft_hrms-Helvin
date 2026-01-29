@extends('applicant.layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/applicant/job_details.css') }}">
@endpush

@section('content')

<div class="job-details-container">

    {{-- ======================
         HEADER (Dynamic)
    ======================= --}}
    <div class="job-header">
        <h2 class="job-title">{{ $job->job_title }}</h2>
        
        {{-- Dynamic Badge Logic --}}
        @php
            $badgeClass = match($job->job_type) {
                'Full-Time' => 'fulltime',
                'Part-Time' => 'parttime',
                'Contract'  => 'contract',
                'Internship'=> 'internship',
                default     => 'fulltime'
            };
        @endphp
        <span class="job-type-tag {{ $badgeClass }}">{{ $job->job_type }}</span>
    </div>

    <div class="job-meta">
        <span><i class="fa-solid fa-building"></i> {{ $job->department }}</span>
        
        {{-- Dynamic Date (e.g., "2 days ago") --}}
        <span><i class="fa-solid fa-calendar"></i> Posted {{ $job->created_at->diffForHumans() }}</span>
        
        <span><i class="fa-solid fa-location-dot"></i> {{ $job->location }}</span>
        
        {{-- Salary (Optional) --}}
        @if($job->salary_range)
            <span><i class="fa-solid fa-money-bill"></i> {{ $job->salary_range }}</span>
        @endif
    </div>

    {{-- ======================
         DESCRIPTION
    ======================= --}}
    <div class="job-section">
        <h3>Description</h3>
        <p>
            {{-- nl2br allows line breaks from the textarea to show properly --}}
            {!! nl2br(e($job->job_description)) !!}
        </p>
    </div>

    {{-- ======================
         REQUIREMENTS
         (Note: Your Add Form only had Description & Requirements, 
          so we display Requirements here.)
    ======================= --}}
    <div class="job-section">
        <h3>Requirements & Responsibilities</h3>
        <p>
            {!! nl2br(e($job->requirements)) !!}
        </p>
    </div>

    {{-- ======================
         APPLY BUTTON
    ======================= --}}
    <div class="apply-btn-container">
        {{-- Links to the Apply Form for this specific Job ID --}}
        <a href="{{ route('applicant.jobs.apply', $job->job_id) }}" class="apply-btn">
            Apply Now
        </a>
    </div>

</div>

@endsection