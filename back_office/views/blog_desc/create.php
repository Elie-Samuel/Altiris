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
require_once dirname(__DIR__, 2) . '/controllers/BlogDescController.php';
$controller = new BlogDescController();
$existingDesc = $controller->index(); // Check for existing description
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
    $sous_titre = !empty($_POST['sous_titre']) ? trim($_POST['sous_titre']) : null;
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;

    if ($titre && $sous_titre && $description) {
        if ($existingDesc) {
            $_SESSION['error'] = "Une description de blog existe déjà. Veuillez la modifier.";
        } elseif ($controller->model->create($titre, $sous_titre, $description)) {
            $_SESSION['success'] = "Description créée avec succès.";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de la création.";
        }
    } else {
        $_SESSION['error'] = "Le titre, le sous-titre et la description sont obligatoires.";
    }
}
?>
<h2>Ajouter une Description de Blog</h2>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="sous_titre">Sous-titre</label>
        <input type="text" class="form-control" id="sous_titre" name="sous_titre" required>
    </div>
    <div class="mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
</form>