<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class BlogPost {
    private $conn;
    private $table = 'blog_posts';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Échec de la connexion à la base de données.");
        }
    }

    public function create($title, $content, $excerpt, $category, $published, $image_path) {
        try {
            $query = "INSERT INTO " . $this->table . " (title, content, excerpt, category, published, image_path) VALUES (:title, :content, :excerpt, :category, :published, :image_path)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':excerpt', $excerpt);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':published', $published, PDO::PARAM_INT);
            $stmt->bindParam(':image_path', $image_path);
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

    public function update($id, $title, $content, $excerpt, $category, $published, $image_path) {
        try {
            $query = "UPDATE " . $this->table . " SET title = :title, content = :content, excerpt = :excerpt, category = :category, published = :published, image_path = :image_path WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':excerpt', $excerpt);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':published', $published, PDO::PARAM_INT);
            $stmt->bindParam(':image_path', $image_path);
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