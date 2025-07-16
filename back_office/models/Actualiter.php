<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Actualiter {
    private $conn;
    private $table = 'actualiter';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) die("Erreur de connexion à la base de données.");
    }

    public function create($texte, $date, $image) {
        $query = "INSERT INTO $this->table (texte, Date, image) VALUES (:texte, :Date, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':texte', $texte);
        $stmt->bindParam(':Date', $date);
        $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT id, Date, texte, image FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($id) {
        $query = "SELECT id, Date, texte, image FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $date, $text, $image = null) {
        if ($image) {
            $query = "UPDATE $this->table SET Date = :Date, texte = :texte, image = :image WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        } else {
            $query = "UPDATE $this->table SET Date = :Date, texte = :texte WHERE id = :id";
            $stmt = $this->conn->prepare($query);
        }
        $stmt->bindParam(':Date', $date);
        $stmt->bindParam(':texte', $text);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>