@extends('layouts.app')

@section('content')
    <!-- Floating Navigation Bar (same style as your site) -->
    <nav class="floating-nav" id="navbar">
        <div class="nav-logo">
            <img src="{{ asset('images/gj.png') }}" alt="Logo" class="logo-img" id="logoImg">
        </div>

        <ul class="nav-menu" id="navMenu">
            <li><a href="{{ url('/') }}#home" class="nav-link">Home</a></li>
            <li><a href="{{ url('/') }}#about" class="nav-link">About</a></li>
            <li><a href="{{ url('/') }}#projects" class="nav-link">Projects</a></li>
            <li><a href="{{ url('/') }}#skills" class="nav-link">Skills</a></li>
            <li><a href="{{ url('/') }}#contact" class="nav-link">Contact</a></li>

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

            <h1 class="section-title">{{ $project->title }}</h1>

            <!-- Screenshots -->
            <div class="screenshots-section">
                <h2 class="subsection-title">Project Screenshots</h2>

                <div class="screenshots-grid">
                    @forelse($shots as $shot)
                        <div class="screenshot-card">
                            <div class="screenshot-image">
                                <img src="{{ url($shot->image_path) }}" alt="{{ $shot->title }}">
{{--                                <img src="{{ asset($shot->image_path) }}" alt="{{ $shot->title }}">--}}
                            </div>
                            <h3 class="screenshot-title">{{ $shot->title }}</h3>
                        </div>
                    @empty
                        <p class="coming-soon">No screenshots added yet.</p>
                    @endforelse
                </div>
            </div>

            @if(!empty($project->video_url))
                <div class="video-section">
                    <a href="{{ $project->video_url }}" target="_blank" rel="noopener">
                        Watch project video
                    </a>
                </div>
            @endif

            <!-- Description + Features -->
            <div class="description-section">
                <h2 class="subsection-title">Project Description</h2>

                <div class="description-content">
                    <p>{{ $project->description ?? 'No description yet.' }}</p>

                    @if(!empty($project->features))
                        <h3 class="feature-title">Key Features:</h3>
                        <ul class="feature-list">
                            @foreach(explode("\n", $project->features) as $feature)
                                @if(trim($feature) !== '')
                                    <li>{{ trim($feature) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Tools -->
            <div class="tools-section">
                <h2 class="subsection-title">Tools & Languages Used</h2>

                <div class="tools-list">
                    @php($tools = $project->tools ?? '')
                    @forelse(array_filter(array_map('trim', explode(',', $tools))) as $tool)
                        <span class="tool-item">{{ $tool }}</span>
                    @empty
                        <p class="coming-soon">Not added yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Links -->
            <div class="link-section">
                <h2 class="subsection-title">Website Link</h2>
                @if($project->live_link)
                    <a href="{{ $project->live_link }}" class="coming-soon">{{ $project->live_link }}</a>
                @else
                    <p class="coming-soon">Coming Soon</p>
                @endif
            </div>

            <!-- Back Button -->
            <div class="back-button-container">
                <a href="{{ url('/') }}#projects" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Projects
                </a>
            </div>

        </div>
    </section>
@endsection
