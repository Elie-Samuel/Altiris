<div class="container">
    <h2>Ajouter une Annonce</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">URL du média</label>
            <input type="url" name="url_media" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date de début</label>
            <input type="date" name="date_debut" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date de fin</label>
            <input type="date" name="date_fin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-control">
                <option value="brouillon">Brouillon</option>
                <option value="publie">Publié</option>
                <option value="archive">Archivé</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Catégorie</label>
            <input type="text" name="categorie" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>