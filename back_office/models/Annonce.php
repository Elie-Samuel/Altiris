<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Annonce {
    private $conn;
    private $table = 'annonce';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Échec de la connexion à la base de données.");
        }
    }

    public function create($titre, $titre1, $text, $image) {
        try {
            $query = "INSERT INTO " . $this->table . " (titre, titre1, text, image) VALUES (:titre, :titre1, :text, :image)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':titre1', $titre1);
            $stmt->bindParam(':text', $text);
            $stmt->bindParam(':image', $image);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création : " . $e->getMessage());
            return false;
        }
    }

    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
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

    public function update($id, $titre, $titre1, $text, $image) {
        try {
            $query = "UPDATE " . $this->table . " SET titre = :titre, titre1 = :titre1, text = :text, image = :image WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':titre1', $titre1);
            $stmt->bindParam(':text', $text);
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