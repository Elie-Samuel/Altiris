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
require_once dirname(__DIR__, 2) . '/controllers/AproposMissionController.php';
$controller = new AproposMissionController();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !is_numeric($id) || $id <= 0) {
    header("Location: index.php");
    exit;
}

$controller->edit($id);
global $record;
if (!$record) {
    header("Location: index.php");
    exit;
}
require_once '../../components/header.php';
?>
<h2>Modifier une Mission À Propos</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo isset($record['titre']) ? htmlspecialchars($record['titre']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="text">Texte</label>
        <textarea class="form-control" id="text" name="text" rows="5" required><?php echo isset($record['text']) ? htmlspecialchars($record['text']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="icon_bootstrap">Icône Font Awesome</label>
        <input type="text" class="form-control" id="icon_bootstrap" name="icon_bootstrap" value="<?php echo isset($record['icon_bootstrap']) ? htmlspecialchars($record['icon_bootstrap']) : ''; ?>" required>
        <small class="form-text text-muted">
            Entrez une classe Font Awesome valide (ex: <code>fas fa-rocket</code>). Consultez <a href="https://fontawesome.com/v5/cheatsheet" target="_blank">Font Awesome Cheatsheet</a> pour plus d'icônes.
            Icône actuelle: <i class="<?php echo isset($record['icon_bootstrap']) ? htmlspecialchars($record['icon_bootstrap']) : ''; ?>"></i>
        </small>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>