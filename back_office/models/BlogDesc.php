<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class BlogDesc {
    private $conn;
    private $table = 'blog_desc';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Échec de la connexion à la base de données.");
        }
    }

    public function create($titre, $sous_titre, $description) {
        try {
            $query = "INSERT INTO " . $this->table . " (titre, sous_titre, description) 
                      VALUES (:titre, :sous_titre, :description)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':sous_titre', $sous_titre);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création : " . $e->getMessage());
            return false;
        }
    }

    public function read($id = null) {
        try {
            if ($id) {
                $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $query = "SELECT * FROM " . $this->table . " LIMIT 1";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la lecture : " . $e->getMessage());
            return null;
        }
    }

    public function update($id, $titre, $sous_titre, $description) {
        try {
            $query = "UPDATE " . $this->table . " SET titre = :titre, sous_titre = :sous_titre, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':sous_titre', $sous_titre);
            $stmt->bindParam(':description', $description);
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