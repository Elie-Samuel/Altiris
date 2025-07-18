<?php
// D:\wamp64\www\Altiris\root\frontoffice\views\actualite.php
?>
<section class="py-5">
    <div class="container">
        <h2>Actualité</h2>
        <?php if (!empty($actualite)): ?>
            <div class="card border-0 shadow-sm">
                <?php if (!empty($actualite['image_path'])): ?>
                    <img src="<?= htmlspecialchars($actualite['image_path']) ?>" 
                         class="card-img-top" 
                         alt="Actualité <?= htmlspecialchars($actualite['id']) ?>"
                         style="height: 300px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($actualite['titre']) ?></h3>
                    <p class="text-muted">
                        <i class="far fa-calendar me-1"></i>
                        <?= htmlspecialchars($actualite['date_formatee']) ?>
                    </p>
                    <p class="card-text"><?= htmlspecialchars($actualite['texte']) ?></p>
                    <a href="/Altiris/root/?page=home" class="btn btn-outline-primary">Retour à l'accueil</a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Actualité non trouvée.</div>
        <?php endif; ?>
    </div>
</section>