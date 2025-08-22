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
require_once dirname(__DIR__, 2) . '/controllers/AproposDescController.php';
$controller = new AproposDescController();
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
<h2>Modifier une Description À Propos</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo isset($record['titre']) ? htmlspecialchars($record['titre']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="sous_titre">Sous-titre</label>
        <input type="text" class="form-control" id="sous_titre" name="sous_titre" value="<?php echo isset($record['sous_titre']) ? htmlspecialchars($record['sous_titre']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="text">Texte</label>
        <textarea class="form-control" id="text" name="text" rows="5" required><?php echo isset($record['text']) ? htmlspecialchars($record['text']) : ''; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>
