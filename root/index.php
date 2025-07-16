<?php
// D:\wamp64\www\Altiris\root/index.php

require_once __DIR__.'/bootstrap.php';

// Initialisation de la base de données
$dbPath = realpath(__DIR__ . '/../config/db.php');
if (!$dbPath || !file_exists($dbPath)) {
    die("Erreur critique : Fichier de configuration DB introuvable (" . __DIR__ . '/../config/db.php' . ")");
}
require_once $dbPath;

// Instantiate Database class and get the connection
$database = new Database();
$db = $database->getConnection();

if ($db === null) {
    die("Erreur : Connexion à la base de données échouée. Vérifiez les logs pour plus de détails.");
}

// Sécurisation du paramètre page
$page = strtolower($_GET['page'] ?? 'home');
if (!preg_match('/^[a-z]+$/', $page)) {
    $page = 'home';
}

// Gestion des routes spéciales
$specialRoutes = ['actualite', 'contact', 'home'];
if (in_array($page, $specialRoutes)) {
    switch ($page) {
        case 'actualite':
            $controller = new \Altiris\FrontOffice\Controllers\ActualiteController($db);
            $controller->show();
            break;
            
        case 'contact':
            $controller = new \Altiris\FrontOffice\Controllers\ContactController($db);
            if (isset($_GET['action']) && $_GET['action'] === 'submit') {
                $controller->submit();
            } else {
                $controller->index();
            }
            break;
            
        case 'home':
            $controller = new \Altiris\FrontOffice\Controllers\HomeController($db);
            $controller->index();
            break;
    }
    exit;
}

// Route standard (pour les autres pages)
$controllerClass = 'Altiris\\FrontOffice\\Controllers\\'.ucfirst($page).'Controller';
$controllerFile = __DIR__.'/frontoffice/controllers/'.ucfirst($page).'Controller.php';

try {
    if (!file_exists($controllerFile)) {
        throw new Exception("Contrôleur $page non trouvé");
    }

    require_once $controllerFile;

    if (!class_exists($controllerClass)) {
        throw new Exception("Classe $controllerClass introuvable");
    }

    $controller = new $controllerClass($db);
    
    if (!method_exists($controller, 'index')) {
        throw new Exception("Méthode index() manquante");
    }
    
    $controller->index();

} catch (Exception $e) {
    error_log('Erreur routage: ' . $e->getMessage());
    
    if (ini_get('display_errors')) {
        die("ERREUR: " . $e->getMessage());
    } else {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__.'/frontoffice/views/404.php';
    }
    exit;
}