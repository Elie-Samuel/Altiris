<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}
require_once '../../controllers/SocialLinksController.php';
$controller = new SocialLinksController();
$controller->delete($_GET['id']);
ob_end_flush();
?>