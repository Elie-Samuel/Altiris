<?php
// back_office/components/header.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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