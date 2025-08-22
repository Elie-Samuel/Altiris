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
require_once dirname(__DIR__, 2) . '/controllers/CompetenceController.php';

$controller = new CompetenceController();
$controller->edit($_GET['id'] ?? 0);
?>
<h2>Modifier une Compétence</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $competence['id'] ?>">
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($competence['nom']) ?>" required>
    </div>
    <div class="form-group">
        <label for="image">Image (laissez vide pour conserver l'existante)</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        <?php if ($competence['image_path']): ?>
            <p>Image actuelle : <img src="/Altiris/<?= htmlspecialchars($competence['image_path']) ?>" alt="Image actuelle" style="max-width: 200px;"></p>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/Altiris/competences" class="btn btn-secondary">Retour</a>
</form>