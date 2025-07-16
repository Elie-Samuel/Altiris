<?php
// back_office/view/annonces/edit.php
require_once(__DIR__ . '/../../components/header.php');

// Récupérer les données de l'annonce (qui sont passées sous le nom 'annonce')
$annonce = $annonce ?? null; // Fournit une valeur par défaut si $annonce n'existe pas

// Debug: vérifier les données reçues (à supprimer en production)
echo '<pre>'; print_r($annonce); echo '</pre>';
?>

<div class="container mt-4">
    <h2>Modifier l'annonce</h2>
    <form method="POST" action="/Altiris/back_office/controllers/AnnoncesController.php?action=edit&id=<?= $annonce['id'] ?? '' ?>">
        <div class="form-group">
            <label for="titre">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" 
                   value="<?= htmlspecialchars($annonce['titre'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required><?= 
                htmlspecialchars($annonce['description'] ?? '') 
            ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="url_media">URL du média</label>
            <input type="url" class="form-control" id="url_media" name="url_media" 
                   value="<?= htmlspecialchars($annonce['url_media'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="date_debut">Date de début</label>
            <input type="date" class="form-control" id="date_debut" name="date_debut" 
                   value="<?= $annonce['date_debut'] ?? '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="date_fin">Date de fin</label>
            <input type="date" class="form-control" id="date_fin" name="date_fin" 
                   value="<?= $annonce['date_fin'] ?? '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="statut">Statut</label>
            <select class="form-control" id="statut" name="statut" required>
                <option value="brouillon" <?= ($annonce['statut'] ?? '') === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                <option value="publie" <?= ($annonce['statut'] ?? '') === 'publie' ? 'selected' : '' ?>>Publié</option>
                <option value="archive" <?= ($annonce['statut'] ?? '') === 'archive' ? 'selected' : '' ?>>Archivé</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="categorie">Catégorie</label>
            <input type="text" class="form-control" id="categorie" name="categorie" 
                   value="<?= htmlspecialchars($annonce['categorie'] ?? '') ?>">
        </div>
        
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<?php require_once(__DIR__ . '/../../components/footer.php'); ?>