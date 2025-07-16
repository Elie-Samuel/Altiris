<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ActualiterController.php';
$controller = new ActualiterController();
$controller->create();
?>
<h2>Ajouter une Actualité</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="Date">Date (optionnel)</label>
        <input type="date" class="form-control" id="Date" name="Date">
    </div>
    <div class="mb-3">
        <label for="texte">Texte</label>
        <textarea class="form-control" id="texte" name="texte" required></textarea>
    </div>
    <div class="mb-3">
        <label for="image">Image (obligatoire)</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>
<?php require_once '../../components/footer.php'; ?>