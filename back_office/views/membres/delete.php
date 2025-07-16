<?php
require_once '../../../components/header.php';
require_once dirname(__DIR__, 3) . '/controllers/MembreController.php';
$controller = new MembreController();
if (isset($_GET['id'])) {
    $controller->delete($_GET['id']);
}
header("Location: index.php");
exit;
?>