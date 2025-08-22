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
$controller->create();
?>
<h2>Ajouter une Description À Propos</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="sous_titre">Sous-titre</label>
        <input type="text" class="form-control" id="sous_titre" name="sous_titre" required>
    </div>
    <div class="mb-3">
        <label for="text">Texte</label>
        <textarea class="form-control" id="text" name="text" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>