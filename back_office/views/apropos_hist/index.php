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
require_once dirname(__DIR__, 2) . '/controllers/AproposHistController.php';
$controller = new AproposHistController();
$history = $controller->index();
?>
<h2>Gestion de l'Historique À Propos</h2>
<a href="/Altiris/apropos-historique/ajouter" class="btn btn-primary mb-3">Ajouter un événement</a>
<div class="list-container">
    <?php if (empty($history)): ?>
        <p class="text-center">Aucun événement historique.</p>
    <?php else: ?>
        <ul class="history-list">
            <?php foreach ($history as $item): ?>
                <li class="history-item">
                    <div class="history-content">
                        <div class="history-id">ID: <?php echo isset($item['id']) ? htmlspecialchars($item['id']) : ''; ?></div>
                        <div class="history-titre"><?php echo isset($item['titre']) ? htmlspecialchars($item['titre']) : '(Aucun titre)'; ?></div>
                        <div class="history-text"><?php echo isset($item['text']) ? htmlspecialchars($item['text']) : '(Aucun texte)'; ?></div>
                        <div class="history-date">Date: <?php echo isset($item['date']) ? htmlspecialchars($item['date']) : '(Aucune date)'; ?></div>
                    </div>
                    <div class="history-actions">
                        <a href="/Altiris/apropos-historique/modifier/<?php echo isset($item['id']) ? $item['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="delete.php?id=<?php echo isset($item['id']) ? $item['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
