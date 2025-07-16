<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ContactEntController.php';

$controller = new ContactEntController();
$error = null;
$success = false;

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    $error = "ID de contact invalide.";
} else {
    $result = $controller->delete($id);
    if ($result === true) {
        $_SESSION['success'] = "Contact supprimé avec succès";
        header("Location: index.php");
        exit;
    } else {
        $error = "Erreur lors de la suppression du contact.";
    }
}
?>

<h2>Supprimer un contact</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">Contact supprimé avec succès</div>
<?php endif; ?>

<?php if ($error): ?>
    <a href="index.php" class="btn btn-secondary">Retour à la liste</a>
<?php endif; ?>

<?php require_once '../../components/footer.php'; ?>