@extends('layouts.app')

@section('content')
    <!-- Floating Navigation Bar -->
    <nav class="floating-nav" id="navbar">
        <div class="nav-logo">
            <img src="{{ asset('images/gr.jpg') }}" alt="Logo" class="logo-img" id="logoImg">
        </div>
        <ul class="nav-menu" id="navMenu">
            <li><a href="{{ url('/') }}#home" class="nav-link">Home</a></li>
            <li><a href="{{ url('/') }}#about" class="nav-link">About</a></li>
            <li><a href="{{ url('/') }}#projects" class="nav-link">Projects</a></li>
            <li><a href="{{ url('/') }}#skills" class="nav-link">Skills</a></li>
            <li><a href="{{ url('/') }}#contact" class="nav-link">Contact</a></li>

            <!-- Theme Toggle for Mobile -->
            <li class="mobile-theme-toggle">
                <div class="theme-toggle-mobile" id="themeToggleMobile">
                    <span class="theme-label">Theme</span>
                    <div class="theme-toggle-slider-mobile">
                        <i class="fas fa-moon"></i>
                    </div>
                </div>
            </li>
        </ul>

        <div class="theme-toggle" id="themeToggle">
            <div class="theme-toggle-slider">
                <i class="fas fa-moon"></i>
            </div>
        </div>

        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <!-- Project Detail Section -->
    <section class="project-detail-section">
        <div class="project-detail-container">
            <!-- Project Title -->
            @php
                $project = \App\Models\Project::where('title', 'Kit Connect')->first();
            @endphp
            <h1 class="section-title">{{ $project->title }}</h1>
                <!-- Screenshots Gallery -->
            <div class="screenshots-section">
                <h2 class="subsection-title">Project Screenshots</h2>
                <div class="screenshots-grid">
                    <!-- Screenshot Card 1 -->
                    @php
                        $shots = \App\Models\ProjectScreenshot::where('project_id', 6)->get();  // 2 = KIT Connect
                    @endphp
                @foreach($shots as $shot)
                        <div class="screenshot-card">
                            <div class="screenshot-image">
{{--                                <img src="{{ asset($shot->image_path) }}" alt="{{ $shot->title }}">--}}
                                <img src="{{ str_starts_with($project->image, 'http') ? $project->image : asset($project->image) }}" alt="Project Image">
                            </div>
                            <h3 class="screenshot-title">{{ $shot->title }}</h3>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Project Description -->
            <div class="description-section">
                <h2 class="subsection-title">Project Description</h2>
                <div class="description-content">
                    <p>{{ $project->description ?? $project->short_description }}</p>

                    <h3 class="feature-title">Key Features:</h3>
                    <ul class="feature-list">
                        @foreach(explode("\n", $project->features ?? '') as $feature)
                            @if(trim($feature) !== '')
                                <li>{{ trim($feature) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Tools and Languages -->
            <div class="tools-section">
                <h2 class="subsection-title">Tools & Languages Used</h2>
                <div class="tools-list">
                    @foreach(explode(',', $project->tools ?? '') as $tool)
                        @if(trim($tool) !== '')
                            <span class="tool-item">{{ trim($tool) }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="link-section">
                <h2 class="subsection-title">Website Link</h2>
                @if($project->live_link)
                    <a href="{{ $project->live_link }}" class="coming-soon">{{ $project->live_link }}</a>
                @else
                    <p class="coming-soon">Coming Soon</p>
                @endif
            </div>
                </div>
        <!-- Back Button -->
        <div class="back-button-container">
            <a href="{{ url('/') }}#projects" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Projects
            </a>
        </div>
    </section>

    <!-- Footer -->
{{--    <footer class="footer">--}}
{{--        <div class="footer-links">--}}
{{--            <a href="{{ url('/') }}#home">Home</a>--}}
{{--            <a href="{{ url('/') }}#about">About</a>--}}
{{--            <a href="{{ url('/') }}#projects">Projects</a>--}}
{{--            <a href="{{ url('/') }}#skills">Skills</a>--}}
{{--            <a href="{{ url('/') }}#contact">Contact</a>--}}
{{--        </div>--}}
{{--        <p class="copyright">--}}
{{--            &copy; {{ $footerYear ?? date('Y') }} Gokulraju All Rights Reserved.--}}
{{--        </p>--}}
{{--    </footer>--}}
@endsection
