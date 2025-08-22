<?php
header('Content-Type: application/json');

try {
    error_log('new-messages.php : Début de l\'exécution');
    require_once(__DIR__ . '/../../config/db.php');
    require_once(__DIR__ . '/../models/Contacte.php');

    $contactModel = new Contacte();
    error_log('new-messages.php : Modèle Contacte initialisé');

    // Marquer un message spécifique comme lu
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_read' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        error_log("new-messages.php : Requête reçue : action=mark_read, id=$id");
        $contact = $contactModel->getById($id);
        if ($contact === false || !is_array($contact)) {
            error_log("new-messages.php : Contact introuvable pour ID $id");
            echo json_encode([
                'success' => false,
                'message' => 'Contact introuvable',
                'count' => $contactModel->countNewMessages()
            ]);
            exit;
        }
        if ($contact['status'] === 'lu') {
            error_log("new-messages.php : Message déjà lu pour ID $id");
            echo json_encode([
                'success' => true,
                'message' => 'Message déjà lu',
                'count' => $contactModel->countNewMessages()
            ]);
            exit;
        }
        $success = $contactModel->markAsRead($id);
        $count = $contactModel->countNewMessages();
        error_log("new-messages.php : Marquage ID $id : " . ($success ? "Succès" : "Échec"));
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Message marqué comme lu' : 'Erreur lors du marquage',
            'count' => $count
        ]);
        exit;
    }

    // Marquer tous les messages comme lus
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_all_read') {
        $success = $contactModel->markAllMessagesAsRead();
        $count = $contactModel->countNewMessages();
        error_log("new-messages.php : Marquage tous messages : " . ($success ? "Succès" : "Échec"));
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Tous les messages ont été marqués comme lus' : 'Erreur lors du marquage',
            'count' => $count
        ]);
        exit;
    }

    // Récupérer les notifications
    $since = isset($_GET['since']) ? (int)$_GET['since'] : 0;
    $notifications = $contactModel->getNotifications($since);
    $count = $contactModel->countNewMessages();
    error_log("new-messages.php : Notifications récupérées, count=$count");
    echo json_encode([
        'success' => true,
        'count' => $count,
        'notifications' => $notifications
    ]);
} catch (Exception $e) {
    error_log("new-messages.php : Erreur fatale : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur : ' . $e->getMessage(),
        'count' => 0
    ]);
}
?>