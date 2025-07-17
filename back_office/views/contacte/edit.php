<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ContacteController.php';

$controller = new ContacteController();
$error = null;

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

$contact = $controller->edit($id);
if ($contact === false || !is_array($contact)) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->edit($id);
    if ($result === true) {
        $_SESSION['success'] = "Réponse envoyée avec succès";
        header("Location: index.php");
        exit;
    } else {
        $error = $result;
    }
}

$success = isset($_GET['success']) && $_GET['success'] == 1;
?>

<h2>Répondre à la demande #<?= htmlspecialchars($contact['id_cont'] ?? 'Invalide') ?></h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success">Réponse envoyée avec succès !</div>
<?php endif; ?>

<form method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($contact['name'] ?? '') ?>" disabled>
    </div>
    <!-- (Autres champs inchangés) -->
    <div class="mb-3">
        <label class="form-label">Réponse</label>
        <select name="response" class="form-select" required>
            <option value="">Choisir une réponse</option>
            <option value="accepté">Accepter</option>
            <option value="refusé">Refuser</option>
        </select>
        <div class="invalid-feedback">Veuillez sélectionner une réponse</div>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer la réponse</button>
    <a href="index.php" class="btn btn-secondary">Annuler</a>
</form>

<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

<?php require_once '../../components/footer.php'; ?>