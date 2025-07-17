<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ServiceController.php';
$controller = new ServiceController();
$services = $controller->index();
?>
<h2>Gestion des Services</h2>
<a href="create.php" class="btn btn-primary mb-3">Ajouter un service</a>
<div class="card-container">
    <?php if (empty($services)): ?>
        <div class="card">
            <div class="card-body">
                <p>Aucun service trouvé.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($services as $service): ?>
            <div class="card">
                <div class="card-header">
                    ID: <?php echo isset($service['id']) ? htmlspecialchars($service['id']) : ''; ?>
                    <?php if (isset($service['titre']) && $service['titre'] !== null): ?>
                        - Titre: <?php echo htmlspecialchars($service['titre']); ?>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <p><?php echo isset($service['texte']) && $service['texte'] !== null ? htmlspecialchars(substr($service['texte'], 0, 50)) : '(Aucun texte)'; ?>...</p>
                </div>
                <div class="card-image">
                    <?php if (isset($service['image']) && $service['image'] !== null): ?>
                        <img src="/Altiris/<?php echo htmlspecialchars($service['image']); ?>" alt="Image du service">
                    <?php else: ?>
                        <p>(Aucune image)</p>
                    <?php endif; ?>
                </div>
                <div class="card-actions">
                    <a href="edit.php?id=<?php echo isset($service['id']) ? $service['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                    <a href="delete.php?id=<?php echo isset($service['id']) ? $service['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php require_once '../../components/footer.php'; ?>