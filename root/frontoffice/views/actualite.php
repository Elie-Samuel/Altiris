<style>
    .actualite-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }

    .actualite-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .actualite-date {
        color: var(--secondary);
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .actualite-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1.5rem;
    }

    .actualite-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .actualite-content {
        color: var(--text-light);
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        color: var(--secondary);
        margin-top: 3rem;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .back-link i {
        margin-right: 0.5rem;
    }

    @media (max-width: 768px) {
        .actualite-container {
            padding: 2rem 1rem;
        }
        
        .actualite-title {
            font-size: 2rem;
        }
    }
</style>

<main class="actualite-container">
    <div class="actualite-header">
        <div class="actualite-date">
            <?= htmlspecialchars($actualite['date_formatee']) ?>
        </div>
        <h1 class="actualite-title"><?= htmlspecialchars($actualite['titre']) ?></h1>
    </div>

    <?php if ($actualite['image_path']): ?>
    <img src="<?= htmlspecialchars($actualite['image_path']) ?>" alt="<?= htmlspecialchars($actualite['titre']) ?>" class="actualite-image">
    <?php endif; ?>

    <div class="actualite-content">
        <?= nl2br(htmlspecialchars($actualite['texte'])) ?>
    </div>

    <a href="/Altiris/root/?page=actualites" class="back-link">
        <i class="fas fa-arrow-left"></i> Retour aux actualités
    </a>
</main>