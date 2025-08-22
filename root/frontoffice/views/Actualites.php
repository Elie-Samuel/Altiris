<?php
$pageTitle = 'Toutes les Actualités - ALTIRYS';

// Simuler des données d'actualités (à remplacer par une vraie requête DB)
$actualites = [
    [
        'id' => 1,
        'title' => 'Nouvelle technologie révolutionnaire',
        'excerpt' => 'Découvrez notre dernière innovation qui va changer le monde du développement.',
        'image_path' => '/Altiris/Assets/Images/logo_Altirys.jpg',
        'created_at' => '2024-01-15'
    ],
    [
        'id' => 2,
        'title' => 'Partenariat stratégique annoncé',
        'excerpt' => 'ALTIRYS s\'associe avec des leaders de l\'industrie pour offrir de meilleurs services.',
        'image_path' => '/Altiris/Assets/Images/logo_Altirys.jpg',
        'created_at' => '2024-01-10'
    ],
    [
        'id' => 3,
        'title' => 'Expansion internationale',
        'excerpt' => 'Notre entreprise étend ses activités à l\'international avec de nouveaux bureaux.',
        'image_path' => '/Altiris/Assets/Images/logo_Altirys.jpg',
        'created_at' => '2024-01-05'
    ]
];

require_once __DIR__ . '/partials/header.php';
?>

<section class="actualites-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Toutes les Actualités</h2>
            <p class="section-subtitle">Découvrez toutes nos dernières nouvelles et mises à jour</p>
        </div>
        
        <?php if (!empty($actualites) && is_array($actualites)): ?>
            <div class="row g-4">
                <?php foreach ($actualites as $actualite): ?>
                    <div class="col-lg-4 col-md-6">
                        <article class="blog-card">
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
                                
                                <a href="/Altiris/lire_article/<?php echo htmlspecialchars($actualite['id'], ENT_QUOTES, 'UTF-8'); ?>" class="blog-link">
                                    Lire l'actualité
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5">
                <a href="/Altiris/home" class="btn-outline">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à l'accueil
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
                        <div class="mt-4">
                            <a href="/Altiris/home" class="btn-outline">
                                <i class="fas fa-arrow-left me-2"></i>
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.actualites-section {
    padding: 80px 0;
    background: var(--bg-light);
    transition: var(--transition);
    min-height: 70vh;
}

.actualites-section .section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--text-light);
    line-height: 1.3;
    transition: var(--transition);
}

.actualites-section .section-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: linear-gradient(to right, var(--secondary-color), var(--primary-color));
    margin: 1rem auto;
    border-radius: 2px;
}

.actualites-section .section-subtitle {
    font-size: 1.1rem;
    color: var(--gray-light);
    margin-bottom: 3rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    transition: var(--transition);
}

.actualites-section .blog-card {
    background: var(--card-light);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    height: 100%;
    border: 1px solid var(--border-light);
}

.actualites-section .blog-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.actualites-section .blog-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.actualites-section .blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.actualites-section .blog-card:hover .blog-image img {
    transform: scale(1.05);
}

.actualites-section .blog-content {
    padding: 1.5rem;
}

.actualites-section .blog-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: var(--gray-light);
    transition: var(--transition);
}

.actualites-section .blog-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-light);
    line-height: 1.4;
    transition: var(--transition);
}

.actualites-section .blog-excerpt {
    color: var(--gray-light);
    margin-bottom: 1.5rem;
    line-height: 1.6;
    transition: var(--transition);
}

.actualites-section .blog-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
}

.actualites-section .blog-link:hover {
    color: var(--secondary-color);
    transform: translateX(5px);
}

.actualites-section .no-posts {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--card-light);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.actualites-section .no-posts-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

.actualites-section .no-posts h3 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: var(--text-light);
    transition: var(--transition);
}

.actualites-section .no-posts p {
    color: var(--gray-light);
    transition: var(--transition);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    padding: 1rem 2rem;
    border-radius: 50px;
    color: var(--primary-color);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: var(--transition);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: var(--text-dark);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .actualites-section {
        padding: 60px 0;
    }
    
    .actualites-section .section-title {
        font-size: 2rem;
    }
    
    .actualites-section .blog-image {
        height: 180px;
    }
}

@media (max-width: 576px) {
    .actualites-section .section-title {
        font-size: 1.8rem;
    }
    
    .actualites-section .blog-content {
        padding: 1.2rem;
    }
}
</style>

<?php
require_once __DIR__ . '/partials/footer.php';
?>