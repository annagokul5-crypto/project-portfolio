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
            <h1 class="section-title">KIT Connect</h1>

            <!-- Screenshots Gallery -->
            <div class="screenshots-section">
                <h2 class="subsection-title">Project Screenshots</h2>
                <div class="screenshots-grid">
                    <!-- Screenshot Card 1 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/kitconnect/splash.jpg') }}" alt="Splash Screen">
                        </div>
                        <h3 class="screenshot-title">Splash Screen</h3>
                    </div>

                    <!-- Screenshot Card 2 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/kitconnect/contacts.jpg') }}" alt="Contacts List">
                        </div>
                        <h3 class="screenshot-title">Contacts List</h3>
                    </div>

                    <!-- Screenshot Card 3 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/kitconnect/departments.jpg') }}" alt="Departments">
                        </div>
                        <h3 class="screenshot-title">Department View</h3>
                    </div>

                    <!-- Screenshot Card 4 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/kitconnect/profile.jpg') }}" alt="Profile Details">
                        </div>
                        <h3 class="screenshot-title">Profile Details</h3>
                    </div>

                    <!-- Screenshot Card 5 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/kitconnect/search.jpg') }}" alt="Search Function">
                        </div>
                        <h3 class="screenshot-title">Search Contacts</h3>
                    </div>

                    <!-- Screenshot Card 6 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/kitconnect/settings.jpg') }}" alt="Settings">
                        </div>
                        <h3 class="screenshot-title">App Settings</h3>
                    </div>
                </div>
            </div>

            <!-- Project Description -->
            <div class="description-section">
                <h2 class="subsection-title">Project Description</h2>
                <div class="description-content">
                    <p>
                        KIT Connect is a smart mobile application designed to streamline contact management
                        for college departments. Built with Flutter, this app provides an intuitive interface
                        for accessing and managing department contacts efficiently.
                    </p>
                    <h3 class="feature-title">Key Features:</h3>
                    <ul class="feature-list">
                        <li>Comprehensive contact directory for all departments</li>
                        <li>Quick search and filter functionality</li>
                        <li>Direct call and email integration</li>
                        <li>Department-wise categorization</li>
                        <li>Offline access to saved contacts</li>
                        <li>Real-time contact updates</li>
                        <li>Clean and intuitive user interface</li>
                        <li>Quick dial and favorites system</li>
                        <li>Share contact details easily</li>
                        <li>Dark mode support</li>
                    </ul>
                </div>
            </div>

            <!-- Tools and Languages -->
            <div class="tools-section">
                <h2 class="subsection-title">Tools & Languages Used</h2>
                <div class="tools-list">
                    <span class="tool-item">Dart</span>
                    <span class="tool-item">Flutter</span>
                    <span class="tool-item">Android Studio</span>
                    <span class="tool-item">Firebase</span>
                    <span class="tool-item">Node.js</span>
                    <span class="tool-item">MongoDB</span>
                    <span class="tool-item">Git</span>
                    <span class="tool-item">Figma</span>
                </div>
            </div>

            <!-- Website Link -->
            <div class="link-section">
                <h2 class="subsection-title">Playstore Link</h2>
                <p class="coming-soon">Coming Soon</p>
            </div>

            <!-- Back Button -->
            <div class="back-button-container">
                <a href="{{ url('/') }}#projects" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Projects
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="{{ url('/') }}#home">Home</a>
            <a href="{{ url('/') }}#about">About</a>
            <a href="{{ url('/') }}#projects">Projects</a>
            <a href="{{ url('/') }}#skills">Skills</a>
            <a href="{{ url('/') }}#contact">Contact</a>
        </div>
        <p class="copyright">&copy; 2025 Gokulraju A. All Rights Reserved.</p>
    </footer>
@endsection
