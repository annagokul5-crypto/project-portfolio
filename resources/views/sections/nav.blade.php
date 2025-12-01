<nav class="navbar glassy">
    <div class="navbar-inner">
        <!-- Logo section (right side) -->
        <div class="navbar-logo">
            <!-- Replace src and alt for your logo, or use initials text -->
            <img src="{{ asset('images/logo.png') }}" alt="Portfolio Logo" class="logo-img" />
        </div>
        <!-- Navigation links (left side) -->
        <ul class="nav-links">
            <li><a class="nav-link glow" href="#home">Home</a></li>
            <li><a class="nav-link glow" href="#about">About</a></li>
            <li><a class="nav-link glow" href="#projects">Projects</a></li>
            <li><a class="nav-link glow" href="#skills">Skills</a></li>
            <li><a class="nav-link glow" href="#contact">Contact</a></li>
        </ul>
        <!-- Mobile menu toggle -->
        <div class="mobile-menu-icon" id="mobileToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- Mobile dropdown menu -->
    <div class="mobile-nav" id="mobileNav">
        <ul>
            <li><a href="#home" class="nav-link">Home</a></li>
            <li><a href="#about" class="nav-link">About</a></li>
            <li><a href="#projects" class="nav-link">Projects</a></li>
            <li><a href="#skills" class="nav-link">Skills</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
        </ul>
    </div>
</nav>
