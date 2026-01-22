@extends('applicant.layout')

@push('styles')


@section('content')

<h2 class="page-title">Job Application</h2>
<p class="page-subtitle">Please fill in the required fields below to submit your application.</p>

<div class="apply-form-container">

    <form class="apply-form">

        {{-- Full Name --}}
        <div class="form-group">
            <label for="name">Full Name <span>*</span></label>
            <input type="text" id="name" name="name" placeholder="Enter your full name">
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Email Address <span>*</span></label>
            <input type="email" id="email" name="email" placeholder="example@email.com">
        </div>

        {{-- Phone --}}
        <div class="form-group">
            <label for="phone">Phone Number <span>*</span></label>
            <input type="text" id="phone" name="phone" placeholder="012-3456789">
        </div>

        {{-- Resume Upload --}}
        <div class="form-group">
            <label for="resume">Upload Resume (PDF Only) <span>*</span></label>
            <input type="file" id="resume" name="resume" accept="application/pdf">
        </div>

        {{-- Cover Letter --}}
        <div class="form-group">
            <label for="cover">Cover Letter (Optional)</label>
            <textarea id="cover" name="cover" rows="5" placeholder="Briefly introduce yourself..."></textarea>
        </div>

        {{-- Submit Button --}}
        <div class="form-actions">
            <button type="submit" class="btn-submit">Submit Application</button>
        </div>

    </form>
</div>

@endsection
