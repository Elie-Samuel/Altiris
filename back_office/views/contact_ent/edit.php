<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ContactEntController.php';

$controller = new ContactEntController();
$error = null;

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

$contact = $controller->edit($id); // Récupère les données via edit()
if (!$contact) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->edit($id);
    if ($result === true) {
        $_SESSION['success'] = "Contact mis à jour avec succès";
        header("Location: index.php");
        exit;
    } else {
        $error = $result;
    }
}
?>

<h2>Modifier le contact #<?= htmlspecialchars($contact['id_ent'] ?? '') ?></h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
        <label class="form-label">Adresse</label>
        <input type="text" name="adresse" class="form-control" 
               value="<?= htmlspecialchars($contact['adresse'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Email *</label>
        <input type="email" name="mail" class="form-control" 
               value="<?= htmlspecialchars($contact['mail'] ?? '') ?>" required>
        <div class="invalid-feedback">Veuillez renseigner un email valide</div>
    </div>

    <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input type="tel" name="phonne" class="form-control" 
               value="<?= htmlspecialchars($contact['phonne'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Lien Facebook</label>
        <input type="url" name="lien_facebook" class="form-control" 
               value="<?= htmlspecialchars($contact['lien_facebook'] ?? '') ?>">
    </div>

    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="index.php" class="btn btn-secondary">Annuler</a>
</form>

<script>
// Activation de la validation Bootstrap
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