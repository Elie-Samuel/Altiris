<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/connexion");
    exit;
}
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ActualiterController.php';
$controller = new ActualiterController();
$controller->edit($_GET['id'] ?? null);
?>
<h2>Modifier une Actualité</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="Date">Date (optionnel)</label>
        <input type="date" class="form-control" id="Date" name="Date" value="<?php echo htmlspecialchars($actualiter['Date'] ?? ''); ?>">
    </div>
    <div class="mb-3">
        <label for="texte">Texte</label>
        <textarea class="form-control" id="texte" name="texte" required><?php echo htmlspecialchars($actualiter['texte'] ?? ''); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="image">Image (optionnel)</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        <?php if ($actualiter['image']): ?>
            <img src="/Altiris/<?php echo htmlspecialchars($actualiter['image']); ?>" style="max-width: 100px; margin-top: 10px;">
        <?php else: ?>
            <p>Pas d'image</p>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>
