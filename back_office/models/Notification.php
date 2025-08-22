<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Notification {
    private $conn;
    private $table = 'notifications';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Erreur de connexion à la base de données");
        }
    }

    public function create($userId, $title, $message, $type = 'info', $senderId = null, $data = null) {
        $query = "INSERT INTO {$this->table} (user_id, sender_id, title, message, type, data, is_read, created_at) 
                  VALUES (:user_id, :sender_id, :title, :message, :type, :data, 0, NOW())";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':type', $type);
            // Stocker le résultat de json_encode dans une variable
            $encodedData = json_encode($data ?? []);
            $stmt->bindParam(':data', $encodedData);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur création notification: " . $e->getMessage());
            return false;
        }
    }

    public function getByUserId($userId, $limit = 50, $offset = 0) {
        $query = "SELECT n.*, u.nom_utilisateur as sender_name 
                  FROM {$this->table} n 
                  LEFT JOIN utilisateurs u ON n.sender_id = u.id 
                  WHERE n.user_id = :user_id 
                  ORDER BY n.created_at DESC 
                  LIMIT :limit OFFSET :offset";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur récupération notifications: " . $e->getMessage());
            return [];
        }
    }

    public function getUnreadCount($userId) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = :user_id AND is_read = 0";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur comptage notifications: " . $e->getMessage());
            return 0;
        }
    }

    public function markAsRead($id, $userId) {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE id = :id AND user_id = :user_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur marquage notification: " . $e->getMessage());
            return false;
        }
    }

    public function markAllAsRead($userId) {
        $query = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = :user_id AND is_read = 0";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur marquage toutes notifications: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id, $userId) {
        $query = "DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur suppression notification: " . $e->getMessage());
            return false;
        }
    }

    public function createForAllAdmins($title, $message, $type = 'info', $senderId = null, $data = null) {
        // Récupérer tous les administrateurs
        $query = "SELECT id FROM utilisateurs WHERE Types IN ('Super admin', 'Admin') AND status = 'Actif'";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $success = true;
            foreach ($admins as $admin) {
                if (!$this->create($admin['id'], $title, $message, $type, $senderId, $data)) {
                    $success = false;
                }
            }
            
            return $success;
        } catch (PDOException $e) {
            error_log("Erreur notification tous admins: " . $e->getMessage());
            return false;
        }
    }

    public function createAppointmentNotification($contactData) {
        $title = "Nouvelle demande de rendez-vous";
        $message = "Une nouvelle demande de rendez-vous a été reçue de " . ($contactData['name'] ?? 'Inconnu');
        $data = [
            'contact_id' => $contactData['id_cont'],
            'contact_name' => $contactData['name'] ?? null,
            'contact_email' => $contactData['email'] ?? null,
            'contact_subject' => $contactData['subject'] ?? null,
            'contact_text' => $contactData['text'] ?? null,
            'contact_adresse' => $contactData['adresse'] ?? null,
            'contact_tel' => $contactData['tel'] ?? null
        ];
        
        return $this->createForAllAdmins($title, $message, 'message', null, $data);
    }

    public function createUserRegistrationNotification($userData) {
        $title = "Nouvel utilisateur inscrit";
        $message = "Un nouvel utilisateur s'est inscrit: " . $userData['nom_utilisateur'];
        $data = [
            'user_id' => $userData['id'],
            'username' => $userData['nom_utilisateur'],
            'email' => $userData['email']
        ];
        
        return $this->createForAllAdmins($title, $message, 'info', null, $data);
    }

    public function createSystemNotification($title, $message, $type = 'info') {
        return $this->createForAllAdmins($title, $message, $type, null, ['system' => true]);
    }
}
?>