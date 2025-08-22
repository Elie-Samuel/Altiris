<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class SocialLinks {
    private $conn;
    private $table = 'social_links';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Échec de la connexion à la base de données.");
        }
    }

    public function create($platform, $url, $is_active, $order_position, $icon_class) {
        try {
            $query = "INSERT INTO " . $this->table . " (platform, url, is_active, order_position, icon_class) 
                      VALUES (:platform, :url, :is_active, :order_position, :icon_class)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':platform', $platform);
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
            $stmt->bindParam(':order_position', $order_position, PDO::PARAM_INT);
            $stmt->bindParam(':icon_class', $icon_class);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création : " . $e->getMessage());
            return false;
        }
    }

    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY order_position ASC, id ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture de tous : " . $e->getMessage());
            return [];
        }
    }

    public function read($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture : " . $e->getMessage());
            return null;
        }
    }

    public function update($id, $platform, $url, $is_active, $order_position, $icon_class) {
        try {
            $query = "UPDATE " . $this->table . " SET platform = :platform, url = :url, is_active = :is_active, order_position = :order_position, icon_class = :icon_class WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':platform', $platform);
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':is_active', $is_active, PDO::PARAM_INT);
            $stmt->bindParam(':order_position', $order_position, PDO::PARAM_INT);
            $stmt->bindParam(':icon_class', $icon_class);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour : " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage());
            return false;
        }
    }
}
?>