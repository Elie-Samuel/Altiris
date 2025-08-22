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
require_once dirname(__DIR__, 2) . '/controllers/AproposDescController.php';
$controller = new AproposDescController();
$records = $controller->index();
?>
<h2>Gestion des Descriptions À Propos</h2>
<a href="/Altiris/apropos-description/ajouter" class="btn btn-primary mb-3">Ajouter une description</a>
<div class="list-container">
    <?php if (empty($records)): ?>
        <p class="text-center">Aucune description.</p>
    <?php else: ?>
        <ul class="records-list">
            <?php foreach ($records as $record): ?>
                <li class="record-item">
                    <div class="record-content">
                        <div class="record-id">ID: <?php echo isset($record['id']) ? htmlspecialchars($record['id']) : ''; ?></div>
                        <div class="record-titre">Titre: <?php echo isset($record['titre']) ? htmlspecialchars($record['titre']) : '(Aucun titre)'; ?></div>
                        <div class="record-sous-titre">Sous-titre: <?php echo isset($record['sous_titre']) ? htmlspecialchars($record['sous_titre']) : '(Aucun sous-titre)'; ?></div>
                        <div class="record-text">Texte: <?php echo isset($record['text']) ? htmlspecialchars($record['text']) : '(Aucun texte)'; ?></div>
                    </div>
                    <div class="record-actions">
                        <a href="/Altiris/apropos-description/modifier/<?php echo isset($record['id']) ? $record['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="delete.php?id=<?php echo isset($record['id']) ? $record['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
