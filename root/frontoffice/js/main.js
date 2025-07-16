// main.js - Version Background Plein Écran
document.addEventListener('DOMContentLoaded', function() {
    // 1. Animation au scroll (conservée)
    function initScrollReveal() {
        const reveals = document.querySelectorAll(".reveal");
        
        function reveal() {
            for (let i = 0; i < reveals.length; i++) {
                const windowHeight = window.innerHeight;
                const elementTop = reveals[i].getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                } else {
                    reveals[i].classList.remove("active");
                }
            }
        }

        window.addEventListener("scroll", reveal);
        reveal();
    }

    // 2. Carousel Bootstrap (conservé)
    function initCarousel() {
        const myCarousel = document.querySelector('#mainCarousel');
        if (myCarousel) {
            new bootstrap.Carousel(myCarousel, {
                interval: 3000,
                ride: 'carousel'
            });
        }
    }

    // 3. Menu actif (conservé)
    function setActiveMenu() {
        document.querySelectorAll('.nav-link').forEach(link => {
            if(link.href === window.location.href) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            }
        });
    }

    // 4. NOUVEAU - Slider Background Plein Écran
    function initBackgroundSlider() {
        const testimonialSection = document.querySelector('.testimonial-background');
        if (!testimonialSection) return;

        const slides = testimonialSection.querySelectorAll('.testimonial-slide');
        const texts = testimonialSection.querySelectorAll('.testimonial-text');
        const dots = testimonialSection.querySelectorAll('.testimonial-dot');
        const duration = 7000; // Durée entre chaque transition

        let currentIndex = 0;
        let interval;

        function showSlide(index) {
            // Masquer tous les slides et textes
            slides.forEach(slide => slide.classList.remove('active'));
            texts.forEach(text => text.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            // Afficher le slide courant
            slides[index].classList.add('active');
            texts[index].classList.add('active');
            dots[index].classList.add('active');
            
            // Animation d'entrée
            gsap.from(texts[index], {
                opacity: 0,
                y: 50,
                duration: 1,
                ease: "power3.out"
            });
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        }

        function startSlider() {
            clearInterval(interval);
            interval = setInterval(nextSlide, duration);
        }

        // Navigation par points
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index;
                showSlide(currentIndex);
                startSlider();
            });
        });

        // Pause au survol
        testimonialSection.addEventListener('mouseenter', () => clearInterval(interval));
        testimonialSection.addEventListener('mouseleave', startSlider);

        // Initialisation
        showSlide(0);
        startSlider();
    }

    // Initialisation globale
    initScrollReveal();
    initCarousel();
    setActiveMenu();
    initBackgroundSlider();
});

document.addEventListener('DOMContentLoaded', function() {
    const announcements = document.querySelectorAll('.announcement-item');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentIndex = 0;
    let autoScrollInterval;
    let isAnimating = false;

    // Initialisation des animations
    function initAnimations() {
        gsap.set('.announcement-line', {
            y: 30,
            opacity: 0
        });
    }

    // Affiche une annonce avec animations
    function showAnnouncement(index) {
        if (isAnimating || index === currentIndex) return;
        isAnimating = true;

        const currentAnn = announcements[currentIndex];
        const nextAnn = announcements[index];

        // Animation de sortie
        gsap.to(currentAnn, {
            opacity: 0,
            duration: 0.8,
            ease: "power2.inOut"
        });

        // Animation d'entrée
        gsap.fromTo(nextAnn, 
            { opacity: 0 },
            { 
                opacity: 1,
                duration: 1,
                ease: "power2.inOut",
                onComplete: () => {
                    currentIndex = index;
                    isAnimating = false;
                    updateDots();
                    animateContent();
                }
            }
        );
    }

    // Animation du contenu
    function animateContent() {
        const activeContent = announcements[currentIndex].querySelector('.announcement-content');
        const lines = activeContent.querySelectorAll('.announcement-line');
        
        gsap.to(activeContent, {
            y: 0,
            opacity: 1,
            duration: 0.8
        });
        
        gsap.to(lines, {
            y: 0,
            opacity: 1,
            duration: 0.6,
            stagger: 0.2,
            ease: "back.out"
        });
    }

    // Mise à jour des indicateurs
    function updateDots() {
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }

    // Annonce suivante
    function nextAnnouncement() {
        const newIndex = (currentIndex + 1) % announcements.length;
        showAnnouncement(newIndex);
    }

    // Annonce précédente
    function prevAnnouncement() {
        const newIndex = (currentIndex - 1 + announcements.length) % announcements.length;
        showAnnouncement(newIndex);
    }

    // Auto-défilement
    function startAutoScroll() {
        autoScrollInterval = setInterval(nextAnnouncement, 6000);
    }

    function stopAutoScroll() {
        clearInterval(autoScrollInterval);
    }

    // Événements
    nextBtn.addEventListener('click', () => {
        stopAutoScroll();
        nextAnnouncement();
        startAutoScroll();
    });

    prevBtn.addEventListener('click', () => {
        stopAutoScroll();
        prevAnnouncement();
        startAutoScroll();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.dataset.index);
            if (index !== currentIndex) {
                stopAutoScroll();
                showAnnouncement(index);
                startAutoScroll();
            }
        });
    });

    // Navigation au clavier
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') nextAnnouncement();
        if (e.key === 'ArrowLeft') prevAnnouncement();
    });

    // Initialisation
    initAnimations();
    showAnnouncement(0);
    startAutoScroll();

    // Pause au survol
    const section = document.querySelector('.announcements-section');
    section.addEventListener('mouseenter', stopAutoScroll);
    section.addEventListener('mouseleave', startAutoScroll);
});