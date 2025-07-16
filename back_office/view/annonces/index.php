<?php
// back_office/view/annonces/index.php
require_once(__DIR__ . '/../../components/header.php');
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Gestion des annonces</h2>
        <a href="/Altiris/back_office/controllers/AnnoncesController.php?action=create" 
           class="btn btn-primary">
           <i class="fas fa-plus me-2"></i> Nouvelle annonce
        </a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Créé par</th>
                <th>Dates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($annonces as $annonce): ?>
            <tr>
                <td><?= htmlspecialchars($annonce['titre'] ?? '') ?></td>
                <td><?= htmlspecialchars($annonce['categorie'] ?? '') ?></td>
                <td><?= htmlspecialchars($annonce['nom_utilisateur'] ?? '') ?></td>
                <td>
                    <?= date('d/m/Y', strtotime($annonce['date_debut'] ?? '')) ?> - 
                    <?= date('d/m/Y', strtotime($annonce['date_fin'] ?? '')) ?>
                </td>
                <td>
                    <a href="/Altiris/back_office/controllers/AnnoncesController.php?action=edit&id=<?= $annonce['id'] ?? '' ?>" 
                       class="btn btn-sm btn-outline-primary me-1">
                       <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination-container mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Précédent</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Suivant</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<?php require_once(__DIR__ . '/../../components/footer.php'); ?>