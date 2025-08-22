<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/connexion");
    exit;
}
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/MembreController.php';

$controller = new MembreController();

// Gestion des paramètres
$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$roleFilter = isset($_GET['role']) ? trim($_GET['role']) : null;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 6;

// Récupération des membres avec filtres
$allMembres = $controller->getMembres($search, $roleFilter);

// Pagination
$totalMembres = count($allMembres);
$totalPages = max(1, ceil($totalMembres / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$membresPaginated = array_slice($allMembres, $offset, $perPage);

// Suppression d'un membre
if (isset($_GET['delete'])) {
    if ($controller->delete($_GET['delete'])) {
        $_SESSION['success'] = "Membre supprimé avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
    }
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des membres - Altiris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        h2 {
            color: #f9f9f9;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .alert-success {
            background-color: #004aad;
            color: #ffffff;
        }

        .alert-error {
            background-color: #dc3545;
            color: #ffffff;
        }

        .alert-info {
            background-color: #333333;
            color: #ffffff;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 5px;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #004aad;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #ff6200;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #bd2130;
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: #ff6200;
            color: #ffffff;
        }

        .btn-warning:hover {
            background-color: #e55a00;
            transform: translateY(-2px);
        }

        .search-filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .search-box, .role-filter {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-box input, .role-filter input {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #004aad;
            background-color: #333;
            color: white;
            min-width: 250px;
            font-size: 0.9rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .member-card {
            background-color: #0f172a;
            border: 1px solid #004aad;
            border-radius: 8px;
            max-width: 550px;
            width: 100%;
            padding: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: flex;
            gap: 1rem;
        }

        .member-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 74, 173, 0.4);
        }

        .image-container {
            flex: 0 0 150px;
            display: flex;
            align-items: center;
        }

        .member-card img {
            width: 150px;
            height: 300px;
            object-fit: cover;
            border: 2px solid #ff6200;
            border-radius: 5px;
        }

        .no-photo {
            width: 150px;
            height: 150px;
            background-color: #333333;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ff6200;
            border-radius: 5px;
            font-size: 0.8rem;
        }

        .info-container {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .member-info {
            margin-bottom: 0.5rem;
            color: #ffffff;
            word-break: break-word;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .member-info strong {
            color: #ff6200;
            font-weight: 600;
            display: inline-block;
            min-width: 80px;
        }

        .member-info a {
            color: #4da6ff;
            text-decoration: none;
        }

        .member-info a:hover {
            text-decoration: underline;
        }

        .button-container {
            margin-top: auto;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border: 1px solid #004aad;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .pagination a:hover {
            background-color: #004aad;
            transform: translateY(-2px);
        }

        .pagination .active {
            background-color: #ff6200;
            border-color: #ff6200;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .member-card {
                flex-direction: column;
                max-width: 350px;
            }
            
            .image-container {
                flex: 0 0 auto;
                justify-content: center;
            }
            
            .member-card img,
            .no-photo {
                width: 100%;
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            .button-container {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
            
            .search-box input, .role-filter input {
                min-width: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <h2>Gestion des membres</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="search-filter-container">
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Rechercher un membre..." 
                   value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
            <?php if ($search): ?>
                <a href="index.php" class="btn btn-danger">
                    <i class="fas fa-times"></i> Effacer
                </a>
            <?php endif; ?>
        </form>

        <form method="GET" class="role-filter">
            <input type="text" name="role" placeholder="Filtrer par rôle (texte libre)..." 
                   value="<?= htmlspecialchars($roleFilter ?? '') ?>">
            <button type="submit" class="btn btn-primary" style="padding: 10px 15px;">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            <?php if ($search): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
            <?php endif; ?>
        </form>
    </div>

    <a href="/Altiris/membres/ajouter" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un membre
    </a>

    <div class="row">
        <?php if (empty($membresPaginated)): ?>
            <div class="col">
                <div class="alert alert-info">Aucun membre trouvé</div>
            </div>
        <?php else: ?>
            <?php foreach ($membresPaginated as $membre): ?>
                <div class="member-card">
                    <div class="image-container">
                        <?php if (!empty($membre['photo'])): ?>
                            <img src="/Altiris/<?= htmlspecialchars($membre['photo']) ?>" 
                                 alt="Photo de <?= htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']) ?>">
                        <?php else: ?>
                            <div class="no-photo">Aucune photo</div>
                        <?php endif; ?>
                    </div>
                    <div class="info-container">
                        <div class="member-info">
                            <strong>Nom:</strong> <?= htmlspecialchars($membre['nom']) ?>
                        </div>
                        <div class="member-info">
                            <strong>Prénom:</strong> <?= htmlspecialchars($membre['prenom']) ?>
                        </div>
                        <div class="member-info">
                            <strong>Email:</strong> <?= htmlspecialchars($membre['email']) ?>
                        </div>
                        <div class="member-info">
                            <strong>Rôle:</strong> <?= htmlspecialchars($membre['role']) ?>
                        </div>
                        <div class="member-info">
                            <strong>Téléphone:</strong> <?= htmlspecialchars($membre['Tel'] ?? '-') ?>
                        </div>
                        <div class="button-container">
                            <a href="/Altiris/membres/modifier/<?= $membre['id_membre'] ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="?delete=<?= $membre['id_membre'] ?>" class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $roleFilter ? '&role='.urlencode($roleFilter) : '' ?>">
                &laquo; Précédent
            </a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $roleFilter ? '&role='.urlencode($roleFilter) : '' ?>">
                    <?= $i ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $roleFilter ? '&role='.urlencode($roleFilter) : '' ?>">
                Suivant &raquo;
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des boutons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Confirmation avant suppression
            const deleteButtons = document.querySelectorAll('.btn-danger');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>