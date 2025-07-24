// main.js - Version fusionnée
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

    // 4. Slider Team (nouveau)
    function initTeamSlider() {
        const teamSlider = document.querySelector('.agency-slider');
        if (!teamSlider) return;

        const slides = document.querySelectorAll('.agency-slide');
        const dots = document.querySelectorAll('.pagination-dot');
        const prevBtn = document.querySelector('.slider-control.prev');
        const nextBtn = document.querySelector('.slider-control.next');
        const duration = 5000;
        
        let currentIndex = 0;
        let interval;

        function showSlide(index) {
            // Masquer tous les slides
            slides.forEach(slide => {
                slide.style.opacity = 0;
                slide.style.display = 'none';
            });
            
            // Afficher le slide courant avec animation
            slides[index].style.display = 'flex';
            gsap.to(slides[index], {
                opacity: 1,
                duration: 1,
                ease: "power2.inOut"
            });
            
            // Mettre à jour les dots
            dots.forEach(dot => dot.classList.remove('active'));
            dots[index].classList.add('active');
            
            currentIndex = index;
        }

        function nextSlide() {
            const newIndex = (currentIndex + 1) % slides.length;
            showSlide(newIndex);
        }

        function prevSlide() {
            const newIndex = (currentIndex - 1 + slides.length) % slides.length;
            showSlide(newIndex);
        }

        function startSlider() {
            clearInterval(interval);
            interval = setInterval(nextSlide, duration);
        }

        // Événements
        nextBtn.addEventListener('click', () => {
            clearInterval(interval);
            nextSlide();
            startSlider();
        });

        prevBtn.addEventListener('click', () => {
            clearInterval(interval);
            prevSlide();
            startSlider();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(interval);
                showSlide(index);
                startSlider();
            });
        });

        // Navigation au clavier
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') nextSlide();
            if (e.key === 'ArrowLeft') prevSlide();
        });

        // Initialisation
        showSlide(0);
        startSlider();

        // Pause au survol
        teamSlider.addEventListener('mouseenter', () => clearInterval(interval));
        teamSlider.addEventListener('mouseleave', startSlider);
    }

    // 5. Modal de rendez-vous (nouveau)
    function initAppointmentModal() {
        const modal = document.getElementById('appointmentModal');
        if (!modal) return;

        const form = document.getElementById('appointmentForm');
        
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const email = button.getAttribute('data-email');
            document.getElementById('memberEmail').value = email;
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Rendez-vous demandé avec succès!');
                    bootstrap.Modal.getInstance(modal).hide();
                    form.reset();
                } else {
                    alert('Erreur: ' + (data.message || 'Veuillez réessayer'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        });
    }

    // Initialisation globale
    initScrollReveal();
    initCarousel();
    setActiveMenu();
    initTeamSlider();
    initAppointmentModal();
});