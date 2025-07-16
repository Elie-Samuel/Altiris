<?php
<<<<<<< HEAD
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: /Altiris/login.php");
    exit;
}
$adminName = $_SESSION['username'] ?? 'Elie samuel'; 
=======
// back_office/components/header.php
>>>>>>> e094eeb8efa2873c0981c539bb3c64d9e64feb63
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Altiris Back Office</title>
    <link rel="stylesheet" href="/Altiris/Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Altiris/Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/Altiris/back_office/css/style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top" style="width: 100%; max-width: calc(100% - 200px); margin-left: 200px;">
        <div class="d-flex align-items-center">
            <div class="search-bar">
                <input type="text" class="form-control text-white" placeholder="Rechercher..." aria-label="Search">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <a href="#" class="nav-link text-white me-3"><i class="fas fa-bell"></i></a>
            <a href="#" class="nav-link text-white me-3"><i class="fas fa-cog"></i></a>
            <div class="admin-info d-flex align-items-center">
                <i class="fas fa-user me-2"></i>
                <span class="text-white"><?php echo htmlspecialchars($adminName); ?></span>
            </div>
        </div>
    </nav>
    <div class="sidebar d-flex flex-column">
        <a class="navbar-brand" href="#">
            <img src="/Altiris/Assets/Images/logo_Altirys.png" alt="Logo Altiris" class="logo">
            Altiris
        </a>
        <nav class="nav flex-column mt-5">
            <a class="nav-link" href="/Altiris/back_office/views/annonces/index.php"><i class="fas fa-bullhorn"></i> Annonces</a>
            <a class="nav-link" href="/Altiris/back_office/views/services/index.php"><i class="fas fa-cogs"></i> Services</a>
            <a class="nav-link" href="/Altiris/back_office/views/actualiters/index.php"><i class="fas fa-newspaper"></i> Actualités</a>
            <a class="nav-link" href="/Altiris/back_office/views/membres/index.php"><i class="fas fa-users"></i> Membres</a>
            <a class="nav-link" href="/Altiris/back_office/views/contact_ent/index.php"><i class="fas fa-users"></i> Contact entreprise</a>
        </nav>
        <div class="mt-auto">
            <a class="nav-link" href="/Altiris/back_office/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnecter</a>
        </div>
    </div>
    <div class="container mt-5 pt-4">
=======
    <title>Back Office Altirys</title>
    <link href="/Altiris/Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Altiris/Assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="/Altiris/back_office/css/custom.css" rel="stylesheet">
</head>
<body>
    <header class="header-fixed">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <img src="/Altiris/Assets/Images/logo_Altirys.png" alt="Logo Altiris" style="height: 40px;">
                    <h1 class="h4 mb-0 ms-3">ALTIRIS</h1>
                </div>
                <div class="d-flex align-items-center">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Rechercher une annonce...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="dropdown ms-4">
                        <button class="btn btn-link dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> Admin
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small">Administrateur</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item btn-logout" 
                                   href="/Altiris/back_office/controllers/LoginController.php?action=logout"
                                   onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                                   <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <!-- Le contenu de index.php sera injecté ici -->
>>>>>>> e094eeb8efa2873c0981c539bb3c64d9e64feb63
