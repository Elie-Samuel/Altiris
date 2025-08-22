<?php
// altiris/root/index.php

require_once __DIR__ . '/bootstrap.php';

// Initialisation de la base de données
try {
    $dbPath = realpath(__DIR__ . '/../config/db.php');
    if (!$dbPath || !file_exists($dbPath)) {
        throw new Exception("Fichier de configuration DB introuvable à " . $dbPath);
    }
    require_once $dbPath;
    
    if (!isset($db) || !($db instanceof PDO)) {
        throw new Exception("La connexion à la base de données n'a pas pu être établie");
    }
} catch (Exception $e) {
    die("Erreur critique : " . $e->getMessage());
}

// Sécurisation du paramètre page
$page = strtolower($_GET['page'] ?? 'home');
if (!preg_match('/^[a-z]+$/', $page)) {
    $page = 'home';
}

// Define the base namespace for controllers
$controllerNamespace = 'Altiris\\FrontOffice\\Controllers\\';

// Handle special routes
$specialRoutes = ['actualite', 'contact', 'home', 'team', 'apropos', 'blog'];

if (in_array($page, $specialRoutes)) {
    try {
        $controllerClass = $controllerNamespace . ucfirst($page) . 'Controller';
        
        if (!class_exists($controllerClass)) {
            throw new Exception("Contrôleur '$controllerClass' non trouvé pour la page '$page'");
        }

        error_log("Chargement du contrôleur: $controllerClass pour la page: $page");
        $controller = new $controllerClass($db);

        switch ($page) {
            case 'contact':
                if (isset($_GET['action']) && $_GET['action'] === 'submit') {
                    $controller->submit();
                } else {
                    $controller->index();
                }
                break;

            case 'actualite':
            case 'blog':
                if (isset($_GET['id'])) {
                    $controller->show($_GET['id']);
                } else {
                    $controller->index();
                }
                break;

            case 'home':
            case 'team':
            case 'apropos':
                $controller->index();
                break;

            default:
                $controller->index();
                break;
        }
        exit;
    } catch (Exception $e) {
        error_log("Erreur dans le contrôleur $page: " . $e->getMessage());
        header("Location: /Altiris/root/?page=home");
        exit;
    }
}

try {
    $controllerClass = $controllerNamespace . ucfirst($page) . 'Controller';
    
    if (!class_exists($controllerClass)) {
        throw new Exception("Contrôleur '$controllerClass' non trouvé");
    }

    $controller = new $controllerClass($db);
    
    if (!method_exists($controller, 'index')) {
        throw new Exception("Méthode 'index' non disponible pour le contrôleur '$controllerClass'");
    }
    
    $controller->index();

} catch (Exception $e) {
    error_log('Erreur routage: ' . $e->getMessage());
    
    if (ini_get('display_errors')) {
        die("ERREUR: " . $e->getMessage());
    } else {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/frontoffice/views/404.php';
    }
    exit;
}