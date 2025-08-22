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
require_once dirname(__DIR__, 2) . '/controllers/ServiceController.php';
$controller = new ServiceController();
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id) || $id <= 0) {
    header("Location: /Altiris/services");
    exit;
}

require_once '../../components/header.php';
$controller->edit($id);
global $service;
if (!$service) {
    header("Location: /Altiris/services");
    exit;
}
// Charger les données initiales ou modifiées via edit()
$controller->edit($id);
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
            <img src="/Altiris/<?php echo htmlspecialchars($service['image']); ?>" width="100" class="mt-2">
        <?php else: ?>
            <p>(Aucune image)</p>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>