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
require_once dirname(__DIR__, 2) . '/controllers/LegalPagesController.php';
$controller = new LegalPagesController();
$pages = $controller->index();
?>
<h2>Gestion des Pages Légales</h2>
<a href="/Altiris/pages-legales/ajouter" class="btn btn-primary mb-3">Ajouter une page</a>
<div class="list-container">
    <?php if (empty($pages)): ?>
        <p class="text-center">Aucune page légale.</p>
    <?php else: ?>
        <ul class="pages-list">
            <?php foreach ($pages as $page): ?>
                <li class="page-item">
                    <div class="page-content">
                        <div class="page-id">ID: <?php echo isset($page['id']) ? htmlspecialchars($page['id']) : ''; ?></div>
                        <div class="page-title"><?php echo isset($page['title']) ? htmlspecialchars($page['title']) : '(Aucun titre)'; ?></div>
                        <div class="page-slug"><?php echo isset($page['slug']) ? htmlspecialchars($page['slug']) : '(Aucun slug)'; ?></div>
                        <div class="page-content"><?php echo isset($page['content']) ? htmlspecialchars(substr($page['content'], 0, 100)) . '...' : '(Aucun contenu)'; ?></div>
                        <div class="page-is-active">Actif: <?php echo isset($page['is_active']) && $page['is_active'] ? 'Oui' : 'Non'; ?></div>
                    </div>
                    <div class="page-actions">
                        <a href="/Altiris/pages-legales/modifier/<?php echo isset($page['id']) ? $page['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="delete.php?id=<?php echo isset($page['id']) ? $page['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>