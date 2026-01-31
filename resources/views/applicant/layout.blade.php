<!DOCTYPE html>
<html>
<head>
    <title>Applicant Portal – HRMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">

    @stack('styles')
</head>

<body>

<header>
    <div class="title">Applicant Portal</div>
</header>

<div class="container">

    <aside class="sidebar">

        <ul>
            <li>
                <a href="{{ route('applicant.jobs') }}" class="{{ request()->is('applicant/jobs') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase"></i>
                    Job Listings
                </a>
            </li>

            <li>
                <a href="{{ route('applicant.applications') }}" class="{{ request()->is('applicant/applications') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i>
                    My Applications
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">

        <li class="{{ request()->routeIs('applicant.profile') ? 'active' : '' }}">
            <a href="{{ route('applicant.profile') }}">
                <i class="fa-solid fa-user"></i>
                <span>My Profile</span>
            </a>
        </li>

        <div class="sidebar-group">
            <a href="#" class="sidebar-toggle logout-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <div class="left"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></div>
            </a>
        </div>

        

        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
            @csrf
        </form>

    </aside>

    <!-- MAIN -->
    <main class="content">
        @yield('content')
    </main>

</div>

</body>
</html>
