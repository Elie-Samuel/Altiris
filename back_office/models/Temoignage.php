<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Temoignage {
    private $db;
    private $table = 'temoignage';

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->getConnection();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'initialisation de la base de données : " . $e->getMessage());
        }
    }

    public function create($nom, $rang, $text_tem, $image = null) {
        if (empty($nom) || empty($rang) || empty($text_tem)) {
            throw new Exception("Les champs nom, rang et texte du témoignage sont requis.");
        }
        if (strlen($nom) > 100 || strlen($rang) > 100) {
            throw new Exception("Le nom et le rang ne peuvent pas dépasser 100 caractères.");
        }

        try {
            $query = "INSERT INTO {$this->table} (Nom, rang, text_tem, image) 
                      VALUES (:nom, :rang, :text_tem, :image)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':rang', $rang, PDO::PARAM_STR);
            $stmt->bindParam(':text_tem', $text_tem, PDO::PARAM_STR);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR | PDO::PARAM_NULL);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création du témoignage : " . $e->getMessage());
        }
    }

    public function read($id) {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception("L'ID du témoignage doit être un entier positif.");
        }

        try {
            $query = "SELECT * FROM {$this->table} WHERE id_tem = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la lecture du témoignage : " . $e->getMessage());
        }
    }

    public function readAll($page = 1, $perPage = 10) {
        try {
            $offset = ($page - 1) * $perPage;
            $query = "SELECT * FROM {$this->table} ORDER BY id_tem DESC LIMIT :offset, :perPage";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des témoignages : " . $e->getMessage());
        }
    }

    public function getTotalCount() {
        try {
            $query = "SELECT COUNT(*) FROM {$this->table}";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors du comptage des témoignages : " . $e->getMessage());
        }
    }

    public function update($id, $nom, $rang, $text_tem, $image = null) {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception("L'ID du témoignage doit être un entier positif.");
        }
        if (empty($nom) || empty($rang) || empty($text_tem)) {
            throw new Exception("Les champs nom, rang et texte du témoignage sont requis.");
        }
        if (strlen($nom) > 100 || strlen($rang) > 100) {
            throw new Exception("Le nom et le rang ne peuvent pas dépasser 100 caractères.");
        }

        try {
            $query = "UPDATE {$this->table} SET Nom = :nom, rang = :rang, text_tem = :text_tem, image = :image
                      WHERE id_tem = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':rang', $rang, PDO::PARAM_STR);
            $stmt->bindParam(':text_tem', $text_tem, PDO::PARAM_STR);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR | PDO::PARAM_NULL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour du témoignage : " . $e->getMessage());
        }
    }

    public function delete($id) {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception("L'ID du témoignage doit être un entier positif.");
        }

        try {
            // Récupérer l'image associée pour la supprimer
            $query = "SELECT image FROM {$this->table} WHERE id_tem = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $image = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($image && !empty($image['image'])) {
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $image['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $query = "DELETE FROM {$this->table} WHERE id_tem = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression du témoignage : " . $e->getMessage());
        }
    }

    public function deleteMultiple($ids) {
        if (empty($ids)) {
            throw new Exception("Aucun ID de témoignage fourni.");
        }

        try {
            // Récupérer les images associées pour les supprimer
            $query = "SELECT image FROM {$this->table} WHERE id_tem IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";
            $stmt = $this->db->prepare($query);
            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, (int)$id, PDO::PARAM_INT);
            }
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($images as $image) {
                if (!empty($image['image'])) {
                    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $image['image'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            $query = "DELETE FROM {$this->table} WHERE id_tem IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";
            $stmt = $this->db->prepare($query);
            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, (int)$id, PDO::PARAM_INT);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression des témoignages : " . $e->getMessage());
        }
    }

    public function exportToCSV() {
        try {
            $query = "SELECT id_tem, Nom, rang, text_tem, image FROM {$this->table} ORDER BY id_tem DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'exportation des témoignages : " . $e->getMessage());
        }
    }

    public function search($term, $page = 1, $perPage = 10) {
        if (empty($term)) {
            return $this->readAll($page, $perPage);
        }
        $term = "%$term%";
        $offset = ($page - 1) * $perPage;

        try {
            $query = "SELECT * FROM {$this->table} WHERE Nom LIKE :term OR rang LIKE :term OR text_tem LIKE :term 
                      ORDER BY id_tem DESC LIMIT :offset, :perPage";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':term', $term, PDO::PARAM_STR);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la recherche des témoignages : " . $e->getMessage());
        }
    }

    public function getSearchCount($term) {
        if (empty($term)) {
            return $this->getTotalCount();
        }
        $term = "%$term%";

        try {
            $query = "SELECT COUNT(*) FROM {$this->table} WHERE Nom LIKE :term OR rang LIKE :term OR text_tem LIKE :term";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':term', $term, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors du comptage des témoignages recherchés : " . $e->getMessage());
        }
    }
}
?>