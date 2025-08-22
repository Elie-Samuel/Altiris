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
require_once dirname(__DIR__, 2) . '/controllers/SocialLinksController.php';
$controller = new SocialLinksController();
$links = $controller->index();
?>
<h2>Gestion des Liens Sociaux</h2>
<a href="/Altiris/liens-sociaux/ajouter" class="btn btn-primary mb-3">Ajouter un lien</a>
<div class="list-container">
    <?php if (empty($links)): ?>
        <p class="text-center">Aucun lien social.</p>
    <?php else: ?>
        <ul class="links-list">
            <?php foreach ($links as $link): ?>
                <li class="link-item">
                    <div class="link-content">
                        <div class="link-id">ID: <?php echo isset($link['id']) ? htmlspecialchars($link['id']) : ''; ?></div>
                        <div class="link-platform"><?php echo isset($link['platform']) ? htmlspecialchars($link['platform']) : '(Aucune plateforme)'; ?></div>
                        <div class="link-url"><a href="<?php echo isset($link['url']) ? htmlspecialchars($link['url']) : '#'; ?>" target="_blank"><?php echo isset($link['url']) ? htmlspecialchars($link['url']) : '(Aucune URL)'; ?></a></div>
                        <div class="link-is-active">Actif: <?php echo isset($link['is_active']) && $link['is_active'] ? 'Oui' : 'Non'; ?></div>
                        <div class="link-order">Position: <?php echo isset($link['order_position']) ? htmlspecialchars($link['order_position']) : '(Non défini)'; ?></div>
                        <div class="link-icon">
                            <?php if (isset($link['icon_class']) && $link['icon_class']): ?>
                                <i class="<?php echo htmlspecialchars($link['icon_class']); ?>"></i>
                            <?php else: ?>
                                <span>(Aucune icône)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="link-actions">
                        <a href="/Altiris/liens-sociaux/modifier/<?php echo isset($link['id']) ? $link['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="delete.php?id=<?php echo isset($link['id']) ? $link['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>