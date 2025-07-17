<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ContacteController.php';

$controller = new ContacteController();
$contacts = $controller->index();
?>

<h2>Gestion des demandes de rendez-vous</h2>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Sujet</th>
                <th>Texte</th>
                <th>Adresse</th>
                <th>Réponse</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($contacts)): ?>
                <tr><td colspan="9" class="text-center">Aucune demande trouvée</td></tr>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['id_cont'] ?? '') ?></td>
                        <td><?= htmlspecialchars($contact['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($contact['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($contact['tel'] ?? '') ?></td>
                        <td><?= htmlspecialchars($contact['subject'] ?? '') ?></td>
                        <td><?= htmlspecialchars($contact['text'] ?? '') ?></td>
                        <td><?= htmlspecialchars($contact['adresse'] ?? '') ?></td>
                        <td><?= htmlspecialchars($contact['response'] ?? 'Non répondu') ?></td>
                        <td>
                            <a href="edit.php?id=<?= $contact['id_cont'] ?>" class="btn btn-sm btn-primary">Répondre</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../components/footer.php'; ?>