<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/MembreController.php';

$controller = new MembreController();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->create();
    if ($result === true) {
        $_SESSION['success'] = "Membre ajouté avec succès";
        header("Location: index.php");
        exit;
    } else {
        $error = $result;
    }
}
?>

<h2>Ajouter un membre</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Prénom *</label>
                <input type="text" name="prenom" class="form-control" 
                       value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
                <div class="invalid-feedback">Veuillez renseigner le prénom</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" 
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                <div class="invalid-feedback">Veuillez renseigner le nom</div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email *</label>
        <input type="email" name="email" class="form-control" 
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <div class="invalid-feedback">Veuillez renseigner un email valide</div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Rôle</label>
                <input type="text" name="role" class="form-control" 
                       value="<?= htmlspecialchars($_POST['role'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="actif" <?= ($_POST['statut'] ?? 'actif') === 'actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= ($_POST['statut'] ?? '') === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input type="tel" name="Tel" class="form-control" 
               value="<?= htmlspecialchars($_POST['Tel'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Compétences</label>
        <textarea name="competce_mbr" class="form-control" rows="3"><?= htmlspecialchars($_POST['competce_mbr'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Lien Facebook</label>
        <input type="url" name="lien_facebook" class="form-control" 
               value="<?= htmlspecialchars($_POST['lien_facebook'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png">
        <small class="form-text text-muted">Formats acceptés: JPG, PNG (max 2MB)</small>
        <div class="invalid-feedback">Veuillez sélectionner une photo valide</div>
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