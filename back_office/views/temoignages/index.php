<?php
ob_start();
session_start();
require_once dirname(__DIR__, 2) . '/controllers/TemoignageController.php';
$controller = new TemoignageController();
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Gestion des actions avant toute sortie
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'delete' && isset($_GET['id']) && isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        $id = (int)$_GET['id'];
        $controller->delete($id);
        header("Location: index.php?page=$page" . ($search ? "&search=" . urlencode($search) : ""));
        exit;
    } elseif ($action === 'export') {
        $controller->exportCSV();
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_multiple') {
    $controller->deleteMultiple();
    header("Location: index.php?page=$page" . ($search ? "&search=" . urlencode($search) : ""));
    exit;
}

// Récupération des témoignages
$data = $search ? $controller->search($search, $page) : $controller->index($page);
$temoignages = $data['temoignages'] ?? [];
$total = $data['total'] ?? 0;
$perPage = $data['perPage'] ?? 10;
$currentPage = $data['currentPage'] ?? 1;
$totalPages = $perPage > 0 ? ceil($total / $perPage) : 1;

// Statistiques
$totalTemoignages = $total;
$recentTemoignages = array_filter($temoignages, function($tem) {
    return isset($tem['date_creation']) && strtotime($tem['date_creation']) > strtotime('-30 days');
});
$recentCount = count($recentTemoignages);

require_once dirname(__DIR__, 2) . '/components/header.php';
?>

<link rel="stylesheet" href="/Altiris/back_office/css/temoignage.css">

<div class="page-header">
    <h2><i class="fas fa-quote-left"></i> Gestion des Témoignages</h2>
    <div class="header-actions">
        <a href="/Altiris/temoignages/ajouter" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Nouveau Témoignage
        </a>
        <a href="?action=export" class="btn btn-secondary btn-lg">
            <i class="fas fa-download"></i> Exporter CSV
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalTemoignages; ?></div>
        <div class="stat-label">Total Témoignages</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $recentCount; ?></div>
        <div class="stat-label">Ce mois-ci</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo count($temoignages); ?></div>
        <div class="stat-label">Affichés</div>
    </div>
</div>

<!-- Messages de feedback -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Barre de recherche -->
<div class="search-container">
    <form method="GET" class="search-form">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="search-input form-control" placeholder="Rechercher par nom, rang ou contenu..." 
                   value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Rechercher
        </button>
        <?php if ($search): ?>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Réinitialiser
            </a>
        <?php endif; ?>
    </form>
</div>

<form method="POST" id="bulkDeleteForm">
    <input type="hidden" name="action" value="delete_multiple">
    <div class="bulk-actions">
        <button type="submit" class="btn btn-danger" id="bulkDeleteBtn" disabled 
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer les témoignages sélectionnés ? Cette action est irréversible.')">
            <i class="fas fa-trash"></i> Supprimer la sélection
        </button>
    </div>
    <div class="table-responsive">
        <table class="temoignage-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nom</th>
                    <th>Rang</th>
                    <th>Contenu</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($temoignages)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">
                        <div class="empty-state">
                            <i class="fas fa-quote-left"></i>
                            <h3>Aucun témoignage trouvé</h3>
                            <p><?php echo $search ? 'Aucun résultat pour votre recherche' : 'Commencez par créer votre premier témoignage'; ?></p>
                            <?php if (!$search): ?>
                                <a href="create.php" class="btn btn-primary" style="margin-top: 20px;">
                                    <i class="fas fa-plus"></i> Créer un témoignage
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($temoignages as $tem): ?>
                    <?php
                        $id_tem = isset($tem['id_tem']) ? (int)$tem['id_tem'] : 0;
                        $nom = isset($tem['Nom']) && $tem['Nom'] !== null ? $tem['Nom'] : '';
                        $rang = isset($tem['rang']) && $tem['rang'] !== null ? $tem['rang'] : '';
                        $text_tem = isset($tem['text_tem']) && $tem['text_tem'] !== null ? $tem['text_tem'] : '';
                        $image = isset($tem['image']) && $tem['image'] !== null ? $tem['image'] : '';
                        $imagePath = !empty($image) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $image)
                            ? '/Altiris/' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8')
                            : '/Altiris/Assets/Images/default_temoignage.jpg';
                    ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?php echo $id_tem; ?>" class="select-temoignage"></td>
                        <td><?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($rang, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo htmlspecialchars(mb_strimwidth($text_tem, 0, 80, '...'), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <img src="<?php echo $imagePath; ?>" alt="Image" style="width:40px;height:40px;border-radius:8px;object-fit:cover;border:1px solid #eee;">
                        </td>
                        <td>
                            <a href="/Altiris/temoignages/modifier/<?php echo $id_tem; ?>" class="btn btn-warning btn-sm" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $id_tem; ?>&confirm=yes&page=<?php echo $currentPage; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                               class="btn btn-danger btn-sm" title="Supprimer"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ? Cette action est irréversible.')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</form>

<?php if ($totalPages > 1): ?>
    <nav aria-label="Pagination" class="pagination-container">
        <ul class="pagination">
            <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?page=<?php echo max(1, $currentPage - 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" aria-label="Précédent">
                    Précédent
                </a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?page=<?php echo min($totalPages, $currentPage + 1); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" aria-label="Suivant">
                    Suivant
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélection/désélection tout
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.select-temoignage').forEach(cb => cb.checked = this.checked);
        });
    }
    // Activation du bouton suppression multiple
    const checkboxes = document.querySelectorAll('.select-temoignage');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
        });
    });
    // Auto-dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.querySelector('.btn-close')) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 300);
            }
        });
    }, 5000);
});
</script>