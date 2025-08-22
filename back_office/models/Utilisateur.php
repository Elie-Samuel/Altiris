<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Utilisateur {
    private $conn;
    private $table = 'utilisateurs';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Erreur de connexion à la base de données");
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function getAll() {
        $query = "SELECT id, nom_utilisateur, email, profil, status, Types, 
                     DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i') as date_creation 
                     FROM $this->table 
                     ORDER BY date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function read($id) {
        $query = "SELECT id, nom_utilisateur, email, profil, status, Types 
                     FROM $this->table 
                     WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        return $this->read($id);
    }

    public function search($term) {
        $term = '%' . $term . '%';
        $query = "SELECT id, nom_utilisateur, email, profil, status, Types, 
                     DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i') as date_creation 
                     FROM $this->table 
                     WHERE nom_utilisateur LIKE :term OR email LIKE :term 
                     ORDER BY date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':term', $term);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM $this->table WHERE email = :email";
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function usernameExists($nom_utilisateur, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM $this->table WHERE nom_utilisateur = :nom_utilisateur";
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function create($data) {
        $query = "INSERT INTO $this->table 
                     (nom_utilisateur, email, mot_de_passe, profil, status, Types, date_creation) 
                     VALUES (:nom_utilisateur, :email, :mot_de_passe, :profil, :status, :Types, NOW())";

        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nom_utilisateur', $data['nom_utilisateur']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':mot_de_passe', $data['mot_de_passe']);
            $profil = !empty($data['profil']) ? $data['profil'] : null;
            $stmt->bindParam(':profil', $profil);
            $stmt->bindParam(':status', $data['status']);
            $Types = !empty($data['Types']) ? $data['Types'] : null;
            $stmt->bindParam(':Types', $Types);
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            $error = "Erreur création utilisateur: " . $e->getMessage() . " | Data: " . json_encode($data);
            error_log($error);
            return $error;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE $this->table SET 
                     nom_utilisateur = :nom_utilisateur, 
                     email = :email, 
                     profil = :profil, 
                     status = :status, 
                     Types = :Types";
        
        if (!empty($data['mot_de_passe'])) {
            $query .= ", mot_de_passe = :mot_de_passe";
        }
        
        $query .= " WHERE id = :id";

        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nom_utilisateur', $data['nom_utilisateur']);
            $stmt->bindParam(':email', $data['email']);
            $profil = !empty($data['profil']) ? $data['profil'] : null;
            $stmt->bindParam(':profil', $profil);
            $stmt->bindParam(':status', $data['status']);
            $Types = !empty($data['Types']) ? $data['Types'] : null;
            $stmt->bindParam(':Types', $Types);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if (!empty($data['mot_de_passe'])) {
                $stmt->bindParam(':mot_de_passe', $data['mot_de_passe']);
            }
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            $error = "Erreur mise à jour utilisateur: " . $e->getMessage();
            error_log($error);
            return $error;
        }
    }

    public function changeStatus($id, $status) {
        $query = "UPDATE $this->table SET status = :status WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur changement statut: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur suppression utilisateur: " . $e->getMessage());
            return false;
        }
    }
}
?>