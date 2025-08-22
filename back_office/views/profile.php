<?php
session_start();
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}

// Inclure le modèle
require_once dirname(__DIR__) . '/models/Utilisateur.php';

$userModel = new Utilisateur();
$user = $userModel->read($_SESSION['user_id']);

if (!$user) {
    die("Utilisateur non trouvé.");
}

$username = htmlspecialchars($user['nom_utilisateur'] ?? 'Inconnu');
$email = htmlspecialchars($user['email'] ?? '');
$profile = htmlspecialchars($user['profil'] ?? 'Non défini');
$dateCreation = htmlspecialchars($user['date_creation'] ?? 'Non défini');

// Définir le chemin de l'image
$imagePath = !empty($user['profil']) ? '/Altiris/' . htmlspecialchars($user['profil']) : '/Altiris/Assets/Images/default_profile.jpg';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Altiris Back Office</title>
    <link rel="stylesheet" href="/Altiris/Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Altiris/Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/Altiris/back_office/css/style.css">
    <style>
        .profile-img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Profil de <?php echo $username; ?></h1>
        <div class="card">
            <div class="card-body">
                <?php if (!empty($user['profil'])): ?>
                    <img src="<?php echo $imagePath; ?>" alt="Photo de profil de <?php echo $username; ?>" class="profile-img mb-3">
                <?php else: ?>
                    <img src="/Altiris/Assets/Images/default_profile.jpg" alt="Photo de profil par défaut" class="profile-img mb-3">
                <?php endif; ?>
                <p><strong>Nom d'utilisateur :</strong> <?php echo $username; ?></p>
                <p><strong>Email :</strong> <?php echo $email; ?></p>
                <p><strong>Profil :</strong> <?php echo $profile; ?></p>
                <p><strong>Date de création :</strong> <?php echo $dateCreation; ?></p>
                <a href="/Altiris/back_office/views/annonces/index.php" class="btn btn-primary">Retour</a>
            </div>
        </div>
    </div>
</body>
</html>