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
                            <img src="{{ asset('images/real2.PNG') }}" alt="Real Image" >
                        </div>
                    </div>
                </div>
                <button class="click-me-btn" id="clickMeBtn">
                    <i class="fas fa-sync-alt"></i> Flip
                </button>
            </div>
            <div class="hero-content">
                <h1 class="hero-name">GOKULRAJU A</h1>
                <h2 class="hero-title">
                    ASPIRING <span class="typing-text"></span>
                </h2>

                <p class="hero-objective">
                    Enthusiastic AI and Data Science student passionate about SaaS development and real-world innovation.
                    Skilled in web technologies, I build scalable, user-centric solutions while continuously adapting to
                    evolving cloud environments.
                </p>
                <a href="{{ asset('resume/resume.pdf') }}" download="Gokulraju_Resume.pdf" class="download-resume-btn">
                    <i class="fas fa-download"></i>
                    <span>Download Resume</span>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <h2 class="section-title">About Me</h2>
        <div class="about-container">
            <div class="about-content">
                <p>
                    I'm Gokulraju A, a B.Tech student specializing in Artificial Intelligence & Data Science at
                    Kangeyam Institute of Technology. I'm passionate about leveraging technology
                    to create innovative solutions.
                </p>
                <p>
                    My journey in software development began with a keen interest in web technologies. I've completed
                    internships at Boostability and Maruthi Computer Accessories, where I gained hands-on experience
                    in frontend and backend development, system setup, and troubleshooting.
                </p>
                <p>
                    I'm proficient in multiple programming languages and frameworks including Python, JavaScript,
                    Laravel, and Flutter. I'm constantly learning and adapting to new technologies to build scalable,
                    user-centric applications that make a difference.
                </p>
            </div>
            <div class="about-image-container">
                <div class="about-image-frame">
                    <img src="{{ asset('images/real1.png') }}" alt="Gokulraju A" class="about-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="projects-section">
        <h2 class="section-title">Projects</h2>
        <div class="projects-container">
            <!-- E-Commerce Project -->
            <div class="project-card">
                <div class="project-badge ongoing">Ongoing Project</div>
                <h3 class="project-title">E-Commerce Web Application</h3>
                <p class="project-description">
                    Built using HTML, CSS, JavaScript, Bootstrap, Laravel. Involved in UI development,
                    responsive design, and backend logic integration. A complete online shopping platform
                    with user authentication and product management.
                </p>
                <div class="project-buttons">
                    <a href="{{ url('/project/ecommerce') }}" class="project-btn" title="View Project">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="" class="project-btn" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>

            <!-- Industry Dialer Project -->
            <div class="project-card">
                <h3 class="project-title">KIT Connect</h3>
                <p class="project-description">
                    Building a smart dialer application using Dart, Flutter, and Android Studio. Utilizing AI-powered
                    tools to support design, code generation, and development efficiency. Implementing backend logic
                    for call handling and application functionality.
                </p>
                <div class="project-buttons">
                    <a href="{{ url('/project/kitconnect') }}" class="project-btn" title="View Project">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ url('/project/kitconnect') }}" class="project-btn" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="project-btn" title="Play Store">
                        <i class="fab fa-google-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="skills-section">
        <h2 class="section-title">Skills & Tools</h2>
        <div class="skills-container">
            <!-- Programming Skills -->
            <div class="skill-card">
                <h3 class="skill-title">Programming</h3>
                <div class="skill-item">
                    <span class="skill-name">Python</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="80"></div>
                    </div>
                </div>
                <div class="skill-item">
                    <span class="skill-name">JavaScript</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="85"></div>
                    </div>
                </div>
            </div>

            <!-- Frameworks Skills -->
            <div class="skill-card">
                <h3 class="skill-title">Frameworks</h3>
                <div class="skill-item">
                    <span class="skill-name">Laravel</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="75"></div>
                    </div>
                </div>
                <div class="skill-item">
                    <span class="skill-name">Flutter</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="70"></div>
                    </div>
                </div>
                <div class="skill-item">
                    <span class="skill-name">Bootstrap</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="85"></div>
                    </div>
                </div>
            </div>

            <!-- Web Technologies -->
            <div class="skill-card">
                <h3 class="skill-title">Web Technologies</h3>
                <div class="skill-item">
                    <span class="skill-name">HTML</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="90"></div>
                    </div>
                </div>
                <div class="skill-item">
                    <span class="skill-name">CSS</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="85"></div>
                    </div>
                </div>
            </div>

            <!-- Tools -->
            <div class="skill-card">
                <h3 class="skill-title">Tools</h3>
                <div class="skill-item">
                    <span class="skill-name">Visual Studio Code</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="90"></div>
                    </div>
                </div>
                <div class="skill-item">
                    <span class="skill-name">Git / GitHub</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="80"></div>
                    </div>
                </div>
                <div class="skill-item">
                    <span class="skill-name">Android Studio</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="75"></div>
                    </div>
                </div>
                <div class="skill-item">
                    <span class="skill-name">Photoshop</span>
                    <div class="skill-bar">
                        <div class="skill-progress" data-progress="70"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <h2 class="section-title">Contact Me</h2>
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>+91 9790168632</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>annagokul5@gmail.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Erode, Tamil Nadu</span>
                </div>
                <div class="social-links">
                    <a href="https://wa.me/919790168632" class="social-btn" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="mailto:annagokul5@gmail.com" class="social-btn" title="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a href="#" class="social-btn" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="social-btn" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
            <div class="contact-form">
                <form id="contactForm">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#projects">Projects</a>
            <a href="#skills">Skills</a>
            <a href="#contact">Contact</a>
        </div>
        <p class="copyright">&copy; 2025 Gokulraju All Rights Reserved.</p>
    </footer>
@endsection
