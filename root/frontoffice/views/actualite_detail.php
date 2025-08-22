<?php
// Page de détail d'une actualité
$pageTitle = 'Détail Actualité - ALTIRYS';

// Récupérer l'ID de l'actualité
$actualiteId = $_GET['id'] ?? 0;

// Simuler la récupération des données (à remplacer par une vraie requête DB)
$actualite = [
    'id' => $actualiteId,
    'title' => 'Actualité détaillée',
    'content' => 'Contenu complet de l\'actualité...',
    'image_path' => '/Altiris/Assets/Images/logo_Altirys.jpg',
    'created_at' => date('Y-m-d H:i:s'),
    'excerpt' => 'Résumé de l\'actualité'
];

require_once __DIR__ . '/partials/header.php';
?>

<section class="actualite-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="actualite-detail">
                    <header class="actualite-header">
                        <div class="actualite-meta">
                            <span class="actualite-date">
                                <i class="far fa-calendar"></i>
                                <?= date('d M Y', strtotime($actualite['created_at'])) ?>
                            </span>
                        </div>
                        <h1 class="actualite-title"><?= htmlspecialchars($actualite['title']) ?></h1>
                        <p class="actualite-excerpt"><?= htmlspecialchars($actualite['excerpt']) ?></p>
                    </header>
                    
                    <div class="actualite-image">
                        <img src="<?= htmlspecialchars($actualite['image_path']) ?>" 
                             alt="<?= htmlspecialchars($actualite['title']) ?>"
                             class="img-fluid rounded">
                    </div>
                    
                    <div class="actualite-content">
                        <p><?= nl2br(htmlspecialchars($actualite['content'])) ?></p>
                    </div>
                    
                    <footer class="actualite-footer">
                        <div class="actualite-actions">
                            <a href="/Altiris/actualites" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Retour aux actualités
                            </a>
                            <a href="/Altiris/home" class="btn btn-primary">
                                <i class="fas fa-home"></i> Accueil
                            </a>
                        </div>
                    </footer>
                </article>
            </div>
        </div>
    </div>
</section>

<style>
.actualite-detail-section {
    padding: 100px 0;
    background-color: var(--bg-primary);
}

.actualite-detail {
    background: var(--card-bg);
    border-radius: 15px;
    padding: 3rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.actualite-header {
    margin-bottom: 2rem;
}

.actualite-meta {
    margin-bottom: 1rem;
}

.actualite-date {
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.actualite-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.3;
    margin-bottom: 1rem;
}

.actualite-excerpt {
    font-size: 1.2rem;
    color: var(--text-secondary);
    font-style: italic;
    line-height: 1.6;
}

.actualite-image {
    margin-bottom: 2rem;
}

.actualite-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.actualite-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
    margin-bottom: 2rem;
}

.actualite-footer {
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.actualite-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
    border: 2px solid var(--primary-color);
}

.btn-primary:hover {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
    color: white;
}

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    background: transparent;
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
}

@media (max-width: 768px) {
    .actualite-detail {
        padding: 2rem;
    }
    
    .actualite-title {
        font-size: 2rem;
    }
    
    .actualite-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<?php require_once __DIR__ . '/partials/footer.php'; ?>