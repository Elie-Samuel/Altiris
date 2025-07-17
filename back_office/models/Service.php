<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Service {
    private $conn;
    private $table = 'service';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($titre, $texte, $image) {
        $query = $image ? 
            "INSERT INTO " . $this->table . " (titre, texte, image) VALUES (:titre, :texte, :image)" :
            "INSERT INTO " . $this->table . " (titre, texte) VALUES (:titre, :texte)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':texte', $texte);
        if ($image) {
            $stmt->bindParam(':image', $image); // Chemin de l'image
        }
        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $titre, $texte, $image = null) {
        $query = $image ? 
            "UPDATE " . $this->table . " SET titre = :titre, texte = :texte, image = :image WHERE id = :id" :
            "UPDATE " . $this->table . " SET titre = :titre, texte = :texte WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':texte', $texte);
        if ($image) {
            $stmt->bindParam(':image', $image); // Chemin de l'image
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>