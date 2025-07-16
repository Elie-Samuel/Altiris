<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ContactEntController.php';

$controller = new ContactEntController();
$contacts = $controller->index();

if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
    $_SESSION['success'] = "Contact supprimé avec succès";
    header("Location: index.php");
    exit;
}
?>

<link rel="stylesheet" href="/Altiris/back_office/css/style.css">

<h2>Gestion des contacts entreprises</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<a href="create.php" class="btn btn-primary mb-4">
    <i class="fas fa-plus"></i> Ajouter un contact
</a>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php if (empty($contacts)): ?>
        <div class="col">
            <div class="alert alert-info text-center">Aucun contact trouvé</div>
        </div>
    <?php else: ?>
        <?php foreach ($contacts as $contact): ?>
            <div class="col">
                <div class="card contact-card">
                    <div class="card-body">
                        <div class="contact-info">
                            <label>ID :</label> <?= htmlspecialchars($contact['id_ent']) ?>
                        </div>
                        <div class="contact-info">
                            <label>Adresse :</label> <?= htmlspecialchars($contact['adresse'] ?? '-') ?>
                        </div>
                        <div class="contact-info">
                            <label>Email :</label> <?= htmlspecialchars($contact['mail'] ?? '-') ?>
                        </div>
                        <div class="contact-info">
                            <label>Téléphone :</label> <?= htmlspecialchars($contact['phonne'] ?? '-') ?>
                        </div>
                        <div class="contact-info">
                            <label>Lien Facebook :</label> <?= htmlspecialchars($contact['lien_facebook'] ?? '-') ?>
                        </div>
                        <div class="contact-info">
                            <label>Date de création :</label> <?= htmlspecialchars($contact['date_creation']) ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="edit.php?id=<?= $contact['id_ent'] ?>" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="?delete=<?= $contact['id_ent'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')">
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