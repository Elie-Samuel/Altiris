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
$controller->create();
?>
<h2>Ajouter une Page Légale</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="title">Titre</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="slug">Slug (lettres minuscules, chiffres, tirets)</label>
        <input type="text" class="form-control" id="slug" name="slug" required>
    </div>
    <div class="mb-3">
        <label for="content">Contenu</label>
        <textarea class="form-control" id="content" name="content" required></textarea>
    </div>
    <div class="mb-3">
        <label for="is_active">Actif</label>
        <input type="checkbox" id="is_active" name="is_active" value="1" checked>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>