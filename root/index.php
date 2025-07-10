<?php
// D:\wamp64\www\Altiris\root\index.php

require_once __DIR__.'/bootstrap.php';

// Initialisation de la base de données
$dbPath = realpath(__DIR__ . '/../config/db.php');
if (!$dbPath || !file_exists($dbPath)) {
    die("Erreur critique : Fichier de configuration DB introuvable (" . __DIR__ . '/../config/db.php' . ")");
}
require_once $dbPath;

// Sécurisation du paramètre page
$page = strtolower($_GET['page'] ?? 'home');
if (!preg_match('/^[a-z]+$/', $page)) {
    $page = 'home';
}

// Gestion des routes spéciales
if ($page === 'actualite') {
    $controller = new \Altiris\FrontOffice\Controllers\ActualiteController($db);
    $controller->show();
    exit;
}

// Route standard
$controllerClass = 'Altiris\\FrontOffice\\Controllers\\'.ucfirst($page).'Controller';
$controllerFile = __DIR__.'/frontoffice/controllers/'.ucfirst($page).'Controller.php';

try {
    // Vérification du contrôleur
    if (!file_exists($controllerFile)) {
        throw new Exception("Contrôleur $page non trouvé");
    }

    require_once $controllerFile;

    // Vérification de la classe
    if (!class_exists($controllerClass)) {
        throw new Exception("Classe $controllerClass introuvable");
    }

    // Vérification connexion DB
    if (!isset($db) || !($db instanceof PDO)) {
        throw new Exception("Erreur de connexion à la base de données");
    }

    // Instanciation et exécution
    $controller = new $controllerClass($db);
    
    if ($page === 'actualite') {
        if (!method_exists($controller, 'show')) {
            throw new Exception("Méthode show() manquante");
        }
        $controller->show();
    } else {
        if (!method_exists($controller, 'index')) {
            throw new Exception("Méthode index() manquante");
        }
        $controller->index();
    }

} catch (Exception $e) {
    // Gestion des erreurs
    error_log('Erreur routage: ' . $e->getMessage());
    
    if (ini_get('display_errors')) {
        die("ERREUR: " . $e->getMessage());
    } else {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__.'/frontoffice/views/404.php';
    }
    exit;
}