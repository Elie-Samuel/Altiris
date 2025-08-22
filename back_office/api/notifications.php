<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/db.php';
header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();
    $userId = $_SESSION['user_id'];
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Récupérer les notifications
        $since = isset($_GET['since']) ? (int)$_GET['since'] : 0;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        
        $query = "SELECT n.*, u.nom_utilisateur as sender_name 
                  FROM notifications n 
                  LEFT JOIN utilisateurs u ON n.sender_id = u.id 
                  WHERE n.user_id = :user_id";
        
        if ($since > 0) {
            $query .= " AND n.created_at > FROM_UNIXTIME(:since)";
        }
        
        $query .= " ORDER BY n.created_at DESC LIMIT :limit";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        if ($since > 0) {
            $stmt->bindParam(':since', $since, PDO::PARAM_INT);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formater les notifications
        $formattedNotifications = array_map(function($notification) {
            return [
                'id' => $notification['id'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'type' => $notification['type'],
                'read' => (bool)$notification['is_read'],
                'created_at' => $notification['created_at'],
                'sender_name' => $notification['sender_name'],
                'data' => json_decode($notification['data'], true)
            ];
        }, $notifications);
        
        // Compter les notifications non lues
        $unreadQuery = "SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0";
        $unreadStmt = $conn->prepare($unreadQuery);
        $unreadStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $unreadStmt->execute();
        $unreadCount = $unreadStmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'notifications' => $formattedNotifications,
            'unread_count' => (int)$unreadCount
        ]);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
        
        switch ($action) {
            case 'mark_all_read':
                $query = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                
                echo json_encode(['success' => true, 'message' => 'Notifications marquées comme lues']);
                break;
                
            case 'mark_read':
                $notificationId = $input['notification_id'] ?? 0;
                $query = "UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $notificationId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
                
                echo json_encode(['success' => true, 'message' => 'Notification marquée comme lue']);
                break;
                
            case 'create':
                $title = $input['title'] ?? '';
                $message = $input['message'] ?? '';
                $type = $input['type'] ?? 'info';
                $targetUserId = $input['target_user_id'] ?? $userId;
                $data = json_encode($input['data'] ?? []);
                
                $query = "INSERT INTO notifications (user_id, sender_id, title, message, type, data, created_at) 
                         VALUES (:user_id, :sender_id, :title, :message, :type, :data, NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':user_id', $targetUserId, PDO::PARAM_INT);
                $stmt->bindParam(':sender_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':data', $data);
                $stmt->execute();
                
                echo json_encode(['success' => true, 'message' => 'Notification créée']);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
        }
    }
    
} catch (Exception $e) {
    error_log("Erreur API notifications: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
?>