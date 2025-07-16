<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/MembreController.php';

$controller = new MembreController();
$membres = $controller->index();

if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
    $_SESSION['success'] = "Membre supprimé avec succès";
    header("Location: index.php");
    exit;
}
?>

<link rel="stylesheet" href="/Altiris/back_office/css/style.css">

<h2>Gestion des membres</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<a href="create.php" class="btn btn-primary mb-4">
    <i class="fas fa-plus"></i> Ajouter un membre
</a>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php if (empty($membres)): ?>
        <div class="col">
            <div class="alert alert-info text-center">Aucun membre trouvé</div>
        </div>
    <?php else: ?>
        <?php foreach ($membres as $membre): ?>
            <div class="col">
                <div class="card member-card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <?php if (!empty($membre['photo'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($membre['photo']) ?>" 
                                     alt="Photo de <?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?>" 
                                     class="img-fluid">
                            <?php else: ?>
                                <div class="no-photo">Aucune photo</div>
                            <?php endif; ?>
                        </div>
                        <div class="member-info">
                            <label>ID :</label> <?= htmlspecialchars($membre['id_membre']) ?>
                        </div>
                        <div class="member-info">
                            <label>Prénom :</label> <?= htmlspecialchars($membre['prenom']) ?>
                        </div>
                        <div class="member-info">
                            <label>Nom :</label> <?= htmlspecialchars($membre['nom']) ?>
                        </div>
                        <div class="member-info">
                            <label>Email :</label> <?= htmlspecialchars($membre['email']) ?>
                        </div>
                        <div class="member-info">
                            <label>Rôle :</label> <?= htmlspecialchars($membre['role']) ?>
                        </div>
                        <div class="member-info">
                            <label>Statut :</label>
                            <span class="badge bg-<?= $membre['statut'] === 'actif' ? 'success' : 'secondary' ?>">
                                <?= htmlspecialchars($membre['statut']) ?>
                            </span>
                        </div>
                        <div class="member-info">
                            <label>Téléphone :</label> <?= htmlspecialchars($membre['Tel'] ?? '-') ?>
                        </div>
                        <div class="member-info">
                            <label>Compétences :</label> <?= htmlspecialchars($membre['competce_mbr'] ?? '-') ?>
                        </div>
                        <div class="member-info">
                            <label>Lien Facebook :</label> <?= htmlspecialchars($membre['lien_facebook'] ?? '-') ?>
                        </div>
                        <div class="member-info">
                            <label>Date de création :</label> <?= htmlspecialchars($membre['date_creation']) ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="edit.php?id=<?= $membre['id_membre'] ?>" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="?delete=<?= $membre['id_membre'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once '../../components/footer.php'; ?>