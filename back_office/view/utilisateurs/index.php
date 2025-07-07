<div class="container">
    <h2>Gestion des Utilisateurs</h2>
    <a href="<?php echo BASE_URL; ?>utilisateurs/create" class="btn btn-primary mb-3">Ajouter un utilisateur</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= htmlspecialchars($utilisateur['nom_utilisateur']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>utilisateurs/edit?id=<?= $utilisateur['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="<?php echo BASE_URL; ?>utilisateurs/delete?id=<?= $utilisateur['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>