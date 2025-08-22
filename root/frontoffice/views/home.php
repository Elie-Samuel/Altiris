<?php
// altiris/root/frontoffice/views/home.php

// Affichage des erreurs en DEV uniquement (à retirer en prod)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir le titre de la page
$pageTitle = $pageTitle ?? 'ALTIRYS - Votre partenaire digital de confiance';

// Inclure le header
require_once __DIR__ . '/partials/header.php';

// Debug (à retirer en prod)
error_log("Debug: [home.php] chargé");
error_log("Announcement data: " . json_encode($announcement));
?>

<!-- Theme Toggle Button -->
<div class="theme-toggle-container">
    <button id="themeToggle" class="theme-toggle-btn" aria-label="Changer le thème">
        <i class="fas fa-moon" id="themeIcon"></i>
    </button>
</div>

<!-- Hero Section with Manual Slider -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-slider">
                        <?php if (!empty($announcement) && is_array($announcement)): ?>
                            <?php foreach ($announcement as $index => $ann): ?>
                                <div class="hero-slide" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;" data-slide-index="<?php echo $index; ?>">
                                    <h1 class="hero-title">
                                        <?= htmlspecialchars($ann['titre1'] ?? 'Annonce sans titre', ENT_QUOTES, 'UTF-8') ?>
                                    </h1>
                                    <p class="hero-description">
                                        <?= htmlspecialchars($ann['content'] ?? 'Aucune description disponible.', ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                    <div class="hero-buttons">
                                        <a href="/Altiris/contact" class="btn-primary">
                                            Découvrir Plus
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="hero-slide" style="display: block;" data-slide-index="0">
                                <h1 class="hero-title">Aucune annonce disponible</h1>
                                <p class="hero-description">Veuillez vérifier la base de données pour ajouter des annonces.</p>
                                <div class="hero-buttons">
                                    <a href="/Altiris/contact" class="btn-primary">
                                        Découvrir Plus
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Slider Navigation -->
                    <?php if (!empty($announcement) && count($announcement) > 1): ?>
                    <div class="slider-nav">
                        <button class="slider-btn prev-btn" aria-label="Previous slide">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="slider-btn next-btn" aria-label="Next slide">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="hero-image-container">
                        <?php if (!empty($announcement) && is_array($announcement)): ?>
                            <?php foreach ($announcement as $index => $ann): ?>
                                <div class="hero-image-slide" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;" data-slide-index="<?php echo $index; ?>">
                                    <img src="<?= htmlspecialchars($ann['image'] ?? '/Altiris/Assets/Images/logo_Altirys.jpg', ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="Hero Image" 
                                         class="hero-image" 
                                         onerror="this.onerror=null; this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                                    <div class="floating-card">
                                        <div class="card-content">
                                            <h6><?= htmlspecialchars($ann['titre'] ?? 'Annonce', ENT_QUOTES, 'UTF-8') ?></h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="hero-image-slide" style="display: block;" data-slide-index="0">
                                <img src="/Altiris/Assets/Images/logo_Altirys.jpg" 
                                     alt="Hero Image" 
                                     class="hero-image"
                                     onerror="this.onerror=null; this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                                <div class="floating-card">
                                    <div class="card-content">
                                        <h6>Aucune annonce</h6>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Bienvenue chez Altirys -->
<section class="Alt-welcome-section">
    <div class="Alt-container">
        <div class="Alt-content">
            <h2 class="Alt-title">Bienvenue chez Altirys</h2>
            <div class="Alt-text-image-container">
                <div class="Alt-text-content">
                    <p>
                        <span class="highlight-text">Là où la technologie rencontre la créativité.
                        Nous transformons vos idées en expériences digitales uniques.</span>
                        Que vous soyez une startup ambitieuse, une entreprise établie ou un créateur visionnaire,<br>
                        Altirys est votre partenaire pour donner vie à vos projets Web, mobiles, ludiques et visuels.<br>
                        Notre mission : concevoir des solutions innovantes qui marquent les esprits et font évoluer votre activité.
                    </p>
                </div>
                <div class="Alt-image-container">
                    <div class="Alt-image-wrapper">
                        <img src="/Altiris/Assets/Images/_+Brush+Stroke+_+Painting+Print-removebg-preview.png" alt="Solutions digitales innovantes" class="Alt-animated-image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts Section -->
<section class="blog-posts-section">
    <div style="text-align: center; margin: 2rem 0;">
    <h2 style="color: var(--primary-color); 
                transition: color 0.3s ease; 
                cursor: pointer; 
                font-size: 2rem; 
                font-weight: 700; 
                margin-bottom: 0.5rem;"
        onmouseover="this.style.color='var(--secondary-color)'" 
        onmouseout="this.style.color='var(--primary-color)'">
        Altiris : La plateforme tout-en-un pour une gestion de parc IT performante
    </h2>

    <p style="color: #555; 
                font-size: 1.2rem; 
                font-weight: 400; 
                margin: 0;">
        Simplifiez la gestion, maximisez la performance.
    </p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="blog-filters">
                    <button class="filter-btn active" data-filter="all">Tous</button>
                    <button class="filter-btn" data-filter="technologie">Technologie</button>
                    <button class="filter-btn" data-filter="design">Design</button>
                    <button class="filter-btn" data-filter="application">Application</button>
                    <button class="filter-btn" data-filter="web">Web</button>
                    <button class="filter-btn" data-filter="jeux">Jeux</button>
                </div>
            </div>
        </div>

        <?php if (!empty($posts) && is_array($posts)): ?>
            <div class="row g-4 blog-grid">
                <?php foreach ($posts as $post): ?>
                    <div class="col-lg-4 col-md-6 blog-item" data-category="<?= htmlspecialchars($post['category'] ?? 'technologie', ENT_QUOTES, 'UTF-8') ?>">
                        <article class="blog-card clickable-card" onclick="window.location.href='/Altiris/blog/<?php echo htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8'); ?>'">
                            <div class="blog-card-image">
                                <img src="<?= htmlspecialchars($post['image_path'] ?? '/Altiris/Assets/Images/logo_Altirys.jpg', ENT_QUOTES, 'UTF-8') ?>" 
                                     alt="<?= htmlspecialchars($post['title'] ?? 'Article', ENT_QUOTES, 'UTF-8') ?>"
                                     onerror="this.onerror=null; this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                                <div class="blog-card-overlay">
                                    <div class="blog-card-category"><?= htmlspecialchars($post['category'] ?? 'technologie', ENT_QUOTES, 'UTF-8') ?></div>
                                </div>
                            </div>
                            <div class="blog-card-content">
                                <div class="blog-card-meta">
                                    <span class="blog-date">
                                        <i class="far fa-calendar"></i>
                                        <?= isset($post['created_at']) ? date('d M Y', strtotime($post['created_at'])) : 'Date inconnue' ?>
                                    </span>
                                    <span class="blog-reading-time">
                                        <i class="far fa-clock"></i>
                                        5 min
                                    </span>
                                </div>
                                <h3 class="blog-card-title"><?= htmlspecialchars($post['title'] ?? 'Titre inconnu', ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="blog-card-excerpt"><?= htmlspecialchars($post['excerpt'] ?? 'Aucun extrait', ENT_QUOTES, 'UTF-8') ?></p>
                                <div class="blog-card-footer">
                                    <span class="blog-read-more">
                                        Lire l'article
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                    <div class="blog-card-author">
                                        <div class="author-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span>ALTIRYS</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="no-posts">
                        <div class="no-posts-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h3>Aucun article disponible</h3>
                        <p>Nos articles arrivent bientôt. Restez connectés !</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Service Section -->
<section class="trust-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Des services pensés pour vos besoins digitaux</h2>
            <p class="section-subtitle">
                <?php 
                    echo !empty($announcement[0]['slogan']) ? htmlspecialchars($announcement[0]['slogan'], ENT_QUOTES, 'UTF-8') : 'Des applications sur mesure, pour des résultats sur mesure.'; 
                ?>
            </p>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($services) && is_array($services)): ?>
                <?php foreach ($services as $index => $service): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card clickable-card" onclick="window.location.href='/Altiris/service/<?php echo htmlspecialchars($service['id'], ENT_QUOTES, 'UTF-8'); ?>'">
                            <div class="service-image">
                                <img src="<?php echo htmlspecialchars($service['image_path'] ?? '/Altiris/Assets/Images/logo_Altirys.jpg', ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($service['titre'] ?? 'Service', ENT_QUOTES, 'UTF-8'); ?>" 
                                     class="service-img"
                                     onerror="this.onerror=null; this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                            </div>
                            <h4 class="service-title"><?php echo htmlspecialchars($service['titre'] ?? 'Service', ENT_QUOTES, 'UTF-8'); ?></h4>
                            <p class="service-description"><?php echo htmlspecialchars(substr($service['texte'] ?? 'Description du service', 0, 100), ENT_QUOTES, 'UTF-8') ?>...</p>
                            <span href= "/Altiris/service-detail"class="service-link">
                                Voir Plus <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card clickable-card" onclick="window.location.href='/Altiris/service/1'">
                        <div class="service-image">
                            <img src="/Altiris/Assets/Images/logo_Altirys.jpg" alt="Service" class="service-img">
                        </div>
                        <h4 class="service-title">Ingénierie Produit</h4>
                        <p class="service-description">Solutions sur mesure pour vos projets numériques.</p>
                        <span class="service-link">
                            Voir Plus <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Partner Section -->
<section class="partner-section">
    <div class="container">
        <div class="partner-header">
            <h2 class="partner-title">Nos partenaires</h2>
        </div>
        <div class="partner-logos">
            <?php if (!empty($partenaires) && is_array($partenaires)): ?>
                <div class="row">
                    <?php foreach ($partenaires as $partenaire): ?>
                        <?php if (!empty($partenaire['logo_partenaire'])): ?>
                            <div class="col">
                                <div class="partner-logo">
                                    <img src="<?= htmlspecialchars($partenaire['image_path'], ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="Logo Partenaire <?= htmlspecialchars($partenaire['id'], ENT_QUOTES, 'UTF-8') ?>" 
                                         class="partner-img"
                                         onerror="this.onerror=null; this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="col-12">
                    <div class="partner-logo">
                        <img src="/Altiris/Assets/Images/logo_Altirys.jpg" alt="Default Logo" class="partner-img">
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="partner-details">
            <h2 class="partner-details-title">Pourquoi choisir Altirys ?</h2>
            <div class="partner-details-table">
                <?php if (!empty($caracteristiques) && is_array($caracteristiques)): ?>
                    <div class="row">
                        <?php foreach ($caracteristiques as $caracteristique): ?>
                            <div class="col">
                                <div class="partner-detail-item">
                                    <i class="<?= $caracteristique['icon_bootstrap'] ?>"></i>
                                    <p class="partner-detail-description"><?= htmlspecialchars($caracteristique['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="partner-detail-item">
                        <i class="fas fa-user"></i>
                        <p class="partner-detail-description">Aucune description disponible.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="cta-image">
                    <img src="<?php echo htmlspecialchars($altirysInfo['image_path'] ?? '/Altiris/Assets/Images/logo_Altirys.jpg', ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="Team Working" 
                         class="img-fluid rounded"
                         onerror="this.onerror=null; this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="cta-content">
                    <h2 class="cta-title">Donnez-nous une idée que les gens veulent passer du temps avec</h2>
                    <p class="cta-description"><?php echo htmlspecialchars($altirysInfo['mission'] ?? 'Créons ensemble des solutions numériques qui captivent et engagent.', ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Boostez Votre Vente</h5>
                                <p><?php echo htmlspecialchars($altirysInfo['vente_boost'] ?? 'Maximisez vos revenus avec des stratégies numériques efficaces.', ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="feature-content">
                                <h5>Suivre & Analyser</h5>
                                <p><?php echo htmlspecialchars($altirysInfo['analyse'] ?? 'Obtenez des insights précieux pour optimiser vos performances.', ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dernières Actualités Section -->
<section class="blog-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Dernières Actualités</h2>
            <p class="section-subtitle">Restez informé de nos dernières nouvelles et mises à jour</p>
        </div>
        
        <?php if (!empty($actualites) && is_array($actualites)): ?>
            <div class="row g-4">
                <?php foreach ($actualites as $actualite): ?>
                    <div class="col-lg-4 col-md-6">
                        <article class="blog-card clickable-card" onclick="window.location.href='/Altiris/actualite/<?php echo htmlspecialchars($actualite['id'], ENT_QUOTES, 'UTF-8'); ?>'">
                            <div class="blog-image">
                                <img src="<?php echo htmlspecialchars($actualite['image_path'] ?? '/Altiris/Assets/Images/logo_Altirys.jpg', ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($actualite['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                     onerror="this.onerror=null; this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                            </div>
                            
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-date">
                                        <i class="far fa-calendar"></i>
                                        <?php echo isset($actualite['created_at']) ? date('d M Y', strtotime($actualite['created_at'])) : 'Date inconnue'; ?>
                                    </span>
                                    <span class="blog-reading-time">
                                        <i class="far fa-clock"></i>
                                        5 min
                                    </span>
                                </div>
                                
                                <h3 class="blog-title"><?php echo htmlspecialchars($actualite['title'] ?? 'Titre inconnu', ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p class="blog-excerpt"><?php echo htmlspecialchars($actualite['excerpt'] ?? 'Aucun extrait', ENT_QUOTES, 'UTF-8'); ?></p>
                                
                                <span class="blog-link">
                                    Lire l'actualité
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="/Altiris/actualites" class="btn-outline">
                    Voir toutes les actualités
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="no-posts">
                        <div class="no-posts-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h3>Aucune actualité disponible</h3>
                        <p>Nos actualités arrivent bientôt. Restez connectés !</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Ce qu'ils disent de nous</h2>
            <p class="section-subtitle">Découvrez les retours de ceux qui ont vécu l'expérience avec notre équipe engagée.</p>
        </div>
        
        <?php if (!empty($temoignages) && is_array($temoignages)): ?>
            <!-- Affichage des 3 premiers témoignages -->
            <div class="row g-4" id="testimonials-preview">
                <?php foreach (array_slice($temoignages, 0, 3) as $temoignage): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-image">
                                <?php if (!empty($temoignage['image_path'])): ?>
                                    <img src="<?= htmlspecialchars($temoignage['image_path'], ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="<?= htmlspecialchars($temoignage['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                         class="img-fluid rounded-circle"
                                         onerror="this.onerror=null; this.src='/Altiris/Assets/Images/avatar-default.jpg';">
                                <?php else: ?>
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    <p><?= htmlspecialchars($temoignage['texte'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                                <div class="testimonial-author">
                                    <h5><?= htmlspecialchars($temoignage['nom'], ENT_QUOTES, 'UTF-8') ?></h5>
                                    <span><?= htmlspecialchars($temoignage['poste'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <div class="testimonial-date">
                                    <small><?= htmlspecialchars($temoignage['date_formatee'], ENT_QUOTES, 'UTF-8') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Témoignages cachés -->
            <?php if (count($temoignages) > 3): ?>
            <div class="row g-4" id="testimonials-hidden" style="display: none;">
                <?php foreach (array_slice($temoignages, 3) as $temoignage): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-image">
                                <?php if (!empty($temoignage['image_path'])): ?>
                                    <img src="<?= htmlspecialchars($temoignage['image_path'], ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="<?= htmlspecialchars($temoignage['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                         class="img-fluid rounded-circle"
                                         onerror="this.onerror=null; this.src='/Altiris/Assets/Images/avatar-default.jpg';">
                                <?php else: ?>
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="testimonial-content">
                                <div class="testimonial-text">
                                    <p><?= htmlspecialchars($temoignage['texte'], ENT_QUOTES, 'UTF-8') ?></p>
                                </div>
                                <div class="testimonial-author">
                                    <h5><?= htmlspecialchars($temoignage['nom'], ENT_QUOTES, 'UTF-8') ?></h5>
                                    <span><?= htmlspecialchars($temoignage['poste'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <div class="testimonial-date">
                                    <small><?= htmlspecialchars($temoignage['date_formatee'], ENT_QUOTES, 'UTF-8') ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Bouton pour afficher tous les témoignages -->
            <div class="text-center mt-4">
                <button id="show-all-testimonials" class="btn-outline">
                    <span id="testimonials-btn-text">Voir tous les témoignages</span>
                    <i class="fas fa-chevron-down ms-2" id="testimonials-btn-icon"></i>
                </button>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Aucun témoignage disponible pour le moment
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Skills Section -->
<section class="skills-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Nos Compétences</h2>
            <p class="section-subtitle">Technologies que nous maîtrisons</p>
        </div>
        
        <div class="skills-grid">
            <?php if (!empty($competences) && is_array($competences)): ?>
                <?php foreach ($competences as $competence): ?>
                    <div class="skill-item">
                        <?php 
                            $iconName = strtolower(str_replace([' ', '.', '#', '+'], '', $competence['nom']));
                            $iconUrl = "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/{$iconName}/{$iconName}-original.svg";
                        ?>
                        <img src="<?php echo htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($competence['nom'], ENT_QUOTES, 'UTF-8'); ?>" 
                             class="skill-logo"
                             onerror="this.onerror=null; this.src='<?php echo !empty($competence['image_path']) ? htmlspecialchars($competence['image_path'], ENT_QUOTES, 'UTF-8') : '/Altiris/Assets/Images/pexels-photo-3184339.jpeg'; ?>';">
                        <span><?php echo htmlspecialchars($competence['nom'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="skill-item">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" alt="HTML5" class="skill-logo">
                    <span>HTML5</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php
// Vérifier que footer.php existe
if (!file_exists(__DIR__ . '/partials/footer.php')) {
    error_log("Erreur: footer.php introuvable à " . __DIR__ . '/partials/footer.php');
}
require_once __DIR__ . '/partials/footer.php';
?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du thème (inchangée)
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;
        
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            body.classList.add('dark-mode');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        }
        
        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        });
        
        // Gestion du slider Hero
        const slides = document.querySelectorAll('.hero-slide');
        const imageSlides = document.querySelectorAll('.hero-image-slide');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        let currentSlide = 0;

        if (slides.length > 1) {
            function showSlide(index) {
                slides.forEach(slide => slide.style.display = 'none');
                imageSlides.forEach(imgSlide => imgSlide.style.display = 'none');
                if (slides[index]) slides[index].style.display = 'block';
                if (imageSlides[index]) imageSlides[index].style.display = 'block';
                if (prevBtn) prevBtn.disabled = index === 0;
                if (nextBtn) nextBtn.disabled = index === slides.length - 1;
                
                // Réinitialiser l'animation à chaque changement de slide
                const currentImage = imageSlides[index].querySelector('.hero-image');
                if (currentImage) {
                    currentImage.classList.remove('rotate-once');
                    void currentImage.offsetWidth; // Forcer le reflow pour réinitialiser l'animation
                }
            }

            showSlide(currentSlide);

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentSlide > 0) {
                        currentSlide--;
                        showSlide(currentSlide);
                    }
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (currentSlide < slides.length - 1) {
                        currentSlide++;
                        showSlide(currentSlide);
                    }
                });
            }
        }

        // Rotation à l'actualisation et au survol
        const heroImages = document.querySelectorAll('.hero-image');
        heroImages.forEach(image => {
            // Ajouter la rotation une fois à l'actualisation
            image.classList.add('rotate-once');
            setTimeout(() => {
                image.classList.remove('rotate-once');
            }, 5000); // Retire la classe après 5 secondes

            // Ajouter la rotation au survol
            image.addEventListener('mouseenter', () => {
                image.classList.add('rotate-once');
            });
            image.addEventListener('mouseleave', () => {
                image.classList.remove('rotate-once');
                void image.offsetWidth; // Réinitialiser l'animation pour le prochain survol
            });
        });

        // Gestion des filtres blog (inchangée)
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const filter = btn.dataset.filter;
                const items = document.querySelectorAll('.blog-item');
                items.forEach(item => {
                    if (filter === 'all' || item.dataset.category.toLowerCase() === filter.toLowerCase()) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Gestion des témoignages (inchangée)
        const showAllBtn = document.getElementById('show-all-testimonials');
        const hiddenTestimonials = document.getElementById('testimonials-hidden');
        const btnText = document.getElementById('testimonials-btn-text');
        const btnIcon = document.getElementById('testimonials-btn-icon');
        
        if (showAllBtn && hiddenTestimonials) {
            let isExpanded = false;
            showAllBtn.addEventListener('click', () => {
                if (isExpanded) {
                    hiddenTestimonials.style.display = 'none';
                    btnText.textContent = 'Voir tous les témoignages';
                    btnIcon.classList.remove('fa-chevron-up');
                    btnIcon.classList.add('fa-chevron-down');
                    isExpanded = false;
                } else {
                    hiddenTestimonials.style.display = 'flex';
                    btnText.textContent = 'Masquer les témoignages';
                    btnIcon.classList.remove('fa-chevron-down');
                    btnIcon.classList.add('fa-chevron-up');
                    isExpanded = true;
                }
            });
        }

        // Animation pour l'image de la section Bienvenue (inchangée)
        const welcomeImage = document.querySelector('.Alt-welcome-section .Alt-animated-image');
        if (welcomeImage) {
            function startImageAnimation() {
                welcomeImage.style.animation = `
                    float 6s ease-in-out infinite,
                    pulse 4s ease-in-out infinite
                `;
            }
            startImageAnimation();
            new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'src') {
                        startImageAnimation();
                    }
                });
            }).observe(welcomeImage, { attributes: true });
            welcomeImage.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.filter = 'drop-shadow(0 20px 30px rgba(0, 0, 0, 0.2)) brightness(1.05)';
            });
            welcomeImage.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.filter = '';
            });
        }
    });
</script>