<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Contacte {
    private $conn;
    private $table = 'contacte';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_cont DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("getAll : " . count($result) . " contacts récupérés");
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_cont = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("getById($id) : " . ($result ? "Trouvé" : "Non trouvé"));
        return $result;
    }

    public function updateResponse($id, $response) {
        $query = "UPDATE " . $this->table . " SET response = :response WHERE id_cont = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':response', $response);
        $success = $stmt->execute();
        error_log("updateResponse($id, $response) : " . ($success ? "Succès" : "Échec"));
        return $success;
    }

    public function countNewMessages() {
        $query = "SELECT COUNT(*) AS count FROM " . $this->table . " WHERE status = 'nouveau'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        error_log("countNewMessages : $count");
        return $count;
    }

    public function create($adresse, $email, $name, $subject, $tel, $text) {
        $query = "INSERT INTO " . $this->table . " (adresse, date_creation, email, name, subject, tel, text, status)
                  VALUES (:adresse, NOW(), :email, :name, :subject, :tel, :text, 'nouveau')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':text', $text);
        $success = $stmt->execute();
        error_log("create : " . ($success ? "Succès" : "Échec"));
        return $success;
    }

    public function markAsRead($id) {
        $query = "UPDATE " . $this->table . " SET status = 'lu' WHERE id_cont = :id AND status = 'nouveau'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $success = $stmt->execute();
        $rowCount = $stmt->rowCount();
        error_log("markAsRead($id) : " . ($success ? "Succès, $rowCount lignes affectées" : "Échec"));
        return $success && $rowCount > 0;
    }

    public function markAllMessagesAsRead() {
        $query = "UPDATE " . $this->table . " SET status = 'lu' WHERE status = 'nouveau'";
        $stmt = $this->conn->prepare($query);
        $success = $stmt->execute();
        error_log("markAllMessagesAsRead : " . ($success ? "Succès" : "Échec"));
        return $success;
    }

    public function getNotifications($since = 0) {
        $query = "SELECT id_cont AS id, subject AS title, text AS message, 'message' AS type, status = 'nouveau' AS is_new, date_creation AS created_at
                  FROM " . $this->table . "
                  WHERE date_creation > FROM_UNIXTIME(:since)
                  ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':since', $since, PDO::PARAM_INT);
        $stmt->execute();
        $notifications = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notifications[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'message' => $row['message'],
                'type' => $row['type'],
                'read' => !$row['is_new'],
                'created_at' => $row['created_at']
            ];
        }
        error_log("getNotifications($since) : " . count($notifications) . " notifications");
        return $notifications;
    }
}
?>