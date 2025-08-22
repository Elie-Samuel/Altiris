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
require_once dirname(__DIR__, 2) . '/controllers/AproposMissionController.php';
$controller = new AproposMissionController();
$controller->create();
?>
<h2>Ajouter une Mission À Propos</h2>
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
        <textarea class="form-control" id="text" name="text" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label for="icon_bootstrap">Icône Font Awesome</label>
        <input type="text" class="form-control" id="icon_bootstrap" name="icon_bootstrap" required>
        <small class="form-text text-muted">
            Entrez une classe Font Awesome valide (ex: <code>fas fa-rocket</code>). Consultez <a href="https://fontawesome.com/v5/cheatsheet" target="_blank">Font Awesome Cheatsheet</a> pour plus d'icônes.
        </small>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>