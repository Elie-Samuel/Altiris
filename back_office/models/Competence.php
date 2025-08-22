<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Competence {
    private $conn;
    private $table = 'competence';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Échec de la connexion à la base de données. Vérifiez config/db.php.");
        }
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                error_log("Aucune donnée trouvée dans la table competence.");
            }
            return $result;
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture de tous : " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
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

    public function create($nom, $image_path) {
        try {
            $query = "INSERT INTO " . $this->table . " (nom, image_path) VALUES (:nom, :image_path)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':image_path', $image_path);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création : " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $nom, $image_path = null) {
        try {
            $query = $image_path ?
                "UPDATE " . $this->table . " SET nom = :nom, image_path = :image_path WHERE id = :id" :
                "UPDATE " . $this->table . " SET nom = :nom WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $nom);
            if ($image_path) $stmt->bindParam(':image_path', $image_path);
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