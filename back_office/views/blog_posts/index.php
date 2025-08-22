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
$posts = $controller->index();
?>
<h2>Gestion des Articles de Blog</h2>
<a href="/Altiris/articles-blog/ajouter" class="btn btn-primary mb-3">Ajouter un article</a>
<div class="list-container">
    <?php if (empty($posts)): ?>
        <p class="text-center">Aucun article.</p>
    <?php else: ?>
        <ul class="posts-list">
            <?php foreach ($posts as $post): ?>
                <li class="post-item">
                    <div class="post-content">
                        <div class="post-id">ID: <?php echo isset($post['id']) ? htmlspecialchars($post['id']) : ''; ?></div>
                        <div class="post-title">Titre: <?php echo isset($post['title']) ? htmlspecialchars($post['title']) : '(Aucun titre)'; ?></div>
                        <div class="post-category">Catégorie: <?php echo isset($post['category']) ? htmlspecialchars($post['category']) : '(Aucune catégorie)'; ?></div>
                        <div class="post-excerpt">Extrait: <?php echo isset($post['excerpt']) ? htmlspecialchars($post['excerpt']) : '(Aucun extrait)'; ?></div>
                        <div class="post-published">Publié: <?php echo isset($post['published']) && $post['published'] ? 'Oui' : 'Non'; ?></div>
                      <div class="post-image">
                            <?php if (isset($post['image_path']) && $post['image_path'] !== null): ?>
                                <img src="/Altiris/<?php echo htmlspecialchars($post['image_path']); ?>" alt="Image de l'article" width="100">
                            <?php else: ?>
                                <span>(Aucune image)</span>
                            <?php endif; ?>
                        </div>
                        <div class="post-created">Créé: <?php echo isset($post['created_at']) ? htmlspecialchars($post['created_at']) : '(Inconnu)'; ?></div>
                        <div class="post-updated">Mis à jour: <?php echo isset($post['updated_at']) ? htmlspecialchars($post['updated_at']) : '(Inconnu)'; ?></div>
                    </div>
                    <div class="post-actions">
                        <a href="/Altiris/articles-blog/modifier/<?php echo isset($post['id']) ? $post['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                        <a href="delete.php?id=<?php echo isset($post['id']) ? $post['id'] : ''; ?>" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>