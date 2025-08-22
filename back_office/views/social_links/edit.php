<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/connexion");
    exit;
}
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/controllers/SocialLinksController.php';
$controller = new SocialLinksController();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !is_numeric($id) || $id <= 0) {
    header("Location: /Altiris/liens-sociaux");
    exit;
}

global $link;
$link = $controller->model->read($id);
if (!$link) {
    header("Location: /Altiris/liens-sociaux");
    exit;
}

require_once '../../components/header.php';
$controller->edit($id);
?>
<h2>Modifier un Lien Social</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="platform">Plateforme</label>
        <input type="text" class="form-control" id="platform" name="platform" value="<?php echo isset($link['platform']) ? htmlspecialchars($link['platform']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="url">URL</label>
        <input type="url" class="form-control" id="url" name="url" value="<?php echo isset($link['url']) ? htmlspecialchars($link['url']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="icon_class">Icône FontAwesome (ex: fas fa-facebook)</label>
        <input type="text" class="form-control" id="icon_class" name="icon_class" value="<?php echo isset($link['icon_class']) ? htmlspecialchars($link['icon_class']) : ''; ?>" placeholder="Optionnel">
    </div>
    <div class="mb-3">
        <label for="order_position">Position de tri</label>
        <input type="number" class="form-control" id="order_position" name="order_position" value="<?php echo isset($link['order_position']) ? htmlspecialchars($link['order_position']) : ''; ?>" min="0">
    </div>
    <div class="mb-3">
        <label for="is_active">Actif</label>
        <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo isset($link['is_active']) && $link['is_active'] ? 'checked' : ''; ?>>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>