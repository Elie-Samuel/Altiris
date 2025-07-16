<?php
require_once '../../controllers/ServiceController.php';
$controller = new ServiceController();
$controller->delete($_GET['id']);
?>