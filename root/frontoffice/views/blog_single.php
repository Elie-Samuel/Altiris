<?php
if (!isset($post) || !is_array($post)) {
    error_log("Erreur : \$post non défini ou invalide dans blog_single.php");
    header("Location: /Altiris/blog");
    exit;
}
require_once __DIR__ . '/partials/header.php';
?>
<br>
<main class="blog-single-container">
    <div class="container">
        <article class="blog-single-article">
            <div class="row align-items-center g-5">
                <!-- Colonne pour l'image -->
                <?php if (!empty($post['image_path'])): ?>
                <div class="col-lg-4 col-md-5">
                    <div class="blog-single-image">
                        <img src="<?= htmlspecialchars($post['image_path']) ?>" 
                             alt="<?= htmlspecialchars($post['title'] ?? 'Image de l\'article') ?>"
                             class="blog-single-img"
                             onerror="this.src='/Altiris/Assets/Images/logo_Altirys.jpg'; this.onerror=null;">
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Colonne pour le contenu -->
                <div class="<?= !empty($post['image_path']) ? 'col-lg-8 col-md-7' : 'col-12' ?>">
                    <header class="blog-single-header">
                        <h1 class="blog-single-title"><?= htmlspecialchars($post['title'] ?? 'Titre non disponible') ?></h1>
                        <div class="blog-meta">
                            <span class="blog-date badge bg-secondary">
                                <i class="far fa-calendar me-1"></i>
                                <?= isset($post['created_at']) ? date('d M Y', strtotime($post['created_at'])) : 'Date non disponible' ?>
                            </span>
                            <span class="blog-reading-time badge bg-secondary">
                                <i class="far fa-clock me-1"></i>
                                5 min de lecture
                            </span>
                        </div>
                    </header>
                    <div class="blog-single-content">
                        <?= isset($post['content']) ? nl2br(htmlspecialchars($post['content'])) : '<p>Contenu non disponible.</p>' ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/Altiris/Assets/js/theme-toggle.js"></script>