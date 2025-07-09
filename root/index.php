<?php
require_once __DIR__.'/bootstrap.php';

// 2. Initialisation de la base de données (avec vérification)
$dbPath = realpath(__DIR__ . '/../config/db.php');
if (!$dbPath || !file_exists($dbPath)) {
    die("Erreur critique : Fichier de configuration DB introuvable (" . __DIR__ . '/../../config/db.php' . ")");
}
require_once $dbPath;

// 3. Sécurisation du paramètre page
$page = strtolower($_GET['page'] ?? 'home');
if (!preg_match('/^[a-z]+$/', $page)) {
    $page = 'home';
}

// 4. Construction du chemin du contrôleur
$controllerFile = __DIR__.'/frontoffice/controllers/'.ucfirst($page).'Controller.php';

// 5. Vérification et exécution
try {
    if (!file_exists($controllerFile)) {
        throw new Exception("Contrôleur non trouvé");
    }

    require_once $controllerFile;

    $controllerClass = 'Altiris\\FrontOffice\\Controllers\\'.ucfirst($page).'Controller';
    
    if (!class_exists($controllerClass)) {
        throw new Exception("Classe $controllerClass introuvable");
    }

    // Vérification de la connexion DB avant instanciation
    if (!isset($db) || !($db instanceof PDO)) {
        throw new Exception("Erreur de connexion à la base de données");
    }

    $controller = new $controllerClass($db);
    
    if (!method_exists($controller, 'index')) {
        throw new Exception("Méthode index() manquante");
    }

    $controller->index();

} catch (Exception $e) {
    // Journalisation de l'erreur
    error_log('Erreur routage: ' . $e->getMessage());
    
    // Affichage selon l'environnement
    if (ini_get('display_errors')) {
        die("Erreur : " . $e->getMessage());
    } else {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__.'/frontoffice/views/404.php';
    }
    exit;
}