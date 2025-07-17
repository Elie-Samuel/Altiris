<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/AnnonceController.php';
$controller = new AnnonceController();
$id = $_GET['id'];

// Charger les données initiales ou modifiées via edit()
$controller->edit($id);
?>
<h2>Modifier une Annonce</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="text">Texte</label>
        <textarea class="form-control" id="text" name="text" required><?php echo isset($annonce['text']) && $annonce['text'] !== null ? htmlspecialchars($annonce['text']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="image">Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        <?php if (isset($annonce['image']) && $annonce['image'] !== null): ?>
            <img src="/Altiris/<?php echo htmlspecialchars($annonce['image']); ?>" width="100" class="mt-2">
        <?php else: ?>
            <p>(Aucune image)</p>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>
<?php require_once '../../components/footer.php'; ?>