<?php
require_once __DIR__.'/partials/header.php';
?>

<!-- <link href="/Altiris/root/frontoffice/css/contact.css" rel="stylesheet"> -->

<div class="Alt-particles-bg"></div>
<style>

    /* Particles Background */
    .Alt-particles-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    background: radial-gradient(circle at 20% 50%, rgba(17, 131, 237, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 132, 18, 0.1) 0%, transparent 50%),
                var(--background-color);
    }

    .Alt-particles-bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image:
        radial-gradient(circle at 25% 25%, var(--primary-color) 1px, transparent 1px),
        radial-gradient(circle at 75% 75%, var(--secondary-color) 1px, transparent 1px);
    background-size: 50px 50px;
    opacity: 0.1;
    animation: Alt-moveParticles 20s linear infinite;
    }

    @keyframes Alt-moveParticles {
    0% { transform: translateY(0) rotate(0deg); }
    100% { transform: translateY(-100px) rotate(360deg); }
    }

    /* Section Titles */
    .Alt-section-title {
    font-family: 'Orbitron', monospace;
    font-size: 3rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
    background: linear-gradient(to right, #1183ed, #ff8412);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-shadow: 0 0 20px rgba(17, 131, 237, 0.3);
    }

    .Alt-section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background: linear-gradient(to right, #1183ed, #ff8412);
    border-radius: 2px;
    }

    /* Contact Section */
    .Alt-contact-section {
    padding: 8rem 0 5rem;
    background: rgb(15 7 22);
    }

    /* Logos Section */
    .Alt-logos-section {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 30px 0;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    margin-bottom: 40px;
    flex-wrap: wrap;
    border: 1px solid rgba(17, 131, 237, 0.2);
    }

    .Alt-logo-item {
    font-size: 1.5em;
    font-weight: bold;
    color: #ccc;
    margin: 15px;
    opacity: 0.7;
    transition: all 0.3s ease;
    }

    .Alt-logo-item:hover {
    color: #1183ed;
    opacity: 1;
    transform: scale(1.1);
    }

    /* Contact Info */
    .contact-info {
    margin-bottom: 2rem;
    }

    .Alt-contact-title {
    font-family: 'Orbitron', monospace;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    background: linear-gradient(to right, #1183ed, #ff8412);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-shadow: 0 0 15px rgba(17, 131, 237, 0.3);
    }

    .Alt-contact-text {
    font-size: 1.1rem;
    color: #ccc;
    margin-bottom: 2rem;
    line-height: 1.8;
    }

    .Alt-contact-methods {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    }

    .Alt-contact-method {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 15px;
    border: 1px solid rgba(17, 131, 237, 0.2);
    transition: all 0.3s ease;
    }

    .Alt-contact-method:hover {
    transform: translateX(10px);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    background: rgba(17, 131, 237, 0.1);
    border-color: #1183ed;
    }

    .Alt-contact-method i {
    font-size: 1.5rem;
    color: #1183ed;
    text-shadow: 0 0 10px rgba(17, 131, 237, 0.3);
    }

    .Alt-contact-method span {
    color: #ccc;
    font-size: 1.1rem;
    }

    /* Contact Form */
    .Alt-contact-form {
    background: #1a1a2e;
    padding: 2.5rem;
    border-radius: 20px;
    border: 1px solid rgba(17, 131, 237, 0.2);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    }

    .Alt-form-group {
    margin-bottom: 1.5rem;
    }

    .Alt-form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    background: linear-gradient(to right, #1183ed, #ff8412);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-family: 'Orbitron', monospace;
    }

    .Alt-cyber-input {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid #1183ed;
    border-radius: 15px;
    padding: 1rem;
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    }

    .Alt-cyber-input:focus {
    outline: none;
    border-color: #ff8412;
    box-shadow: 0 0 15px rgba(17, 131, 237, 0.3);
    background: rgba(17, 131, 237, 0.1);
    }

    .Alt-cyber-input::placeholder {
    color: #ccc;
    opacity: 0.7;
    }

    .Alt-cyber-btn {
    position: relative;
    background: linear-gradient(to right, #1183ed, #ff8412);
    border: none;
    padding: 1rem 2rem;
    font-weight: 600;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 30px;
    overflow: hidden;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: white;
    font-family: 'Orbitron', monospace;
    }

    .Alt-cyber-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
    }

    .Alt-cyber-btn:hover::before {
    left: 100%;
    }

    .Alt-cyber-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(17, 131, 237, 0.4);
    color: white;
    }

    /* Newsletter Section */
    .Alt-newsletter-container {
    background: #1a1a2e;
    padding: 2.5rem;
    border-radius: 20px;
    border: 1px solid rgba(17, 131, 237, 0.2);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    }

    .Alt-newsletter-container h3 {
    font-family: 'Orbitron', monospace;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(to right, #1183ed, #ff8412);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    }

    .Alt-newsletter-container p {
    color: #ccc;
    margin-bottom: 1.5rem;
    line-height: 1.6;
    }

    /* Map Section */
    .Alt-map-section {
    margin-top: 3rem;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    }

    #map {
    border-radius: 15px;
    }

    /* Alerts */
    .Alt-alert-error {
    background: rgba(220, 53, 69, 0.2);
    border: 1px solid #dc3545;
    color: #dc3545;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    }

    .Alt-alert-success {
    background: rgba(40, 167, 69, 0.2);
    border: 1px solid #28a745;
    color: #28a745;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    }

    /* Modal */
    .Alt-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    animation: fadeIn 0.3s;
    }

    .Alt-modal-content {
    background: #1a1a2e;
    margin: 15% auto;
    padding: 30px;
    border-radius: 15px;
    width: 80%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(17, 131, 237, 0.3);
    border: 1px solid rgba(17, 131, 237, 0.2);
    }

    .Alt-close-modal {
    color: #ccc;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    }

    .Alt-close-modal:hover {
    color: #1183ed;
    }

    .Alt-modal-icon {
    font-size: 50px;
    color: #1183ed;
    margin-bottom: 20px;
    text-shadow: 0 0 20px rgba(17, 131, 237, 0.5);
    }

    .Alt-modal h3 {
    font-family: 'Orbitron', monospace;
    background: linear-gradient(to right, #1183ed, #ff8412);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 15px;
    }

    @keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
    }

    /* Ajustement pour le contenu principal */
    main {
    padding-top: 100px; /* Ajustez selon la hauteur de votre header */
    }

    /* Responsive Design */
    @media (max-width: 992px) {
    .Alt-section-title {
        font-size: 2.5rem;
    }
    .Alt-contact-title {
        font-size: 2rem;
    }
    }

    @media (max-width: 768px) {
    .Alt-section-title {
        font-size: 2rem;
    }
    .Alt-contact-methods {
        margin-bottom: 2rem;
    }
    .Alt-contact-form,
    .Alt-newsletter-container {
        padding: 2rem;
    }
    .Alt-logos-section {
        justify-content: center;
    }
    .Alt-logo-item {
        margin: 10px;
        font-size: 1.2em;
    }
    }

    @media (max-width: 576px) {
    .Alt-section-title {
        font-size: 1.8rem;
    }
    .Alt-contact-form,
    .Alt-newsletter-container {
        padding: 1.5rem;
    }
    .Alt-contact-section {
        padding: 6rem 0 3rem;
    }
    .Alt-brand-logo {
        font-size: 1.5rem;
    }
    .Alt-brand-logo .logo-text {
        display: none;
    }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
    width: 8px;
    }

    ::-webkit-scrollbar-track {
    background: rgb(15 7 22);
    }

    ::-webkit-scrollbar-thumb {
    background: #1183ed;
    border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
    background: #ff8412;
    }
</style>
<!-- Contact Section -->
<main>
    <section id="contact" class="Alt-contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="Alt-section-title">Contact</h2>
                </div>
            </div>
            
            <!-- Logos Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="Alt-logos-section">
                        <div class="Alt-logo-item"><?= htmlspecialchars($pageSettings['logo_1_text'] ?? 'LOGOIPSUM') ?></div>
                        <div class="Alt-logo-item"><?= htmlspecialchars($pageSettings['logo_2_text'] ?? 'LOGOIPSUM') ?></div>
                        <div class="Alt-logo-item"><?= htmlspecialchars($pageSettings['logo_3_text'] ?? 'LOGOIPSUM') ?></div>
                        <div class="Alt-logo-item"><?= htmlspecialchars($pageSettings['logo_4_text'] ?? 'LOGOIPSUM') ?></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h3 class="Alt-contact-title">Prêt à créer l'avenir ensemble ?</h3>
                        <p class="Alt-contact-text">
                            <?= htmlspecialchars($pageSettings['hero_description'] ?? 'Contactez-nous pour discuter de votre projet et découvrir comment ALTIRYS peut transformer vos idées en réalité digitale.') ?>
                        </p>
                        <div class="Alt-contact-methods">
                            <div class="Alt-contact-method">
                                <i class="fas fa-envelope"></i>
                                <span><?= htmlspecialchars($contactInfo['mail'] ?? 'contact@altirys.com') ?></span>
                            </div>
                            <div class="Alt-contact-method">
                                <i class="fas fa-phone"></i>
                                <span><?= htmlspecialchars($contactInfo['phonne'] ?? '+33 1 23 45 67 89') ?></span>
                            </div>
                            <div class="Alt-contact-method">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($contactInfo['adresse'] ?? 'Paris, France') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger Alt-alert-error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success Alt-alert-success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    
                    <form class="Alt-contact-form" method="POST" action="/Altiris/root/?page=contact&action=submit">
                        <div class="Alt-form-group">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control Alt-cyber-input" id="nom" name="name" placeholder="Votre nom" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div>
                        <div class="Alt-form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control Alt-cyber-input" id="email" name="email" placeholder="Votre email" required
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="Alt-form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" class="form-control Alt-cyber-input" id="phone" name="tel" placeholder="Votre téléphone"
                                   value="<?= htmlspecialchars($_POST['tel'] ?? '') ?>">
                        </div>
                        <div class="Alt-form-group">
                            <label for="subject">Sujet</label>
                            <input type="text" class="form-control Alt-cyber-input" id="subject" name="subject" placeholder="Sujet de votre message"
                                   value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                        </div>
                        <div class="Alt-form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control Alt-cyber-input" id="message" name="message" rows="5" placeholder="Votre message" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary Alt-cyber-btn w-100">
                            <span>Envoyer le message</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="row mt-5">
                <div class="col-lg-6 offset-lg-6">
                    <div class="Alt-newsletter-container">
                        <h3><?= htmlspecialchars($pageSettings['newsletter_title'] ?? 'Notre Newsletter') ?></h3>
                        <p><?= htmlspecialchars($pageSettings['newsletter_description'] ?? 'Restez informé de nos dernières actualités et innovations.') ?></p>
                        <form class="Alt-newsletter-form">
                            <div class="Alt-form-group">
                                <input type="email" class="form-control Alt-cyber-input" placeholder="Votre email">
                            </div>
                            <button type="submit" class="btn Alt-cyber-btn w-100">
                                <span>S'abonner</span>
                                <i class="fas fa-bell"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
    <!-- Google Map Section -->
    <section class="Alt-map-section">
        <div class="container-fluid p-0">
            <div id="map" style="height: 450px; width: 100%;"></div>
        </div>
    </section>

    <!-- Modal de confirmation -->
    <?php if (isset($success) && $success): ?>
    <div id="contactModal" class="Alt-modal" style="display:block;">
        <div class="Alt-modal-content">
            <span class="Alt-close-modal">&times;</span>
            <div class="Alt-modal-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Message envoyé!</h3>
            <p><?= htmlspecialchars($message) ?></p>
        </div>
    </div>

    <script>
    // Gestion du modal
    const modal = document.getElementById("contactModal");
    const span = document.getElementsByClassName("Alt-close-modal")[0];

    // Fermer après 3 secondes
    setTimeout(() => {
        if (modal) modal.style.display = "none";
    }, 3000);

    // Fermer en cliquant sur la croix
    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    // Fermer en cliquant à l'extérieur
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
    <?php endif; ?>


<?php
require_once __DIR__.'/partials/footer.php';
?>