<?php
require_once '../../controllers/ActualiterController.php';
$controller = new ActualiterController();
$controller->delete($_GET['id']);
?>