<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/MembreController.php';

$controller = new MembreController();
$error = null;

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

$membre = $controller->edit($id);
if ($membre === false || !is_array($membre)) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->edit($id);
    if ($result === true) {
        $_SESSION['success'] = "Membre mis à jour avec succès";
        header("Location: index.php");
        exit;
    } else {
        $error = $result;
        // Réutiliser $membre existant au lieu de recharger
    }
}
?>

<h2>Modifier le membre #<?= htmlspecialchars($membre['id_membre'] ?? 'Invalide') ?></h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Prénom *</label>
                <input type="text" name="prenom" class="form-control" 
                       value="<?= htmlspecialchars($membre['prenom'] ?? '') ?>" required>
                <div class="invalid-feedback">Veuillez renseigner le prénom</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" class="form-control" 
                       value="<?= htmlspecialchars($membre['nom'] ?? '') ?>" required>
                <div class="invalid-feedback">Veuillez renseigner le nom</div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email *</label>
        <input type="email" name="email" class="form-control" 
               value="<?= htmlspecialchars($membre['email'] ?? '') ?>" required>
        <div class="invalid-feedback">Veuillez renseigner un email valide</div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-select">
                    <option value="utilisateur" <?= isset($membre['role']) && $membre['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                    <option value="admin" <?= isset($membre['role']) && $membre['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="actif" <?= isset($membre['statut']) && $membre['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= isset($membre['statut']) && $membre['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input type="tel" name="Tel" class="form-control" 
               value="<?= htmlspecialchars($membre['Tel'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Compétences</label>
        <textarea name="competce_mbr" class="form-control" rows="3"><?= htmlspecialchars($membre['competce_mbr'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Lien Facebook</label>
        <input type="url" name="lien_facebook" class="form-control" 
               value="<?= htmlspecialchars($membre['lien_facebook'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Photo</label>
        <?php if (isset($membre['photo']) && !empty($membre['photo'])): ?>
            <div class="mb-2">
                <img src="/Altiris/<?= htmlspecialchars($membre['photo']) ?>" 
                     alt="Photo de <?= htmlspecialchars($membre['prenom'] ?? 'Membre') ?>" 
                     width="100" class="img-thumbnail object-fit-cover">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo">
                    <label class="form-check-label" for="remove_photo">Supprimer la photo actuelle</label>
                </div>
            </div>
        <?php endif; ?>
        <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png">
        <small class="form-text text-muted">Laisser vide pour conserver la photo actuelle</small>
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