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
require_once dirname(__DIR__, 2) . '/controllers/BlogPostController.php';
$controller = new BlogPostController();
$controller->create();
$categories = ['technologie', 'web', 'application', 'jeux', 'design'];
?>
<h2>Ajouter un Article de Blog</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title">Titre</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="category">Catégorie</label>
        <select class="form-control" id="category" name="category" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $cat === 'technologie' ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="excerpt">Extrait</label>
        <textarea class="form-control" id="excerpt" name="excerpt" required></textarea>
    </div>
    <div class="mb-3">
        <label for="content">Contenu</label>
        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
    </div>
    <div class="mb-3">
        <label for="image">Image (optionnel, JPG/PNG, max 5 Mo)</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/jpg,image/png">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="published" name="published" checked>
        <label class="form-check-label" for="published">Publié</label>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>