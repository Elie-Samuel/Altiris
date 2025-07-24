<?php
// altiris/root/index.php

require_once __DIR__.'/bootstrap.php';

// Initialisation de la base de données
try {
    // Path to db.php relative to index.php
    $dbPath = realpath(__DIR__ . '/../config/db.php');
    if (!$dbPath || !file_exists($dbPath)) {
        throw new Exception("Fichier de configuration DB introuvable à " . $dbPath);
    }
    require_once $dbPath;
    
    // Vérification que $db est bien initialisé
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
$specialRoutes = ['actualite', 'contact', 'home', 'team']; // Add other special routes as needed

if (in_array($page, $specialRoutes)) {
    try {
        $controllerClass = $controllerNamespace . ucfirst($page) . 'Controller';
        
        // Check if the controller class exists before instantiating
        if (!class_exists($controllerClass)) {
            throw new Exception("Contrôleur '$controllerClass' non trouvé pour la page '$page'");
        }

        $controller = new $controllerClass($db);

        // Handle specific actions for contact page
        if ($page === 'contact' && isset($_GET['action']) && $_GET['action'] === 'submit') {
            $controller->submit();
        } else {
            // Default action for other special routes
            if (method_exists($controller, 'index')) {
                $controller->index();
            } else {
                throw new Exception("Méthode 'index' non disponible pour le contrôleur '$controllerClass'");
            }
        }
        exit;
    } catch (Exception $e) {
        error_log("Erreur dans le contrôleur $page: " . $e->getMessage());
        // Redirect to home or show a generic error
        header("Location: /Altiris/root/?page=home"); // Adjust /Altiris/root/ if your base URL is different
        exit;
    }
}

// Standard route (for other pages not in specialRoutes)
$controllerClass = $controllerNamespace . ucfirst($page) . 'Controller';

try {
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
        require_once __DIR__.'/frontoffice/views/404.php'; // Assuming you have a 404 view
    }
    exit;
}
