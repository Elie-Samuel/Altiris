<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ServiceController.php';
$controller = new ServiceController();
$controller->create();
?>
<h2>Ajouter un Service</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label for="texte">Texte</label>
        <textarea class="form-control" id="texte" name="texte" required><?php echo isset($_POST['texte']) ? htmlspecialchars($_POST['texte']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="image">Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>
<?php require_once '../../components/footer.php'; ?>