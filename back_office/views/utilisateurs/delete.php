<?php
session_start();
require_once '../../controllers/UtilisateurController.php';

if (!isset($_SESSION['types']) || $_SESSION['types'] !== 'Super admin') {
    $_SESSION['error'] = "Accès non autorisé.";
    header("Location: /Altiris/back_office/views/annonces/index.php");
    exit;
}

$controller = new UtilisateurController();
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "ID utilisateur manquant.";
    header("Location: index.php");
    exit;
}

$controller->delete($id);
?>