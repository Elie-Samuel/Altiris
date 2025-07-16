<?php
// Démarrer la session en tout premier
session_start();

// Chemin de base pour les redirections
define('BASE_URL', '/Altiris/');

// Vérification de la connexion
if (!isset($_SESSION['logged_in'])) {
    header('Location: ' . BASE_URL . 'back_office/login.php');
    exit;
}

// Inclure la configuration DB
require_once __DIR__ . '/config/db.php';

// Nettoyer et valider les entrées
$action = isset($_GET['action']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['action']) : 'index';
$controller = isset($_GET['controller']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['controller']) : 'annonce';

// Construire le chemin du contrôleur
$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/controllers/' . $controllerClass . '.php';

// Vérifier l'existence du fichier
if (!file_exists($controllerFile)) {
    header("HTTP/1.0 404 Not Found");
    die('Contrôleur non trouvé');
}

// Inclure et instancier le contrôleur
require_once $controllerFile;

if (!class_exists($controllerClass)) {
    header("HTTP/1.0 500 Internal Server Error");
    die('Classe contrôleur non trouvée');
}

$controllerInstance = new $controllerClass();

// Vérifier que la méthode existe
if (!method_exists($controllerInstance, $action)) {
    header("HTTP/1.0 404 Not Found");
    die('Action non trouvée');
}

// Appeler la méthode avec sécurité
try {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id) {
        $controllerInstance->$action($id);
    } else {
        $controllerInstance->$action();
    }
} catch (Exception $e) {
    header("HTTP/1.0 500 Internal Server Error");
    die('Erreur serveur: ' . $e->getMessage());
}
?>
