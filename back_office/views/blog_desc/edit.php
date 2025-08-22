<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/controllers/BlogDescController.php';
$controller = new BlogDescController();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !is_numeric($id) || $id <= 0) {
    header("Location: /Altiris/description-blog");
    exit;
}

global $blogDesc;
$blogDesc = $controller->model->read($id);
if (!$blogDesc) {
    header("Location: /Altiris/description-blog");
    exit;
}

require_once '../../components/header.php';
$controller->edit($id);
?>
<h2>Modifier la Description du Blog</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo isset($blogDesc['titre']) ? htmlspecialchars($blogDesc['titre']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="sous_titre">Sous-titre</label>
        <input type="text" class="form-control" id="sous_titre" name="sous_titre" value="<?php echo isset($blogDesc['sous_titre']) ? htmlspecialchars($blogDesc['sous_titre']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" required><?php echo isset($blogDesc['description']) ? htmlspecialchars($blogDesc['description']) : ''; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>