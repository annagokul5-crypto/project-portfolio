@extends('layouts.app')

@section('content')
    <!-- Floating Navigation Bar -->
    <nav class="floating-nav" id="navbar">
        <div class="nav-logo">
            <img src="{{ asset('images/gj.png') }}" alt="Logo" class="logo-img" id="logoImg">
        </div>
        <ul class="nav-menu" id="navMenu">
            <li><a href="#home" class="nav-link">Home</a></li>
            <li><a href="#about" class="nav-link">About</a></li>
            <li><a href="#projects" class="nav-link">Projects</a></li>
            <li><a href="#skills" class="nav-link">Skills</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>

            <!-- Theme Toggle for Mobile (Inside Menu) -->
            <li class="mobile-theme-toggle">
                <div class="theme-toggle-mobile" id="themeToggleMobile">
                    <span class="theme-label">Theme</span>
                    <div class="theme-toggle-slider-mobile">
                        <i class="fas fa-moon"></i>
                    </div>
                </div>
            </li>
        </ul>

        <!-- Theme Toggle Button (Desktop Only) -->
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

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-container">
            <div class="hero-image-container">
                <div class="flip-card" id="flipCard">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <img src="{{ asset('images/3d.png') }}" alt="3D Character">
                        </div>
                        <div class="flip-card-back">
                            <img src="{{ asset('images/real3.PNG') }}" alt="Real Image" >
                        </div>
                    </div>
                </div>
                <button class="click-me-btn" id="clickMeBtn">
                    <i class="fas fa-sync-alt"></i> Flip
                </button>
            </div>
            <div class="hero-content">
                @php
                    $heroContent = \App\Models\HeroContent::first();

                    $heroName      = $heroContent->name        ?? 'GOKULRAJU A';
                    $heroObjective = $heroContent->objective   ?? 'Enthusiastic AI and Data Science student...';
                    $heroResumeUrl = $heroContent->resume_path ?? '#';

                    // This field now contains: "WEB DEVELOPER, SOFTWARE DEVELOPER, AI ENGINEER, GRAPHIC DESIGNER"
                    $rawTitles = $heroContent->title ?? 'WEB DEVELOPER';

                    // Split into array for JS
                    $heroTitles = array_map('trim', explode(',', $rawTitles));
                @endphp

                <h1 class="hero-name">{{ $heroName }}</h1>
                <h2 class="hero-title">ASPIRING <span class="typing-text">{{ $heroTitles[0] ?? '' }}</span></h2>
                <p class="hero-objective">{{ $heroObjective }}</p>
                <a href="{{ $heroResumeUrl }}" download="Gokulraju_resume.pdf" class="download-resume-btn">
                    <i class="fas fa-download"></i>
                    <span>Download Resume</span></a>

            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <h2 class="section-title">About Me</h2>
        <div class="about-container">
            @php
                $about = \App\Models\AboutContent::first();
            @endphp
            <div class="about-content">
                <p>
                {!! nl2br(e($about->content)) !!}
                </p>
            </div>

            <div class="about-image-container">
                <div class="about-image-frame">
                    <img src="{{ asset($about->image_path ?? 'images/real1.png') }}"
                         alt="Gokulraju A" class="about-image">
                </div>
            </div>
        </div>
    </section>


    <!-- Projects Section -->
    <!-- Projects Section -->
    <section id="projects" class="projects-section">
        <h2 class="section-title">Projects</h2>
        <div class="projects-container">

            @php
                $projects = \App\Models\Project::orderBy('order_index')->get();
            @endphp

            @foreach($projects as $project)
                <div class="project-card">
                    @if($project->status === 'ongoing')
                        <div class="project-badge ongoing">Ongoing Project</div>
                    @endif

                    <h3 class="project-title">{{ $project->title }}</h3>

                    <p class="project-description">
                        {{ $project->short_description ?? $project->description }}
                    </p>

                        <div class="project-buttons">
                            {{-- View details page --}}
                            @php
                                $detailUrl = '#';
                                if ($project->title === 'Ecommerce website') {
                                    $detailUrl = url('/project/ecommerce');
                                } elseif ($project->title === 'Kit Connect') {
                                    $detailUrl = url('/project/kitconnect');
                                } elseif ($project->title === 'portfolio') {
                                    $detailUrl = url('/project/portfolio');
                                }
                            @endphp


                            <a href="{{ route('projects.show', $project->id) }}" class="project-btn" title="View Project">
                                <i class="fas fa-eye"></i>
                            </a>



                            {{-- Optional GitHub link --}}
                            @if($project->github_link)
                                <a href="{{ $project->github_link }}" class="project-btn" title="GitHub">
                                    <i class="fab fa-github"></i>
                                </a>
                            @endif
                        </div>

                </div>
            @endforeach

        </div>
    </section>
    <!-- Skills Section -->
    @php
        use App\Models\SkillCategory;
        use App\Models\Skill;

        $categories = SkillCategory::orderBy('order_index')->get();
    @endphp

    <section id="skills" class="skills-section">
        <h2 class="section-title">Skills & Tools</h2>
        <div class="skills-container">

            @foreach($categories as $category)
                @php
                    $items = Skill::where('category_id', $category->id)
                                  ->orderByDesc('percentage')
                                  ->get();
                @endphp

                @if($items->isNotEmpty())
                    <div class="skill-card">
                        <h3 class="skill-title">{{ $category->name }}</h3>

                        @foreach($items as $skill)
                            <div class="skill-item">
                                <span class="skill-name">{{ $skill->skill_name }}</span>
                                <div class="skill-bar">
                                    <div class="skill-progress"
                                         data-progress="{{ $skill->percentage }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach

        </div>
    </section>



    <!-- Contact Section -->
    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <h2 class="section-title">Contact Me</h2>
        <div class="contact-container">
            @php
                $contact = \App\Models\ContactInfo::first();
            @endphp

            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ $contact->phone ?? '+91 9790168632' }}</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $contact->email ?? 'annagokul5@gmail.com' }}</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $contact->location ?? 'Erode, Tamil Nadu' }}</span>
                </div>
                <div class="social-links">
                    <a href="{{ $contact->whatsapp ?? 'https://wa.me/919790168632' }}"
                       class="social-btn" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="mailto:{{ $contact->email ?? 'annagokul5@gmail.com' }}"
                       class="social-btn" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a href="{{ $contact->linkedin ?? '#' }}" class="social-btn" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="{{ $contact->github ?? '#' }}" class="social-btn" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>

            <div class="contact-form">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form id="contactForm" method="POST" action="{{ route('contact.submit') }}">
{{--                    <p class="contact-text">Let's Connect</p>--}}
                    @csrf
                    <input type="text"  name="name"           placeholder="Your Name" required>
                    <input type="email" name="email"          placeholder="Your Email" required>
                    <input type="text"  name="contact_number" placeholder="Your Contact number">
                    <input type="text"  name="subject"        placeholder="Subject" required>
                    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>

        </div>
    </section>


    <!-- Footer -->
{{--    <footer class="footer">--}}
{{--        <div class="footer-links">--}}
{{--            <a href="#home">Home</a>--}}
{{--            <a href="#about">About</a>--}}
{{--            <a href="#projects">Projects</a>--}}
{{--            <a href="#skills">Skills</a>--}}
{{--            <a href="#contact">Contact</a>--}}
{{--        </div>--}}
{{--        <p class="copyright">&copy; {{ $footerYear }} Gokulraju All Rights Reserved.</p>--}}
{{--    </footer>--}}
@endsection
