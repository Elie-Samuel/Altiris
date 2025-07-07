<?php
// index.php
require_once __DIR__ . '/back_office/config/config.php';

// Routeur principal
$controllerName = $_GET['controller'] ?? 'Annonces';
$action = $_GET['action'] ?? 'index';

// Construction du nom de la classe
$controllerClass = $controllerName . 'Controller';
$controllerFile = __DIR__ . '/back_office/controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Vérification que la classe existe
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        
        // Vérification que la méthode existe
        if (method_exists($controller, $action)) {
            // Appel de la méthode avec les paramètres éventuels
            $id = $_GET['id'] ?? null;
            $controller->$action($id);
        } else {
            die("Action '$action' non trouvée dans le contrôleur $controllerClass");
        }
    } else {
        die("Classe '$controllerClass' non trouvée");
    }
} else {
    die("Contrôleur '$controllerName' non trouvé");
}