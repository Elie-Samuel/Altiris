<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class AltirysInfo {
    private $conn;
    private $table = 'altirys_info';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Échec de la connexion à la base de données.");
        }
    }

    public function create($analyse, $mission, $vente_boost, $image) {
        try {
            $query = "INSERT INTO " . $this->table . " (analyse, mission, vente_boost, image) VALUES (:analyse, :mission, :vente_boost, :image)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':analyse', $analyse);
            $stmt->bindParam(':mission', $mission);
            $stmt->bindParam(':vente_boost', $vente_boost);
            $stmt->bindParam(':image', $image);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création : " . $e->getMessage());
            return false;
        }
    }

    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
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

    public function update($id, $analyse, $mission, $vente_boost, $image) {
        try {
            $query = "UPDATE " . $this->table . " SET analyse = :analyse, mission = :mission, vente_boost = :vente_boost, image = :image WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':analyse', $analyse);
            $stmt->bindParam(':mission', $mission);
            $stmt->bindParam(':vente_boost', $vente_boost);
            $stmt->bindParam(':image', $image);
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



