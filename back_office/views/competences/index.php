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
$competences = $controller->index();

if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
    $_SESSION['success'] = "Compétence supprimée avec succès";
    header("Location: index.php");
    exit;
}
?>
<h2>Gestion des Compétences</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<a href="/Altiris/competences/ajouter" class="btn btn-primary mb-4"><i class="fas fa-plus"></i> Ajouter une compétence</a>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php if (empty($competences)): ?>
        <div class="col">
            <div class="alert alert-info text-center">Aucune compétence trouvée. Débogage : <?php var_dump($competences); ?></div>
        </div>
    <?php else: ?>
        <?php foreach ($competences as $competence): ?>
            <div class="col">
                <div class="card member-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <?php if (!empty($competence['image_path'])): ?>
                                <img src="/Altiris/<?= htmlspecialchars($competence['image_path']) ?>" 
                                     alt="Image de <?= htmlspecialchars($competence['nom']) ?>" 
                                     class="img-fluid">
                            <?php else: ?>
                                <div class="no-photo">Aucune image</div>
                            <?php endif; ?>
                        </div>
                        <div class="member-info">
                            <label>ID :</label> <?= htmlspecialchars($competence['id']) ?>
                        </div>
                        <div class="member-info">
                            <label>Nom :</label> <?= htmlspecialchars($competence['nom']) ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/Altiris/competences/modifier/<?= $competence['id'] ?>" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="?delete=<?= $competence['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>