<?php require_once __DIR__ . '/partials/header.php'; ?>

<!-- Blog Hero Section -->
<section class="blog-hero-section">
    <div class="blog-particles-bg"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="blog-hero-content">
                    <h1 class="blog-hero-title"><?= htmlspecialchars($blogDesc['titre'] ?? 'Notre Blog') ?></h1>
                    <h2 class="blog-hero-subtitle"><?= htmlspecialchars($blogDesc['sous_titre'] ?? 'Découvrez nos dernières actualités') ?></h2>
                    <p class="blog-hero-description">
                        <?= htmlspecialchars($blogDesc['description'] ?? 'Restez informé des dernières tendances technologiques, de nos projets innovants et de nos conseils d\'experts en développement digital.') ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="blog-hero-visual">
                    <div class="blog-image-container">
                        <div class="floating-elements">
                            <div class="floating-card card-1">
                                <i class="fas fa-code"></i>
                                <span>Développement</span>
                            </div>
                            <div class="floating-card card-2">
                                <i class="fas fa-mobile-alt"></i>
                                <span>Application</span>
                            </div>
                            <div class="floating-card card-3">
                                <i class="fas fa-paint-brush"></i>
                                <span>Design</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts Section -->
<section class="blog-posts-section">
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
                    <div class="col-lg-4 col-md-6 blog-item" data-category="<?= htmlspecialchars($post['category'] ?? 'technologie') ?>">
                        <a href="/Altiris/root/?page=blog&id=<?= $post['id'] ?? 0 ?>" class="blog-card-link">
                            <article class="blog-card">
                                <div class="blog-card-image">
                                    <img src="<?= htmlspecialchars($post['image_path'] ?? '/Altiris/Assets/Images/logo_Altirys.jpg') ?>" 
                                         alt="<?= htmlspecialchars($post['title'] ?? 'Article') ?>"
                                         onerror="this.onerror=null;this.src='/Altiris/Assets/Images/logo_Altirys.jpg';">
                                    <div class="blog-card-overlay">
                                        <div class="blog-card-category"><?= htmlspecialchars($post['category'] ?? 'Technologie') ?></div>
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
                                    
                                    <h3 class="blog-card-title"><?= htmlspecialchars($post['title'] ?? 'Titre inconnu') ?></h3>
                                    <p class="blog-card-excerpt"><?= htmlspecialchars($post['excerpt'] ?? 'Aucun extrait') ?></p>
                                    
                                    <div class="blog-card-footer">
                                        <div class="blog-card-author">
                                            <div class="author-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span>ALTIRYS</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/Altiris/Assets/js/theme-toggle.js"></script>
<script>
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
</script>
</body>
</html>