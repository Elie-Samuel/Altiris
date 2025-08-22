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
    error_log("Erreur critique : " . $e->getMessage());
    http_response_code(500);
    require_once __DIR__ . '/frontoffice/views/errors/500.php';
    exit;
}

// Fonction pour obtenir le titre dynamique
function getDynamicTitle($page, $db) {
    $titles = [
        'home' => 'ALTIRYS - Votre partenaire digital de confiance',
        'apropos' => 'À propos - ALTIRYS',
        'team' => 'Notre Équipe - ALTIRYS',
        'contact' => 'Contact - ALTIRYS',
        'blog' => 'Blog - ALTIRYS',
        'actualite' => 'Actualités - ALTIRYS'
    ];
    
    // Titre spécifique pour un article de blog
    if ($page === 'blog' && isset($_GET['id'])) {
        try {
            $stmt = $db->prepare("SELECT title FROM blog_posts WHERE id = ? AND published = 1");
            $stmt->execute([$_GET['id']]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($post) {
                return htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') . ' - ALTIRYS Blog';
            }
        } catch (Exception $e) {
            error_log("Erreur titre dynamique: " . $e->getMessage());
        }
    }
    
    return isset($titles[$page]) ? htmlspecialchars($titles[$page], ENT_QUOTES, 'UTF-8') : 'ALTIRYS - Agence Digitale';
}

// Sécurisation du paramètre page
$page = strtolower($_GET['page'] ?? 'home');
if (!preg_match('/^[a-z]+$/', $page)) {
    $page = 'home';
}

// Obtenir le titre dynamique
$pageTitle = getDynamicTitle($page, $db);

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

        // Passer le titre à tous les contrôleurs
        if (method_exists($controller, 'setPageTitle')) {
            $controller->setPageTitle($pageTitle);
        }

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
        http_response_code(404);
        require_once __DIR__ . '/frontoffice/views/errors/404.php';
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
    
    // Passer le titre
    if (method_exists($controller, 'setPageTitle')) {
        $controller->setPageTitle($pageTitle);
    }
    
    $controller->index();

} catch (Exception $e) {
    error_log('Erreur routage: ' . $e->getMessage());
    
    if (ini_get('display_errors')) {
        die("ERREUR: " . $e->getMessage());
    } else {
        http_response_code(404);
        require_once __DIR__ . '/frontoffice/views/errors/404.php';
    }
    exit;
}