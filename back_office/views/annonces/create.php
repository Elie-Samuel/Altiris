<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/AnnonceController.php';
$controller = new AnnonceController();
$controller->create();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Annonce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: white;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            color: white;
            font-weight: bold;
        }
        .form-control {
            background-color: #1a1a1a;
            color: white;
            border: 1px solid #0d6efd;
        }
        .form-control:focus {
            border-color: orange;
            box-shadow: 0 0 5px orange;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: orange;
            border-color: orange;
        }
        .btn-secondary {
            background-color: orange;
            border-color: orange;
            font-weight: bold;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .alert-danger {
            background-color: #ff4d4d;
            color: white;
            border: none;
        }
        .container {
            max-width: 600px;
            background-color: #111;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(255, 165, 0, 0.5);
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Bouton retour -->
    <div class="mb-3">
        <a href="/Altiris/annonces" class="btn btn-secondary">&larr; Retour</a>
    </div>

    <h2>Ajouter une Annonce</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" id="annonceForm">
        <div class="mb-3">
            <label for="titre">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" required>
        </div>
        <div class="mb-3">
            <label for="titre1">Titre supplémentaire</label>
            <input type="text" class="form-control" id="titre1" name="titre1" required>
        </div>
        <div class="mb-3">
            <label for="text">Texte</label>
            <textarea class="form-control" id="text" name="text" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image">Image (optionnel, JPG/PNG, max 5 Mo)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/jpg,image/png">
        </div>
        <button type="submit" class="btn btn-primary w-100">Créer</button>
    </form>
</div>

<script>
document.getElementById("annonceForm").addEventListener("submit", function(e) {
    let imageInput = document.getElementById("image");
    if (imageInput.files.length > 0) {
        let file = imageInput.files[0];
        let maxSize = 5 * 1024 * 1024; // 5 Mo
        if (file.size > maxSize) {
            alert("L'image dépasse la taille maximale de 5 Mo.");
            e.preventDefault();
        }
    }
});
</script>
</body>
</html>
