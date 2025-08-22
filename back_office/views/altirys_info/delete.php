<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/connexion");
    exit;
}
require_once '../../controllers/AltirysInfoController.php';
$controller = new AltirysInfoController();
$controller->delete($_GET['id']);
ob_end_flush();
?>