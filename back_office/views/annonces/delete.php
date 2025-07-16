<?php
require_once '../../controllers/AnnonceController.php';
$controller = new AnnonceController();
$controller->delete($_GET['id']);
?>