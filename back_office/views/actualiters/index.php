<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ActualiterController.php';
$controller = new ActualiterController();
$actualiters = $controller->index();

if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
}
?>
<h2>Gestion des Actualités</h2>
<a href="create.php" class="btn btn-primary mb-3">Ajouter une actualité</a>
<div class="card-container">
    <?php if (!$actualiters): ?>
        <div class="card">
            <div class="card-body">
                <p>Aucune actualité.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($actualiters as $actualiter): ?>
            <div class="card">
                <div class="card-header">
                    ID: <?php echo htmlspecialchars($actualiter['id']); ?>
                </div>
                <div class="card-body">
                    <p><strong>Date :</strong> <?php echo htmlspecialchars($actualiter['Date'] ?? 'Aucune date'); ?></p>
                    <p><?php echo htmlspecialchars(substr($actualiter['texte'], 0, 50)); ?>...</p>
                </div>
                <div class="card-image">
                    <?php if ($actualiter['image']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($actualiter['image']); ?>" alt="Image de l'actualité">
                    <?php else: ?>
                        <p>Pas d'image</p>
                    <?php endif; ?>
                </div>
                <div class="card-actions">
                    <a href="edit.php?id=<?php echo $actualiter['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="?delete=<?php echo $actualiter['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?');">Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once '../../components/footer.php'; ?>