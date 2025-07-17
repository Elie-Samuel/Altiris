<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/AnnonceController.php';
$controller = new AnnonceController();
$annonces = $controller->index();
?>
<h2>Gestion des Annonces</h2>
<a href="create.php" class="btn btn-primary mb-3">Ajouter une annonce</a>
<div class="card-container">
    <?php if (empty($annonces)): ?>
        <p class="text-center">Aucune annonce.</p>
    <?php else: ?>
        <?php foreach ($annonces as $annonce): ?>
            <div class="card">
                <div class="card-header">ID: <?php echo isset($annonce['id']) ? htmlspecialchars($annonce['id']) : ''; ?></div>
                <div class="card-body">
                    <p><?php echo isset($annonce['text']) && $annonce['text'] !== null ? htmlspecialchars($annonce['text']) : '(Aucun texte)'; ?></p>
                    <div class="card-image">
                        <?php if (isset($annonce['image']) && $annonce['image'] !== null): ?>
                            <img src="/Altiris/<?php echo htmlspecialchars($annonce['image']); ?>" alt="Annonce image">
                        <?php else: ?>
                            <p class="text-center">(Aucune image)</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="edit.php?id=<?php echo isset($annonce['id']) ? $annonce['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                    <a href="delete.php?id=<?php echo isset($annonce['id']) ? $annonce['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once '../../components/footer.php'; ?>