<div class="container">
    <h2>Gestion des Paramètres</h2>
    <form method="POST">
        <?php foreach ($parametres as $param): ?>
            <div class="mb-3">
                <label class="form-label"><?= htmlspecialchars($param['cle']) ?></label>
                <input type="hidden" name="cle" value="<?= htmlspecialchars($param['cle']) ?>">
                <input type="text" name="valeur" class="form-control" value="<?= htmlspecialchars($param['valeur']) ?>" required>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>