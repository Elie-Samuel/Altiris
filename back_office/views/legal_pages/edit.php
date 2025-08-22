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
require_once dirname(__DIR__, 2) . '/controllers/LegalPagesController.php';
$controller = new LegalPagesController();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !is_numeric($id) || $id <= 0) {
    header("Location: /Altiris/pages-legales");
    exit;
}

global $page;
$page = $controller->model->read($id);
if (!$page) {
    header("Location: /Altiris/pages-legales");
    exit;
}

require_once '../../components/header.php';
$controller->edit($id);
?>
<h2>Modifier une Page Légale</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="title">Titre</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($page['title']) ? htmlspecialchars($page['title']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="slug">Slug (lettres minuscules, chiffres, tirets)</label>
        <input type="text" class="form-control" id="slug" name="slug" value="<?php echo isset($page['slug']) ? htmlspecialchars($page['slug']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="content">Contenu</label>
        <textarea class="form-control" id="content" name="content" required><?php echo isset($page['content']) ? htmlspecialchars($page['content']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="is_active">Actif</label>
        <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo isset($page['is_active']) && $page['is_active'] ? 'checked' : ''; ?>>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>