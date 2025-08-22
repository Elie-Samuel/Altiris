<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/connexion");
    exit;
}
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/BlogDescController.php';
$controller = new BlogDescController();
$blogDesc = $controller->index();
?>
<h2>Gestion de la Description du Blog</h2>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<a href="/Altiris/description-blog/ajouter" class="btn btn-primary mb-4">
    <i class="fas fa-plus"></i> ajouter une description
</a>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php if (empty($blogDesc)): ?>
        <div class="col">
            <div class="alert alert-info text-center">Aucune description trouvée</div>
        </div>
    <?php else: ?>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo isset($blogDesc['titre']) ? htmlspecialchars($blogDesc['titre']) : '(Aucun titre)'; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo isset($blogDesc['sous_titre']) ? htmlspecialchars($blogDesc['sous_titre']) : '(Aucun sous-titre)'; ?></h6>
                    <p class="card-text"><?php echo isset($blogDesc['description']) ? htmlspecialchars($blogDesc['description']) : '(Aucune description)'; ?></p>
                    <div class="card-actions">
                        <a href="/Altiris/description-blog/modifier/<?php echo isset($blogDesc['id']) ? $blogDesc['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="delete.php?id=<?php echo isset($blogDesc['id']) ? $blogDesc['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>