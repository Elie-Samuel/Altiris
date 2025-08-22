<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__) . '/controllers/ParametreController.php';

// Vérifier l'authentification
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

// Vérifier les permissions Super Admin
if (!isset($_SESSION['types']) || $_SESSION['types'] !== 'Super admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Permissions insuffisantes']);
    exit;
}

header('Content-Type: application/json');

try {
    $controller = new ParametreController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
        
        switch ($action) {
            case 'test_email':
                $settings = $input['settings'] ?? [];
                $result = $controller->testEmailConnection($settings);
                echo json_encode($result);
                break;
                
            case 'create_backup':
                $result = $controller->createBackup();
                echo json_encode($result);
                break;
                
            case 'optimize_database':
                $result = $controller->optimizeDatabase();
                echo json_encode($result);
                break;
                
            case 'clear_cache':
                $result = $controller->clearCache();
                echo json_encode($result);
                break;
                
            case 'send_test_notification':
                require_once dirname(__DIR__) . '/controllers/NotificationController.php';
                $notificationController = new NotificationController();
                $result = $notificationController->createSystemNotification(
                    "Test de notification",
                    "Ceci est une notification de test envoyée depuis les paramètres",
                    "info"
                );
                echo json_encode(['success' => $result]);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
        }
    } else {
        // GET - Récupérer les paramètres
        $data = $controller->index();
        echo json_encode(['success' => true, 'data' => $data]);
    }
    
} catch (Exception $e) {
    error_log("Erreur API paramètres: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
?>