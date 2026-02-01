// ===== THEME SWITCHER =====
const themeToggle = document.getElementById('themeToggle');
const themeToggleMobile = document.getElementById('themeToggleMobile');
const logoImg = document.getElementById('logoImg');
const body = document.body;
const root = document.documentElement;

// Logo paths
const darkLogo = '/images/gj.png';  // Dark theme logo
const lightLogo = '/images/gj1.png'; // Light theme logo

// Dark Theme Variables
const darkTheme = {
    '--bg-primary': '#1a0f0a',
    '--bg-secondary': '#0f0805',
    '--accent-yellow': '#ff6b35',
    '--accent-glow': 'rgba(255, 107, 53, 0.85)',
    '--text-primary': '#fff3e0',
    '--text-secondary': '#ffab91',
    '--glass-bg': 'rgba(26, 15, 10, 0.6)',
    '--glass-border': 'rgba(255, 107, 53, 0.2)',
    'body-bg': 'linear-gradient(135deg, #1a1714 0%, #070707 100%)'
};

// Light Theme Variables
const lightTheme = {
    '--bg-primary': '#ffffff',
    '--bg-secondary': '#fff7ed',
    '--accent-yellow': '#ea580c',
    '--accent-glow': 'rgba(234, 88, 12, 0.3)',
    '--text-primary': '#7c2d12',
    '--text-secondary': '#9a3412',
    '--glass-bg': 'rgba(255, 247, 237, 0.8)',
    '--glass-border': 'rgba(234, 88, 12, 0.3)',
    'body-bg': 'linear-gradient(135deg, #ffffff 0%, #fff7ed 100%)'
};

// Check for saved theme preference or default to dark
let currentTheme = localStorage.getItem('theme') || 'dark';

// Apply theme function
function applyTheme(theme) {
    const themeVars = theme === 'light' ? lightTheme : darkTheme;

    Object.keys(themeVars).forEach(key => {
        if (key === 'body-bg') {
            body.style.background = themeVars[key];
        } else {
            root.style.setProperty(key, themeVars[key]);
        }
    });

    // Update logo based on theme
    if (theme === 'light') {
        logoImg.src = lightLogo;
        body.classList.add('light-theme');
    } else {
        logoImg.src = darkLogo;
        body.classList.remove('light-theme');
    }

    // Update desktop toggle button
    const desktopSlider = themeToggle.querySelector('.theme-toggle-slider i');
    if (theme === 'light') {
        themeToggle.classList.add('active');
        desktopSlider.className = 'fas fa-sun';
    } else {
        themeToggle.classList.remove('active');
        desktopSlider.className = 'fas fa-moon';
    }

    // Update mobile toggle button
    const mobileSlider = themeToggleMobile.querySelector('.theme-toggle-slider-mobile i');
    if (theme === 'light') {
        themeToggleMobile.classList.add('active');
        mobileSlider.className = 'fas fa-sun';
    } else {
        themeToggleMobile.classList.remove('active');
        mobileSlider.className = 'fas fa-moon';
    }

    localStorage.setItem('theme', theme);
}

// Apply saved theme on load
applyTheme(currentTheme);

// Desktop theme toggle click event
themeToggle.addEventListener('click', () => {
    currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(currentTheme);
});

// Mobile theme toggle click event
themeToggleMobile.addEventListener('click', () => {
    currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(currentTheme);
});

// ===== NAVIGATION BAR =====
const navbar = document.getElementById('navbar');
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');
const navLinks = document.querySelectorAll('.nav-link');

// Navbar scroll effect
window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Hamburger menu toggle
hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close menu when clicking on nav links (but not on theme toggle)
navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        // Don't close menu if clicking on mobile theme toggle
        if (!e.target.closest('.theme-toggle-mobile')) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        }
    });
});

// ===== FLIP CARD =====
const flipCard = document.getElementById('flipCard');
const clickMeBtn = document.getElementById('clickMeBtn');

// clickMeBtn.addEventListener('click', () => {
//     flipCard.classList.toggle('flipped');
// });
if (clickMeBtn) {
    clickMeBtn.addEventListener('click', () => {
        flipCard.classList.toggle('flipped');
    });
}

// ===== SKILL BARS ANIMATION =====
const skillsSection = document.querySelector('.skills-section');
const skillProgress = document.querySelectorAll('.skill-progress');

const animateSkills = () => {
    const sectionPos = skillsSection.getBoundingClientRect().top;
    const screenPos = window.innerHeight / 1.3;

    if (sectionPos < screenPos) {
        skillProgress.forEach(skill => {
            const progress = skill.getAttribute('data-progress');
            skill.style.width = progress + '%';
        });
    }
};

window.addEventListener('scroll', animateSkills);

// ===== CONTACT FORM =====
// const contactForm = document.getElementById('contactForm');
//
// contactForm.addEventListener('submit', (e) => {
//     e.preventDefault();
//
//     const formData = new FormData(contactForm);
//     const name = formData.get('name');
//     const email = formData.get('email');
//     const subject = formData.get('subject');
//     const message = formData.get('message');
// });

// ===== SMOOTH SCROLL =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

// ===== INTERSECTION OBSERVER FOR ANIMATIONS =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

// Observe all sections
    document.querySelectorAll('section').forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(50px)';
        section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(section);
    });

// ===== INITIALIZE ON PAGE LOAD =====
    window.addEventListener('load', () => {
        animateSkills();
        const heroSection = document.querySelector('.hero-section');
        heroSection.style.opacity = '1';
        heroSection.style.transform = 'translateY(0)';
    });

// ===== SCRAMBLE TEXT EFFECT =====
    const typingText = document.querySelector('.typing-text');
    const texts = ['SOFTWARE DEVELOPER', 'WEB DEVELOPER', 'AI ENGINEER', 'GRAPHIC DESIGNER'];
    let textIndex = 0;

    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    function scrambleEffect() {
        const targetText = texts[textIndex];
        let iterations = 0;

        const interval = setInterval(() => {
            typingText.textContent = targetText
                .split('')
                .map((char, index) => {
                    if (char === ' ') return ' ';

                    if (index < iterations) {
                        return targetText[index];
                    }

                    return letters[Math.floor(Math.random() * 26)];
                })
                .join('');

            if (iterations >= targetText.length) {
                clearInterval(interval);

                // Wait 3 seconds then switch to next text
                setTimeout(() => {
                    textIndex = (textIndex + 1) % texts.length;
                    scrambleEffect();
                }, 2000);
            }

            iterations += 1 / 3;
        }, 30);
    }

// Start scramble effect when page loads
    window.addEventListener('load', () => {
        setTimeout(scrambleEffect, 1000);
    });

