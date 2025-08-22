<?php
// Page de détail d'un article de blog
$pageTitle = 'Détail Article - ALTIRYS';

// Récupérer l'ID de l'article
$articleId = $_GET['id'] ?? 0;

// Simuler la récupération des données (à remplacer par une vraie requête DB)
$article = [
    'id' => $articleId,
    'title' => 'Article de blog détaillé',
    'content' => 'Contenu complet de l\'article de blog...',
    'image_path' => '/Altiris/Assets/Images/logo_Altirys.jpg',
    'created_at' => date('Y-m-d H:i:s'),
    'category' => 'Technologie',
    'author' => 'ALTIRYS'
];

require_once __DIR__ . '/partials/header.php';
?>

<section class="article-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="article-detail">
                    <header class="article-header">
                        <div class="article-meta">
                            <span class="article-category"><?= htmlspecialchars($article['category']) ?></span>
                            <span class="article-date">
                                <i class="far fa-calendar"></i>
                                <?= date('d M Y', strtotime($article['created_at'])) ?>
                            </span>
                        </div>
                        <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
                    </header>
                    
                    <div class="article-image">
                        <img src="<?= htmlspecialchars($article['image_path']) ?>" 
                             alt="<?= htmlspecialchars($article['title']) ?>"
                             class="img-fluid rounded">
                    </div>
                    
                    <div class="article-content">
                        <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
                    </div>
                    
                    <footer class="article-footer">
                        <div class="article-author">
                            <strong>Par <?= htmlspecialchars($article['author']) ?></strong>
                        </div>
                        <div class="article-actions">
                            <a href="/Altiris/blog" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Retour au blog
                            </a>
                        </div>
                    </footer>
                </article>
            </div>
        </div>
    </div>
</section>

<style>
.article-detail-section {
    padding: 100px 0;
    background-color: var(--bg-primary);
}

.article-detail {
    background: var(--card-bg);
    border-radius: 15px;
    padding: 3rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.article-header {
    margin-bottom: 2rem;
}

.article-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.article-category {
    background: var(--primary-color);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-weight: 600;
}

.article-date {
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.article-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.3;
}

.article-image {
    margin-bottom: 2rem;
}

.article-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
    margin-bottom: 2rem;
}

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.article-author {
    color: var(--text-primary);
}

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
}

@media (max-width: 768px) {
    .article-detail {
        padding: 2rem;
    }
    
    .article-title {
        font-size: 2rem;
    }
    
    .article-footer {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<?php require_once __DIR__ . '/partials/footer.php'; ?>