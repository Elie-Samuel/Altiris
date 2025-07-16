<?php
<<<<<<< HEAD
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

    public function create($text, $image) {
        try {
            $query = "INSERT INTO " . $this->table . " (text, image) VALUES (:text, :image)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':text', $text);
            $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création : " . $e->getMessage());
            return false;
        }
    }

    public function readAll() {
        try {
            $query = "SELECT * FROM " . $this->table;
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
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture : " . $e->getMessage());
            return null;
        }
    }

    public function update($id, $text, $image = null) {
        try {
            $query = $image ? 
                "UPDATE " . $this->table . " SET text = :text, image = :image WHERE id = :id" :
                "UPDATE " . $this->table . " SET text = :text WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':text', $text);
            if ($image) $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $id);
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
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression : " . $e->getMessage());
            return false;
        }
=======
class Annonce {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM annonces");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM annonces WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
>>>>>>> e094eeb8efa2873c0981c539bb3c64d9e64feb63
    }
}
?>