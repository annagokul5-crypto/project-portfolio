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
            <h1 class="section-title">E-Commerce Web Application</h1>

            <!-- Screenshots Gallery -->
            <div class="screenshots-section">
                <h2 class="subsection-title">Project Screenshots</h2>
                <div class="screenshots-grid">
                    <!-- Screenshot Card 1 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/ecommerce/home.jpg') }}" alt="Home Page">
                        </div>
                        <h3 class="screenshot-title">Home Page</h3>
                    </div>

                    <!-- Screenshot Card 2 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/ecommerce/products.jpg') }}" alt="Products Page">
                        </div>
                        <h3 class="screenshot-title">Products Page</h3>
                    </div>

                    <!-- Screenshot Card 3 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/ecommerce/cart.jpg') }}" alt="Shopping Cart">
                        </div>
                        <h3 class="screenshot-title">Shopping Cart</h3>
                    </div>

                    <!-- Screenshot Card 4 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/ecommerce/checkout.jpg') }}" alt="Checkout Page">
                        </div>
                        <h3 class="screenshot-title">Checkout Page</h3>
                    </div>

                    <!-- Screenshot Card 5 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/ecommerce/admin.jpg') }}" alt="Admin Dashboard">
                        </div>
                        <h3 class="screenshot-title">Admin Dashboard</h3>
                    </div>

                    <!-- Screenshot Card 6 -->
                    <div class="screenshot-card">
                        <div class="screenshot-image">
                            <img src="{{ asset('images/projects/ecommerce/login.jpg') }}" alt="Login Page">
                        </div>
                        <h3 class="screenshot-title">User Login</h3>
                    </div>
                </div>
            </div>

            <!-- Project Description -->
            <div class="description-section">
                <h2 class="subsection-title">Project Description</h2>
                <div class="description-content">
                    <p>
                        A comprehensive e-commerce web application developed using modern web technologies.
                        This platform provides a seamless shopping experience for users while offering robust
                        management tools for administrators.
                    </p>
                    <h3 class="feature-title">Key Features:</h3>
                    <ul class="feature-list">
                        <li>User authentication and authorization system</li>
                        <li>Product catalog with advanced search and filtering</li>
                        <li>Shopping cart functionality with real-time updates</li>
                        <li>Secure payment gateway integration</li>
                        <li>Order tracking and management system</li>
                        <li>Admin dashboard for inventory management</li>
                        <li>Responsive design for all devices</li>
                        <li>Email notifications for orders and updates</li>
                        <li>Product reviews and ratings system</li>
                        <li>Wishlist functionality for users</li>
                    </ul>
                </div>
            </div>

            <!-- Tools and Languages -->
            <div class="tools-section">
                <h2 class="subsection-title">Tools & Languages Used</h2>
                <div class="tools-list">
                    <span class="tool-item">HTML</span>
                    <span class="tool-item">CSS</span>
                    <span class="tool-item">JavaScript</span>
                    <span class="tool-item">Bootstrap</span>
                    <span class="tool-item">Laravel</span>
                    <span class="tool-item">PHP</span>
                    <span class="tool-item">MySQL</span>
                    <span class="tool-item">Git</span>
                </div>
            </div>

            <!-- Website Link -->
            <div class="link-section">
                <h2 class="subsection-title">Website Link</h2>
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
