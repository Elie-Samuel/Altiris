<footer class="altirys-footer">
    <div class="container">
        <div class="row">
            <!-- Brand Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-brand">
                    <img src="/Altiris/Assets/Images/logo_Altirys.png" alt="Logo ALTIRYS" class="footer-logo">
                    <h3 class="footer-brand-name">ALTIRYS</h3>
                    <p class="footer-tagline">Votre partenaire digital de confiance</p>
                    <p class="footer-description">
                        Agence numérique spécialisée en développement web & mobile, design, SEO, jeux vidéo, logiciels et hébergement.
                    </p>
                    <div class="footer-social">
                        <?php if (!empty($socialLinks) && is_array($socialLinks)): ?>
                            <?php foreach ($socialLinks as $link): ?>
                                <a href="<?= htmlspecialchars($link['url']) ?>" aria-label="<?= htmlspecialchars($link['platform']) ?>" target="_blank">
                                    <i class="<?= htmlspecialchars($link['icon_class']) ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Liens par défaut si aucune donnée -->
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Services Section -->
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-section">
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-code"></i> Développement Web</a></li>
                        <li><a href="#"><i class="fas fa-mobile-alt"></i> Applications Mobile</a></li>
                        <li><a href="#"><i class="fas fa-gamepad"></i> Jeux Vidéo</a></li>
                        <li><a href="#"><i class="fas fa-paint-brush"></i> Design UI/UX</a></li>
                        <li><a href="#"><i class="fas fa-search"></i> SEO & Marketing</a></li>
                        <li><a href="#"><i class="fas fa-server"></i> Hébergement</a></li>
                    </ul>
                </div>
            </div>

            <!-- Company Section -->
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-section">
                    <h5>Entreprise</h5>
                    <ul class="footer-links">
                        <li><a href="/Altiris/root/?page=apropos"><i class="fas fa-info-circle"></i> À propos</a></li>
                        <li><a href="/Altiris/root/?page=team"><i class="fas fa-users"></i> Notre équipe</a></li>
                        <li><a href="#"><i class="fas fa-briefcase"></i> Carrières</a></li>
                        <li><a href="/Altiris/root/?page=blog"><i class="fas fa-newspaper"></i> Blog</a></li>
                        <li><a href="#"><i class="fas fa-award"></i> Réalisations</a></li>
                        <li><a href="/Altiris/root/?page=contact"><i class="fas fa-envelope"></i> Contact</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-section">
                    <h5>Contact</h5>
                    <ul class="footer-contact-info">
                        <?php if (isset($contactInfo) && $contactInfo) : ?>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($contactInfo['adresse'] ?? 'Adresse non renseignée') ?></span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span><?= htmlspecialchars($contactInfo['phonne'] ?? '+33 1 23 45 67 89') ?></span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span><?= htmlspecialchars($contactInfo['mail'] ?? 'contact@altirys.com') ?></span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Lun - Ven: 9h00 - 18h00</span>
                            </li>
                            <?php if (!empty($contactInfo['lien_facebook'])) : ?>
                            <li>
                                <i class="fab fa-facebook-f"></i>
                                <a href="<?= htmlspecialchars($contactInfo['lien_facebook']) ?>" target="_blank">
                                    Nous suivre sur Facebook
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php else : ?>
                            <!-- Valeurs par défaut -->
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Rue de la Technologie<br>75001 Paris, France</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span>+33 1 23 45 67 89</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>contact@altirys.com</span>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Lun - Ven: 9h00 - 18h00</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="footer-copyright">
                    &copy; <?= date('Y') ?> ALTIRYS. Tous droits réservés.
                </div>
                <ul class="footer-legal">
                    <li><a href="#">Mentions légales</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                    <li><a href="#">Plan du site</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/Altiris/Assets/slick.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Gestion du scroll navbar
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar-uf');
    const content = document.querySelector('.content-offset');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
        content.style.paddingTop = '90px';
    } else {
        navbar.classList.remove('scrolled');
        content.style.paddingTop = '120px';
    }
});

// Dark Mode Toggle
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const body = document.body;
    
    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        darkModeIcon.className = 'fas fa-sun';
    }
    
    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            darkModeIcon.className = 'fas fa-sun';
            localStorage.setItem('darkMode', 'enabled');
        } else {
            darkModeIcon.className = 'fas fa-moon';
            localStorage.setItem('darkMode', 'disabled');
        }
    });
    
    // Animation for elements when they come into view
    const animateElements = document.querySelectorAll('.fade-in');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = 1;
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    animateElements.forEach(el => {
        el.style.opacity = 0;
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });
});

// Newsletter form handling
if (document.querySelector('.newsletter-form')) {
    document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('.newsletter-input').value;
        
        if (email) {
            alert('Merci pour votre inscription à notre newsletter !');
            this.querySelector('.newsletter-input').value = '';
        }
    });
}

// Smooth scroll for footer links
document.querySelectorAll('.footer-links a[href^="#"]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>

</body>
</html>