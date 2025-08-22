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
require_once dirname(__DIR__, 2) . '/controllers/AproposHistController.php';
$controller = new AproposHistController();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !is_numeric($id) || $id <= 0) {
    header("Location: index.php");
    exit;
}

global $history;
$history = $controller->model->read($id);
if (!$history) {
    header("Location: index.php");
    exit;
}

require_once '../../components/header.php';
$controller->edit($id);
?>
<h2>Modifier un Événement Historique</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo isset($history['titre']) ? htmlspecialchars($history['titre']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="text">Texte</label>
        <textarea class="form-control" id="text" name="text" required><?php echo isset($history['text']) ? htmlspecialchars($history['text']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" name="date" value="<?php echo isset($history['date']) ? htmlspecialchars($history['date']) : ''; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>