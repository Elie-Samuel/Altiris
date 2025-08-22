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
$controller->create();
?>
<h2>Ajouter un Événement Historique</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="text">Texte</label>
        <textarea class="form-control" id="text" name="text" required></textarea>
    </div>
    <div class="mb-3">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" name="date" required>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>