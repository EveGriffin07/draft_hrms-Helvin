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
    <div class="search-container">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="jobSearch" placeholder="Search job title or department...">
    </div>

    <select class="filter-select" id="jobFilter">
        <option value="all">All Job Types</option>
        <option value="Full-Time">Full-time</option>
        <option value="Part-Time">Part-time</option>
        <option value="Contract">Contract</option>
        <option value="Internship">Internship</option>
    </select>
</div>

{{-- =======================
     JOB LIST LOOP
======================== --}}
<div class="jobs-container">

    @forelse($jobs as $job)
        {{-- 
            We add a data-type attribute so simple JS filtering can work later if needed.
            We also dynamically assign the class based on job type for color coding.
        --}}
        <div class="job-card" data-type="{{ $job->job_type }}">
            <div class="job-header">
                <h3 class="job-title">{{ $job->job_title }}</h3>
                
                {{-- Dynamic Badge Class --}}
                @php
                    $badgeClass = match($job->job_type) {
                        'Full-Time' => 'fulltime',
                        'Part-Time' => 'parttime', // ensure you have css for this or fallback
                        'Contract'  => 'contract',
                        'Internship'=> 'internship',
                        default     => 'fulltime'
                    };
                @endphp
                <span class="job-type-tag {{ $badgeClass }}">{{ $job->job_type }}</span>
            </div>

            <p class="job-dept"><i class="fa-solid fa-building"></i> {{ $job->department }}</p>
            <p class="job-loc" style="font-size: 13px; color: #666; margin-top: 5px;">
                <i class="fa-solid fa-location-dot"></i> {{ $job->location }}
            </p>

            <div class="job-footer">
                {{-- VIEW DETAILS LINK --}}
                <a href="{{ route('applicant.jobs.show', $job->job_id) }}" class="job-btn view">
                    View Details
                </a>

                {{-- APPLY LINK --}}
                <a href="{{ route('applicant.jobs.apply', $job->job_id) }}" class="job-btn apply">
                    Apply Now
                </a>
            </div>
        </div>

    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
            <i class="fa-solid fa-briefcase" style="font-size: 30px; margin-bottom: 10px; opacity: 0.5;"></i>
            <p>No open job positions at the moment.</p>
        </div>
    @endforelse

</div>

{{-- Optional: Simple Filter Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('jobFilter');
        const searchInput = document.getElementById('jobSearch');
        const cards = document.querySelectorAll('.job-card');

        function filterJobs() {
            const type = filterSelect.value;
            const term = searchInput.value.toLowerCase();

            cards.forEach(card => {
                const cardType = card.getAttribute('data-type');
                const title = card.querySelector('.job-title').innerText.toLowerCase();
                const dept = card.querySelector('.job-dept').innerText.toLowerCase();

                const matchesType = (type === 'all' || cardType === type);
                const matchesSearch = (title.includes(term) || dept.includes(term));

                if (matchesType && matchesSearch) {
                    card.style.display = 'flex'; // Restore display
                } else {
                    card.style.display = 'none';
                }
            });
        }

        filterSelect.addEventListener('change', filterJobs);
        searchInput.addEventListener('input', filterJobs);
    });
</script>

@endsection