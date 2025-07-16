<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <article class="card border-0 shadow-sm">
                <?php if (!empty($actualite['image'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($actualite['image']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($actualite['titre']) ?>">
                <?php endif; ?>
                
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <small class="text-muted">
                            <i class="far fa-calendar me-1"></i> 
                            <?= $actualite['date_formatee'] ?>
                        </small>
                    </div>
                    <h1 class="card-title mb-4"><?= htmlspecialchars($actualite['titre']) ?></h1>
                    <div class="card-text">
                        <?= nl2br(htmlspecialchars($actualite['texte'])) ?>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-end">
                    <a href="/Altiris/root/frontoffice/actualites" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> Retour aux actualités
                    </a>
                </div>
            </article>
        </div>
    </div>
</main>