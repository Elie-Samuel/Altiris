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
$controller->create();
?>
<h2>Ajouter un Lien Social</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="platform">Plateforme</label>
        <input type="text" class="form-control" id="platform" name="platform" required>
    </div>
    <div class="mb-3">
        <label for="url">URL</label>
        <input type="url" class="form-control" id="url" name="url" required>
    </div>
    <div class="mb-3">
        <label for="icon_class">Icône FontAwesome (ex: fas fa-facebook)</label>
        <input type="text" class="form-control" id="icon_class" name="icon_class" placeholder="Optionnel">
    </div>
    <div class="mb-3">
        <label for="order_position">Position de tri</label>
        <input type="number" class="form-control" id="order_position" name="order_position" min="0">
    </div>
    <div class="mb-3">
        <label for="is_active">Actif</label>
        <input type="checkbox" id="is_active" name="is_active" value="1" checked>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>