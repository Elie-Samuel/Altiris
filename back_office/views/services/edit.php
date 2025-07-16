<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ServiceController.php';
$controller = new ServiceController();
$controller->edit($_GET['id'] ?? null);
?>
<h2>Modifier un Service</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo isset($service['titre']) && $service['titre'] !== null ? htmlspecialchars($service['titre']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label for="texte">Texte</label>
        <textarea class="form-control" id="texte" name="texte" required><?php echo isset($service['texte']) && $service['texte'] !== null ? htmlspecialchars($service['texte']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="image">Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        <?php if (isset($service['image']) && $service['image'] !== null): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($service['image']); ?>" width="100" class="mt-2">
        <?php else: ?>
            <p>(Aucune image)</p>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>
<?php require_once '../../components/footer.php'; ?>