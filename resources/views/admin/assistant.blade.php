<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Assistant - HRMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/hrms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assistant.css') }}">
</head>

<body>
<header>
    <div class="title">Web-Based HRMS</div>
    <div class="user-info">
    <a href="{{ route('admin.profile') }}" style="text-decoration: none; color: inherit;">
        <i class="fa-regular fa-bell"></i> &nbsp; HR Admin
    </a>
</div>
</header>

<div class="container">
    {{-- Left sidebar --}}
    @include('admin.layout.sidebar')

    <main>
        <div class="breadcrumb">Home > AI Assistant</div>
        <h2>HRMS Assistant</h2>
        <p class="subtitle">
            Ask questions about HR processes or quickly navigate to different HRMS modules.
        </p>

        <div class="assistant-page">
            <div class="assistant-container">

                {{-- LEFT: Chat Area --}}
                <section class="chat-section">
                    <header class="chat-header">
                        <div class="chat-title">
                            <h2>How can I assist you today?</h2>
                            <p>Your virtual HR helper for daily tasks and FAQs.</p>
                        </div>

                        <div class="chat-meta">
                            <span class="assistant-badge online">
                                <i class="fa-solid fa-circle"></i> Online
                            </span>
                            <span class="assistant-badge">
                                <i class="fa-solid fa-shield-halved"></i> Internal HRMS Info Only
                            </span>
                        </div>
                    </header>

                    {{-- Quick actions --}}
                    <div class="assistant-welcome">
                        <h3>Quick actions</h3>
                        <p class="assistant-hint">Jump straight to a module:</p>
                        <div class="quick-actions">
                            <button type="button" onclick="window.location='{{ route('admin.dashboard') }}'">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </button>
                            <button type="button" onclick="window.location='{{ route('admin.recruitment') }}'">
                                <i class="fa-solid fa-briefcase"></i> Recruitment
                            </button>
                            <button type="button" onclick="window.location='{{ route('admin.training') }}'">
                                <i class="fa-solid fa-chalkboard-user"></i> Training
                            </button>
                            <button type="button" onclick="window.location='{{ route('admin.appraisal') }}'">
                                <i class="fa-solid fa-chart-line"></i> Appraisal
                            </button>
                            <button type="button" onclick="window.location='{{ route('admin.onboarding') }}'">
                                <i class="fa-solid fa-user-plus"></i> Onboarding
                            </button>
                        </div>
                    </div>

                    {{-- Chat window --}}
                    <div class="chat-window">
                        <div class="chat-messages">
                            <div class="msg ai-msg">
                                <div class="msg-bubble">
                                    Welcome to the HRMS Assistant. You can ask questions such as
                                    “How do I start a new onboarding?” or “Show me current training
                                    programs”, or use the quick links on the right.
                                </div>
                            </div>
                            {{-- Future chat messages will appear here --}}
                        </div>
                    </div>

                    {{-- Input row (dummy for now) --}}
                    <form class="chat-input-row" method="POST">
                        @csrf
                        <input
                            type="text"
                            name="message"
                            placeholder="Ask something about HR (e.g. leave policy, appraisal flow)…"
                            required
                        >
                        <button type="submit">
                            Send
                        </button>
                    </form>
                </section>

                {{-- RIGHT: Sidebar --}}
                <aside class="right-sidebar">
                    <div class="sidebar-card">
                        <h4>Quick Navigation</h4>
                        <button type="button" onclick="window.location='{{ route('admin.dashboard') }}'">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard
                        </button>
                        <button type="button" onclick="window.location='{{ route('admin.profile') }}'">
                            <i class="fa-solid fa-user"></i> My Profile
                        </button>
                        <button type="button" onclick="window.location='{{ route('admin.recruitment') }}'">
                            <i class="fa-solid fa-briefcase"></i> Recruitment
                        </button>
                        <button type="button" onclick="window.location='{{ route('admin.training') }}'">
                            <i class="fa-solid fa-chalkboard-user"></i> Training
                        </button>
                        <button type="button" onclick="window.location='{{ route('admin.appraisal') }}'">
                            <i class="fa-solid fa-chart-line"></i> Performance
                        </button>
                        <button type="button" onclick="window.location='{{ route('admin.onboarding') }}'">
                            <i class="fa-solid fa-user-plus"></i> Onboarding
                        </button>
                    </div>

                    <div class="sidebar-card">
                        <h4>HR Knowledge Base</h4>
                        <ul>
                            <li>Leave policies & entitlements</li>
                            <li>Standard onboarding steps</li>
                            <li>Performance evaluation cycle</li>
                            <li>Training & development guidelines</li>
                            <li>HR compliance and conduct rules</li>
                        </ul>
                    </div>

                    <div class="sidebar-card">
                        <h4>System Assistant</h4>
                        <p>
                            This assistant focuses on factual HRMS information and internal policy
                            guidance. It does not make final HR decisions.
                        </p>
                    </div>
                </aside>

            </div>
        </div>

        <footer>© 2025 Web-Based HRMS. All Rights Reserved.</footer>
    </main>
</div>

</body>
</html>
