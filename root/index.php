<?php
require_once __DIR__.'/bootstrap.php';

require_once __DIR__.'/../config/db.php';

$page = $_GET['page'] ?? 'home';

// Dossier des contrôleurs
$controllerPath = __DIR__.'/frontoffice/controllers/'.ucfirst($page).'Controller.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controllerClass = ucfirst($page).'Controller';
    $controller = new $controllerClass($db);
    $controller->index();
} else {
    // Page non trouvée
    header("HTTP/1.0 404 Not Found");
    require_once __DIR__.'/frontoffice/views/404.php';
}