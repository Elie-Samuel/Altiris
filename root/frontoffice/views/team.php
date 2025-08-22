<?php
// altiris/root/frontoffice/views/team.php

// Les données ($pageTitle, $members) sont fournies par le contrôleur
$pageTitle = $pageTitle ?? 'Notre Équipe - ALTIRYS'; // Défaut si non fourni

require_once __DIR__ . '/partials/header.php';
?>

    <!-- Team Section -->
    <main>
        <section id="team" class="team-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="team-section-title">Notre Équipe</h2>
                        <p class="team-section-subtitle">Des professionnels passionnés à votre service</p>
                    </div>
                </div>
                
                <div class="team-slider-container">
                    <div class="team-slider">
                        <?php if (!empty($members)): ?>
                            <?php foreach ($members as $member): ?>
                            <div class="team-slide" data-member-id="<?= htmlspecialchars($member['id_membre'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="team-member-card">
                                    <div class="member-image-container">
                                        <div class="member-image" 
                                             style="background-image: url('<?= !empty($member['photo']) ? '/Altiris/' . htmlspecialchars($member['photo'], ENT_QUOTES, 'UTF-8') : '/Altiris/Assets/Images/default-profile.jpg' ?>')">
                                        </div>
                                        <div class="member-overlay">
                                            <div class="member-social">
                                                <?php if (!empty($member['email'])): ?>
                                                <a href="mailto:<?= htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8') ?>" class="social-link">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($member['Tel'])): ?>
                                                <a href="tel:<?= htmlspecialchars($member['Tel'], ENT_QUOTES, 'UTF-8') ?>" class="social-link">
                                                    <i class="fas fa-phone"></i>
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($member['lien_facebook'])): ?>
                                                <a href="<?= htmlspecialchars($member['lien_facebook'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="social-link">
                                                    <i class="fab fa-facebook"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="member-content">
                                        <div class="member-role"><?= htmlspecialchars($member['role'] ?? 'UTILISATEUR', ENT_QUOTES, 'UTF-8') ?></div>
                                        <h3 class="member-name"><?= htmlspecialchars($member['prenom'] . ' ' . $member['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
                                        
                                        <?php if (!empty($member['competce_mbr'])): ?>
                                        <p class="member-description"><?= htmlspecialchars($member['competce_mbr'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="member-contact-info">
                                            <?php if (!empty($member['email'])): ?>
                                            <div class="contact-item">
                                                <i class="fas fa-envelope"></i>
                                                <span><?= htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($member['Tel'])): ?>
                                            <div class="contact-item">
                                                <i class="fas fa-phone"></i>
                                                <span><?= htmlspecialchars($member['Tel'], ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($member['lien_facebook'])): ?>
                                            <div class="contact-item">
                                                <i class="fab fa-facebook"></i>
                                                <span>Profil Facebook</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (!empty($member['email'])): ?>
                                        <button class="btn-contact open-contact-modal" 
                                                data-member="<?= htmlspecialchars($member['prenom'] . ' ' . $member['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                                data-email="<?= htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8') ?>"
                                                data-member-id="<?= htmlspecialchars($member['id_membre'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fas fa-paper-plane"></i>
                                            <span>Contacter</span>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="team-slide">
                                <div class="no-members">
                                    <i class="fas fa-users"></i>
                                    <h3>Aucun membre trouvé</h3>
                                    <p>L'équipe sera bientôt disponible.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($members) && count($members) > 1): ?>
                    <div class="slider-navigation">
                        <button class="slider-btn prev-btn">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="slider-btn next-btn">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="slider-dots"></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Contact Modal -->
    <div class="contact-modal" id="contactModal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <div class="modal-header">
                <i class="fas fa-paper-plane modal-icon"></i>
                <h3>Contacter <span id="memberName"></span></h3>
            </div>
            <form id="contactForm" method="POST">
                <input type="hidden" name="member_email" id="memberEmail">
                <input type="hidden" name="member_name" id="memberNameHidden">
                
                <div class="form-group">
                    <label for="sender_name">Votre nom</label>
                    <input type="text" id="sender_name" name="sender_name" placeholder="Votre nom" required>
                </div>
                
                <div class="form-group">
                    <label for="sender_email">Votre email</label>
                    <input type="email" id="sender_email" name="sender_email" placeholder="Votre email" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Sujet</label>
                    <input type="text" id="subject" name="subject" placeholder="Sujet du message" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="4" placeholder="Votre message" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">
                    <span>Envoyer</span>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        let currentSlide = 0;
        const $slider = $('.team-slider');
        const $slides = $('.team-slide');
        const totalSlides = $slides.length;
        
        if (totalSlides > 1) {
            // Créer les points de navigation
            function createDots() {
                for(let i = 0; i < totalSlides; i++) {
                    $('.slider-dots').append(`<span class="dot ${i === 0 ? 'active' : ''}" data-slide="${i}"></span>`);
                }
            }
            
            // Mise à jour du slider
            function updateSlider() {
                $slider.css('transform', `translateX(${-currentSlide * 100}%)`);
                $('.dot').removeClass('active');
                $(`.dot[data-slide="${currentSlide}"]`).addClass('active');
            }
            
            // Suivante
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            }
            
            // Précédente
            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSlider();
            }
            
            // Événements
            $('.next-btn').click(nextSlide);
            $('.prev-btn').click(prevSlide);
            
            $(document).on('click', '.dot', function() {
                currentSlide = parseInt($(this).data('slide'));
                updateSlider();
            });
            
            // Initialisation
            createDots();
            updateSlider();
        }
        
        // Contact modal functionality
        $('.open-contact-modal').click(function(){
            const memberName = $(this).data('member');
            const memberEmail = $(this).data('email');
            
            $('#memberName').text(memberName);
            $('#memberEmail').val(memberEmail);
            $('#memberNameHidden').val(memberName);
            
            $('#contactModal').fadeIn(300, function() {
                $('.modal-content').addClass('show');
            });
            $('body').addClass('modal-open');
        });
        
        $('.close-modal, .modal-overlay').click(function(){
            $('.modal-content').removeClass('show');
            setTimeout(function() {
                $('#contactModal').fadeOut(300);
                $('body').removeClass('modal-open');
            }, 300);
        });
        
        $('.modal-content').click(function(e){
            e.stopPropagation();
        });
        
        // AJAX form submission
        $('#contactForm').submit(function(e){
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                type: 'POST',
                url: '/Altiris/root/frontoffice/send_email.php',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $('.submit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Envoi...');
                },
                success: function(response) {
                    if (response.success) {
                        $('.modal-content').html(`
                            <div class="success-message">
                                <i class="fas fa-check-circle"></i>
                                <h3>Message envoyé!</h3>
                                <p>Votre message a été envoyé avec succès.</p>
                                <button class="close-success-btn">Fermer</button>
                            </div>
                        `);
                        
                        $('.close-success-btn').click(function() {
                            $('.modal-content').removeClass('show');
                            setTimeout(function() {
                                $('#contactModal').fadeOut(300);
                                $('body').removeClass('modal-open');
                                location.reload();
                            }, 300);
                        });
                    } else {
                        alert(response.message || 'Une erreur est survenue');
                        $('.submit-btn').prop('disabled', false).html('<span>Envoyer</span><i class="fas fa-paper-plane"></i>');
                    }
                },
                error: function() {
                    alert('Une erreur est survenue. Veuillez réessayer.');
                    $('.submit-btn').prop('disabled', false).html('<span>Envoyer</span><i class="fas fa-paper-plane"></i>');
                }
            });
        });

        // Fonction pour faire défiler vers un membre (pour la recherche)
        window.scrollToMember = function(memberId) {
            const $memberElement = $(`[data-member-id="${memberId}"]`);
            if ($memberElement.length) {
                const slideIndex = $memberElement.index();
                currentSlide = slideIndex;
                updateSlider();
                $memberElement.addClass('highlight');
                setTimeout(() => $memberElement.removeClass('highlight'), 2000);
            }
        };
    });
    </script>
