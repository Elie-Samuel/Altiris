<?php
require_once dirname(__DIR__) . '/models/Notification.php';
require_once dirname(__DIR__) . '/models/Utilisateur.php';

class NotificationController {
    private $notificationModel;
    private $userModel;

    public function __construct() {
        $this->notificationModel = new Notification();
        $this->userModel = new Utilisateur();
    }

    /**
     * Créer une notification pour un rendez-vous
     */
    public function createAppointmentNotification($contactData) {
        $title = "Nouvelle demande de rendez-vous";
        $message = "Une nouvelle demande de rendez-vous a été reçue de " . $contactData['name'];
        $data = [
            'contact_id' => $contactData['id_cont'],
            'contact_name' => $contactData['name'],
            'contact_email' => $contactData['email'],
            'subject' => $contactData['subject'],
            'phone' => $contactData['tel'] ?? '',
            'address' => $contactData['adresse'] ?? ''
        ];
        
        return $this->notificationModel->createForAllAdmins($title, $message, 'appointment', null, $data);
    }

    /**
     * Créer une notification pour un nouvel utilisateur
     */
    public function createUserNotification($userData, $action = 'created') {
        $actions = [
            'created' => 'Nouvel utilisateur inscrit',
            'updated' => 'Utilisateur mis à jour',
            'deleted' => 'Utilisateur supprimé',
            'activated' => 'Utilisateur activé',
            'deactivated' => 'Utilisateur désactivé'
        ];
        
        $title = $actions[$action] ?? 'Action utilisateur';
        $message = $title . ": " . $userData['nom_utilisateur'];
        $data = [
            'user_id' => $userData['id'],
            'username' => $userData['nom_utilisateur'],
            'email' => $userData['email'],
            'action' => $action
        ];
        
        return $this->notificationModel->createForAllAdmins($title, $message, 'user', $_SESSION['user_id'] ?? null, $data);
    }

    /**
     * Créer une notification système
     */
    public function createSystemNotification($title, $message, $type = 'info', $data = null) {
        return $this->notificationModel->createForAllAdmins($title, $message, $type, null, $data);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead($userId) {
        return $this->notificationModel->markAllAsRead($userId);
    }

    /**
     * Obtenir les notifications d'un utilisateur
     */
    public function getUserNotifications($userId, $limit = 50, $offset = 0) {
        return $this->notificationModel->getByUserId($userId, $limit, $offset);
    }

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getUnreadCount($userId) {
        return $this->notificationModel->getUnreadCount($userId);
    }

    /**
     * Supprimer les anciennes notifications (plus de 30 jours)
     */
    public function cleanOldNotifications() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $query = "DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $stmt = $conn->prepare($query);
            $deleted = $stmt->execute();
            
            if ($deleted) {
                $this->createSystemNotification(
                    "Nettoyage automatique",
                    "Les anciennes notifications ont été supprimées",
                    "info"
                );
            }
            
            return $deleted;
        } catch (Exception $e) {
            error_log("Erreur nettoyage notifications: " . $e->getMessage());
            return false;
        }
    }
}
?>