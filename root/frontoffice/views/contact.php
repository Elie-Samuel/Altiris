<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- Contact Section -->
<main>
    <section id="contact" class="Alt-contact-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="Alt-section-title">Contact</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="Alt-contact-info">
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
                                <span><?= htmlspecialchars($contactInfo['phonne'] ?? '+33 1 23 45 67 89') ?></span> <!-- Correction : 'phonne' -> 'phone' si typo -->
                            </div>
                            <div class="Alt-contact-method">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($contactInfo['adresse'] ?? 'Paris, France') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger Alt-alert-error">
                            <?= nl2br(htmlspecialchars($error)) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success Alt-alert-success">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulaire avec reCAPTCHA -->
                    <form class="Alt-contact-form" method="POST" action="/Altiris/root/?page=contact&action=submit">
                        <div class="Alt-form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" class="form-control Alt-cyber-input" id="nom" name="name" 
                                   value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required maxlength="50">
                        </div>
                        
                        <div class="Alt-form-group">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control Alt-cyber-input" id="email" name="email"
                                   value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required maxlength="50">
                        </div>
                        
                        <div class="Alt-form-group">
                            <label for="phone">Téléphone</label>
                            <div class="input-group">
                                <select class="form-control Alt-cyber-input" id="phone_prefix" name="phone_prefix" style="max-width: 100px;">
                                    <option value="+33" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+33') ? 'selected' : '' ?>>+33 (France)</option>
                                    <option value="+1" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+1') ? 'selected' : '' ?>>+1 (USA/Canada)</option>
                                    <option value="+44" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+44') ? 'selected' : '' ?>>+44 (Royaume-Uni)</option>
                                    <option value="+49" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+49') ? 'selected' : '' ?>>+49 (Allemagne)</option>
                                    <option value="+81" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+81') ? 'selected' : '' ?>>+81 (Japon)</option>
                                    <option value="+86" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+86') ? 'selected' : '' ?>>+86 (Chine)</option>
                                    <option value="+91" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+91') ? 'selected' : '' ?>>+91 (Inde)</option>
                                    <option value="+254" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+254') ? 'selected' : '' ?>>+254 (Kenya)</option>

                                    <!-- Autres préfixes fréquents -->
                                    <option value="+213" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+213') ? 'selected' : '' ?>>+213 (Algérie)</option>
                                    <option value="+216" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+216') ? 'selected' : '' ?>>+216 (Tunisie)</option>
                                    <option value="+212" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+212') ? 'selected' : '' ?>>+212 (Maroc)</option>
                                    <option value="+225" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+225') ? 'selected' : '' ?>>+225 (Côte d'Ivoire)</option>
                                    <option value="+261" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+261') ? 'selected' : '' ?>>+261 (Madagascar)</option>
                                    <option value="+27" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+27') ? 'selected' : '' ?>>+27 (Afrique du Sud)</option>
                                    <option value="+971" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+971') ? 'selected' : '' ?>>+971 (Émirats Arabes Unis)</option>
                                    <option value="+966" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+966') ? 'selected' : '' ?>>+966 (Arabie Saoudite)</option>
                                    <option value="+962" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+962') ? 'selected' : '' ?>>+962 (Jordanie)</option>
                                    <option value="+60" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+60') ? 'selected' : '' ?>>+60 (Malaisie)</option>
                                    <option value="+62" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+62') ? 'selected' : '' ?>>+62 (Indonésie)</option>
                                    <option value="+63" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+63') ? 'selected' : '' ?>>+63 (Philippines)</option>
                                    <option value="+64" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+64') ? 'selected' : '' ?>>+64 (Nouvelle-Zélande)</option>
                                    <option value="+65" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+65') ? 'selected' : '' ?>>+65 (Singapour)</option>
                                    <option value="+7" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+7') ? 'selected' : '' ?>>+7 (Russie/Kazakhstan)</option>
                                    <option value="+351" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+351') ? 'selected' : '' ?>>+351 (Portugal)</option>
                                    <option value="+34" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+34') ? 'selected' : '' ?>>+34 (Espagne)</option>
                                    <option value="+39" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+39') ? 'selected' : '' ?>>+39 (Italie)</option>
                                    <option value="+41" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+41') ? 'selected' : '' ?>>+41 (Suisse)</option>
                                    <option value="+55" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+55') ? 'selected' : '' ?>>+55 (Brésil)</option>
                                    <option value="+52" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+52') ? 'selected' : '' ?>>+52 (Mexique)</option>
                                    <option value="+20" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+20') ? 'selected' : '' ?>>+20 (Égypte)</option>
                                    <option value="+90" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+90') ? 'selected' : '' ?>>+90 (Turquie)</option>
                                    <option value="+94" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+94') ? 'selected' : '' ?>>+94 (Sri Lanka)</option>
                                    <option value="+92" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+92') ? 'selected' : '' ?>>+92 (Pakistan)</option>
                                    <option value="+880" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+880') ? 'selected' : '' ?>>+880 (Bangladesh)</option>
                                    <option value="+98" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+98') ? 'selected' : '' ?>>+98 (Iran)</option>
                                    <option value="+853" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+853') ? 'selected' : '' ?>>+853 (Macao)</option>
                                    <option value="+852" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+852') ? 'selected' : '' ?>>+852 (Hong Kong)</option>
                                    <option value="+353" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+353') ? 'selected' : '' ?>>+353 (Irlande)</option>
                                    <option value="+354" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+354') ? 'selected' : '' ?>>+354 (Islande)</option>
                                    <option value="+47" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+47') ? 'selected' : '' ?>>+47 (Norvège)</option>
                                    <option value="+46" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+46') ? 'selected' : '' ?>>+46 (Suède)</option>
                                    <option value="+45" <?= (isset($formData['phone_prefix']) && $formData['phone_prefix'] == '+45') ? 'selected' : '' ?>>+45 (Danemark)</option>

                                </select>
                                <input type="tel" class="form-control Alt-cyber-input" id="phone_number" name="phone_number"
                                       value="<?= htmlspecialchars($formData['phone_number'] ?? '') ?>" maxlength="13" placeholder="Numéro local">
                            </div>
                        </div>
                        
                        <div class="Alt-form-group">
                            <label for="subject">Sujet *</label>
                            <input type="text" class="form-control Alt-cyber-input" id="subject" name="subject"
                                   value="<?= htmlspecialchars($formData['subject'] ?? '') ?>" required maxlength="100">
                        </div>
                        
                        <div class="Alt-form-group">
                            <label for="message">Message *</label>
                            <textarea class="form-control Alt-cyber-input" id="message" name="message" 
                                      rows="5" required maxlength="225"><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- reCAPTCHA v2 Widget -->
                        <div class="Alt-form-group mb-4">
                            <div class="g-recaptcha" data-sitekey="6LdaY6crAAAAAGFlkSqhCqqxDCFw7r-P7SBAHlS5"></div>
                            <small class="text-muted">Cette vérification nous aide à lutter contre le spam.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary Alt-cyber-btn w-100">
                            <span>Envoyer le message</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Carte Google Maps -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="Alt-map-card">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3713.993352496795!2d47.11163617526773!3d-21.429506280321924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xa91cc6efe9853a47%3A0xd5c0f6517333027c!2sAltyris%20Madagascar!5e0!3m2!1sfr!2smg!4v1755192936991!5m2!1sfr!2smg" 
                                width="100%" height="450" style="border:0; border-radius:20px;" allowfullscreen="" 
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Inclure le script reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("contactModal");
    if (modal) {
        // Fermer après 3 secondes
        setTimeout(() => {
            modal.style.display = "none";
        }, 3000);

        // Fermer en cliquant sur la croix
        const span = modal.querySelector(".Alt-close-modal");
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
    }
});
</script>
<?php endif; ?>