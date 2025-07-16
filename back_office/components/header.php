<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: /Altiris/login.php");
    exit;
}
$adminName = $_SESSION['username'] ?? 'Elie samuel'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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