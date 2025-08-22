<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/controllers/AnnonceController.php';
$controller = new AnnonceController();
$id = isset($_GET['id']) ? $_GET['id'] : null;
require_once '../../components/header.php';
$controller->edit($id);
global $annonce;
if (!$annonce) {
    header("Location: /Altiris/annonces");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Annonce</title>
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
        .current-image {
            border: 2px solid orange;
            padding: 5px;
            background-color: #222;
            border-radius: 5px;
        }
        .preview {
            display: none;
            margin-top: 10px;
            border: 2px solid #0d6efd;
            padding: 5px;
            background-color: #222;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Bouton retour -->
    <div class="mb-3">
        <a href="/Altiris/annonces" class="btn btn-secondary">&larr; Retour</a>
    </div>

    <h2>Modifier une Annonce</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" id="editAnnonceForm">
        <div class="mb-3">
            <label for="titre">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" value="<?php echo isset($annonce['titre']) ? htmlspecialchars($annonce['titre']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="titre1">Titre supplémentaire</label>
            <input type="text" class="form-control" id="titre1" name="titre1" value="<?php echo isset($annonce['titre1']) ? htmlspecialchars($annonce['titre1']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="text">Texte</label>
            <textarea class="form-control" id="text" name="text" required><?php echo isset($annonce['text']) ? htmlspecialchars($annonce['text']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image">Image (optionnel, JPG/PNG, max 5 Mo)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/jpg,image/png">
            <?php if (isset($annonce['image']) && $annonce['image'] && file_exists(dirname(__DIR__, 2) . '/' . $annonce['image'])): ?>
                <div class="mt-3">
                    <p>Image actuelle :</p>
                    <img src="<?php echo htmlspecialchars($annonce['image']); ?>" alt="Image actuelle" class="current-image" style="max-width: 150px;">
                </div>
            <?php endif; ?>
            <div class="preview mt-3" id="previewContainer">
                <p>Nouvelle image :</p>
                <img id="previewImage" src="" alt="Aperçu" style="max-width: 150px;">
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Modifier</button>
    </form>
</div>

<script>
document.getElementById("image").addEventListener("change", function(e) {
    let file = e.target.files[0];
    let maxSize = 5 * 1024 * 1024; // 5 Mo
    if (file) {
        if (file.size > maxSize) {
            alert("L'image dépasse la taille maximale de 5 Mo.");
            e.target.value = "";
            return;
        }
        let reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById("previewImage").src = ev.target.result;
            document.getElementById("previewContainer").style.display = "block";
        }
        reader.readAsDataURL(file);
    }
});
</script>
</body>
</html>
