<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ActualiterController.php';
$controller = new ActualiterController();
$actualiters = $controller->index();
?>
<h2>Gestion des Actualités</h2>
<a href="/Altiris/actualites/ajouter" class="btn btn-primary mb-3">Ajouter une actualité</a>
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
                        <img src="/Altiris/<?php echo htmlspecialchars($actualiter['image']); ?>" alt="Image de l'actualité">
                    <?php else: ?>
                        <p>Pas d'image</p>
                    <?php endif; ?>
                </div>
                <div class="card-actions">
                <a href="/Altiris/actualites/modifier/<?php echo isset($actualiter['id']) ? $actualiter['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                <a href="delete.php?id=<?php echo isset($actualiter['id']) ? $actualiter['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>