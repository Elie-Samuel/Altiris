<?php require_once __DIR__ . '/partials/header.php'; ?>
    
    <!-- Main Content -->
    <main>

        <!-- À Propos Hero Section -->
        <section class="apropos-hero-section">
            <div class="container">
                <div class="row align-items-center min-vh-100">
                    <div class="col-lg-6">
                        <div class="apropos-hero-content">
                            <h1 class="apropos-hero-title"><?= htmlspecialchars($aproposDesc['titre'] ?? '') ?></h1>
                            <h2 class="apropos-hero-subtitle"><?= htmlspecialchars($aproposDesc['sous_titre'] ?? '') ?></h2>
                            <p class="apropos-hero-description">
                                <?= htmlspecialchars($aproposDesc['text'] ?? '') ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="apropos-hero-visual">
                            <div class="apropos-logo-3d">
                                <div class="logo-cube">
                                    <div class="logo-face front">A</div>
                                    <div class="logo-face back">L</div>
                                    <div class="logo-face right">T</div>
                                    <div class="logo-face left">I</div>
                                    <div class="logo-face top">R</div>
                                    <div class="logo-face bottom">Y</div>
                                    <div class="logo-face extra">S</div> <!-- Ajout de la lettre S -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="apropos-hero-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?= htmlspecialchars($yearsExperience) ?>+</span>
                        <span class="stat-label">Années d'expérience</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= htmlspecialchars($publishedPostsCount) ?>+</span>
                        <span class="stat-label">Articles publiés</span>
                    </div>
                <div class="stat-item">
                    <span class="stat-number"><?= htmlspecialchars($testimonialsCount) ?>+</span>
                    <span class="stat-label">Témoignages</span>
                </div>
                </div>
            </div>
        </section>

        <!-- Notre Histoire Section -->
        <section class="apropos-story-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="apropos-section-title">Notre Histoire</h2>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="apropos-story-content">
                            <div class="apropos-timeline">
                                <?php if (!empty($aproposHistory) && is_array($aproposHistory)): ?>
                                    <?php foreach ($aproposHistory as $item): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-year"><?= htmlspecialchars($item['year']) ?></div>
                                            <div class="timeline-content">
                                                <h4><?= htmlspecialchars($item['titre']) ?></h4>
                                                <p><?= htmlspecialchars($item['text']) ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Pas de données par défaut, section vide si aucune donnée -->
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="apropos-story-visual">
                            <div class="story-image-container">
                                <img src="<?= htmlspecialchars($altirysInfo['image_path'] ?? '/Altiris/assets/images/logo_Altirys.png') ?>" alt="Notre Histoire" class="story-image" onerror="this.onerror=null;this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                                <div class="story-overlay">
                                    <i class="fas fa-history"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Notre Mission Section -->
        <section class="apropos-mission-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="apropos-section-title">Notre Mission</h2>
                    </div>
                </div>
                <div class="row">
                    <?php if (!empty($aproposMission) && is_array($aproposMission)): ?>
                        <?php foreach ($aproposMission as $index => $mission): ?>
                            <div class="col-lg-4">
                                <div class="mission-card">
                                    <div class="mission-icon">
                                        <i class="<?= htmlspecialchars($mission['icon_bootstrap']) ?>"></i>
                                    </div>
                                    <h4 class="mission-title"><?= htmlspecialchars($mission['titre']) ?></h4>
                                    <p class="mission-description">
                                        <?= htmlspecialchars($mission['text']) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Section vide si aucune donnée -->
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Nos Valeurs Section -->
        <section class="apropos-values-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="apropos-section-title">Nos Valeurs</h2>
                    </div>
                </div>
                <div class="row">
                    <?php if (!empty($aproposValues) && is_array($aproposValues)): ?>
                        <?php foreach ($aproposValues as $index => $value): ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="value-card">
                                    <div class="value-icon">
                                        <i class="<?= htmlspecialchars($value['icon_bootstrap']) ?>"></i>
                                    </div>
                                    <h5 class="value-title"><?= htmlspecialchars($value['titre']) ?></h5>
                                    <p class="value-description"><?= htmlspecialchars($value['text']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Section vide si aucune donnée -->
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- CTA Section -->
        <section class="apropos-cta-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="cta-title">Prêt à transformer vos idées en réalité ?</h2>
                        <p class="cta-description">Contactez-nous dès aujourd'hui pour discuter de votre projet</p>
                        <div class="cta-buttons">
                            <a href="/Altiris/contact" class="btn-cta primary">
                                <span>Nous contacter</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="/Altiris/team" class="btn-cta secondary">
                                <span>Notre équipe</span>
                                <i class="fas fa-users"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Altiris/assets/gsap.min.js"></script>
    <script>
        // GSAP Animations
        gsap.registerPlugin(ScrollTrigger);

        // Hero animations
        gsap.timeline()
            .from('.apropos-hero-title', { duration: 1, y: 50, opacity: 0, ease: 'power3.out' })
            .from('.apropos-hero-subtitle', { duration: 1, y: 30, opacity: 0, ease: 'power3.out' }, '-=0.5')
            .from('.apropos-hero-description', { duration: 1, y: 30, opacity: 0, ease: 'power3.out' }, '-=0.3')
            .from('.apropos-hero-stats .stat-item', { duration: 0.8, y: 30, opacity: 0, stagger: 0.1, ease: 'power3.out' }, '-=0.5');

        // Timeline animation
        gsap.utils.toArray('.timeline-item').forEach((item, index) => {
            gsap.from(item, {
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                },
                duration: 1,
                x: index % 2 === 0 ? -50 : 50,
                opacity: 0,
                ease: 'power3.out'
            });
        });

        // Cards animations
        gsap.utils.toArray('.mission-card, .value-card').forEach(card => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 80%',
                },
                duration: 1,
                y: 50,
                opacity: 0,
                ease: 'power3.out'
            });
        });

        // Tech items animation
        gsap.utils.toArray('.tech-item').forEach((item, index) => {
            gsap.from(item, {
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                },
                duration: 0.8,
                scale: 0,
                opacity: 0,
                delay: index * 0.1,
                ease: 'back.out(1.7)'
            });
        });

        // 3D Logo rotation
        const logoCube = document.querySelector('.logo-cube');
        if (logoCube) {
            gsap.to(logoCube, {
                duration: 20,
                rotationX: 360,
                rotationY: 360,
                repeat: -1,
                ease: 'none'
            });
        }
    </script>

