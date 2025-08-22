<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/CompetenceController.php';

$controller = new CompetenceController();
$controller->delete($_GET['id'] ?? 0);
?>