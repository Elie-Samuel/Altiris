<?php
require_once '../../controllers/UtilisateurController.php';
$controller = new UtilisateurController();

// Gestion des actions avant toute sortie
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $search = isset($_GET['search']) ? "?search=" . urlencode($_GET['search']) : "";
    
    switch ($action) {
        case 'activate':
            $controller->changeStatus($id, 'Actif');
            header("Location: index.php" . $search);
            exit;
        case 'deactivate':
            $controller->changeStatus($id, 'Inactif');
            header("Location: index.php" . $search);
            exit;
        case 'delete':
            if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
                $controller->delete($id);
                header("Location: index.php" . $search);
                exit;
            }
            break;
    }
}

$utilisateurs = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $utilisateurs = $controller->search($_GET['search']);
} else {
    $utilisateurs = $controller->index();
}

require_once '../../components/header.php';
?>
<link rel="stylesheet" href="/Altiris/back_office/css/utilisateur.css">

<div class="page-header">
    <h2><i class="fas fa-user-shield"></i> Gestion des Utilisateurs</h2>
    <a href="/Altiris/utilisateurs/ajouter" class="btn btn-primary btn-lg">
        <i class="fas fa-plus"></i> Nouvel Utilisateur
    </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="search-container">
    <form method="GET" class="search-form">
        <input type="text" name="search" class="search-input form-control" placeholder="Rechercher un utilisateur..." 
               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
        </button>
        <?php if (isset($_GET['search'])): ?>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-times"></i>
            </a>
        <?php endif; ?>
    </form>
</div>

<div class="table-container fade-in-up">
    <div class="table-responsive">
        <table class="table table-striped" id="utilisateursTable">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($utilisateurs)): ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h3>Aucun utilisateur trouvé</h3>
                                <p>Commencez par créer votre premier utilisateur</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($utilisateurs as $user): ?>
                        <tr>
                            <td>
                                <?php 
                                $imagePath = !empty($user['profil']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $user['profil']) 
                                    ? '/Altiris/' . htmlspecialchars($user['profil']) 
                                    : '/Altiris/Assets/Images/default_profile.jpg';
                                ?>
                                <img src="<?php echo $imagePath; ?>" alt="Photo de profil" class="profile-img-table">
                            </td>
                            <td><?php echo htmlspecialchars($user['nom_utilisateur']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge <?php 
                                    echo $user['Types'] === 'Super admin' ? 'bg-danger' : 
                                        ($user['Types'] === 'Admin' ? 'bg-warning' : 'bg-info'); 
                                ?>">
                                    <?php echo htmlspecialchars($user['Types']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo ($user['status'] ?? 'Actif') === 'Actif' ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo htmlspecialchars($user['status'] ?? 'Actif'); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($user['date_creation']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/Altiris/utilisateurs/modifier/<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        Modifier
                                    </a>
                                    <?php if (($user['status'] ?? 'Actif') === 'Actif'): ?>
                                        <a href="?action=deactivate&id=<?php echo $user['id']; ?>" 
                                           class="btn btn-sm btn-secondary" title="Désactiver"
                                           onclick="return confirm('Désactiver cet utilisateur ?')">
                                            <i class="fas fa-user-slash"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="?action=delete&id=<?php echo $user['id']; ?>&confirm=yes" 
                                       class="btn btn-sm btn-danger" title="Supprimer"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                        Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
if (typeof $.fn.DataTable !== 'undefined') {
    $('#utilisateursTable').DataTable({
        "language": {
            "url": "/Altiris/Assets/js/dataTables.french.json"
        },
        "pageLength": 10,
        "order": [[5, "desc"]]
    });
}
</script>