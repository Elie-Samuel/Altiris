<?php
require_once '../../controllers/TemoignageController.php';
$controller = new TemoignageController();
$controller->delete($_GET['id']);
?>