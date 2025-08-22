<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/controllers/BlogPostController.php';
$controller = new BlogPostController();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !is_numeric($id) || $id <= 0) {
    header("Location: index.php");
    exit;
}

$controller->edit($id);
global $post;
if (!$post) {
    header("Location: index.php");
    exit;
}
$categories = ['technologie', 'web', 'application', 'jeux', 'design'];
require_once '../../components/header.php';
?>
<h2>Modifier un Article de Blog</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title">Titre</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($post['title']) ? htmlspecialchars($post['title']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="category">Catégorie</label>
        <select class="form-control" id="category" name="category" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo isset($post['category']) && $post['category'] === $cat ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="excerpt">Extrait</label>
        <textarea class="form-control" id="excerpt" name="excerpt" required><?php echo isset($post['excerpt']) ? htmlspecialchars($post['excerpt']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="content">Contenu</label>
        <textarea class="form-control" id="content" name="content" rows="10" required><?php echo isset($post['content']) ? htmlspecialchars($post['content']) : ''; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="image">Image (optionnel, JPG/PNG, max 5 Mo)</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/jpg,image/png">
        <?php if (isset($post['image_path']) && $post['image_path'] && file_exists(dirname(__DIR__, 2) . '/' . $post['image_path'])): ?>
            <div class="mt-2">
                <p>Image actuelle :</p>
                <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Image actuelle" style="max-width: 100px; max-height: 100px;">
            </div>
        <?php endif; ?>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="published" name="published" <?php echo isset($post['published']) && $post['published'] ? 'checked' : ''; ?>>
        <label class="form-check-label" for="published">Publié</label>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>