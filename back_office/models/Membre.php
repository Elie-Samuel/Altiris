<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Membre {
    private $conn;
    private $table = 'membres';

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
        $query = "SELECT id_membre, prenom, nom, email, role, statut, Tel, photo, competce_mbr, lien_facebook, 
                 DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i') as date_creation 
                 FROM $this->table 
                 ORDER BY date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT id_membre, prenom, nom, email, role, statut, Tel, photo, competce_mbr, lien_facebook 
                 FROM $this->table 
                 WHERE id_membre = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM $this->table WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function create($data, $photo = null) {
        $query = "INSERT INTO $this->table 
                 (prenom, nom, email, role, statut, Tel, photo, competce_mbr, lien_facebook, date_creation) 
                 VALUES (:prenom, :nom, :email, :role, :statut, :tel, :photo, :competce_mbr, :lien_facebook, NOW())";

        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':prenom', $data['prenom']);
            $stmt->bindParam(':nom', $data['nom']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':role', $data['role']);
            $stmt->bindParam(':statut', $data['statut']);
            
            $tel = !empty($data['Tel']) ? $data['Tel'] : null;
            $stmt->bindParam(':tel', $tel);
            
            $stmt->bindValue(':photo', $photo, $photo !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $competce_mbr = !empty($data['competce_mbr']) ? $data['competce_mbr'] : null;
            $lien_facebook = !empty($data['lien_facebook']) ? $data['lien_facebook'] : null;
            $stmt->bindParam(':competce_mbr', $competce_mbr, PDO::PARAM_STR);
            $stmt->bindParam(':lien_facebook', $lien_facebook, PDO::PARAM_STR);
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            $error = "Erreur création membre: " . $e->getMessage() . " | Data: " . json_encode($data);
            error_log($error);
            return $error;
        }
    }

    public function update($id, $data, $photo = null, $remove_photo = false) {
        $query = "UPDATE $this->table SET 
                 prenom = :prenom, 
                 nom = :nom, 
                 email = :email, 
                 role = :role, 
                 statut = :statut, 
                 Tel = :tel, 
                 competce_mbr = :competce_mbr, 
                 lien_facebook = :lien_facebook";
        
        if ($photo !== null || $remove_photo) {
            $query .= ", photo = :photo";
        }
        
        $query .= ", date_mise_a_jour = NOW() WHERE id_membre = :id";

        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':prenom', $data['prenom']);
            $stmt->bindParam(':nom', $data['nom']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':role', $data['role']);
            $stmt->bindParam(':statut', $data['statut']);
            
            $tel = !empty($data['Tel']) ? $data['Tel'] : null;
            $stmt->bindParam(':tel', $tel);
            
            $competce_mbr = !empty($data['competce_mbr']) ? $data['competce_mbr'] : null;
            $lien_facebook = !empty($data['lien_facebook']) ? $data['lien_facebook'] : null;
            $stmt->bindParam(':competce_mbr', $competce_mbr, PDO::PARAM_STR);
            $stmt->bindParam(':lien_facebook', $lien_facebook, PDO::PARAM_STR);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($photo !== null) {
                $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
            } elseif ($remove_photo) {
                $stmt->bindValue(':photo', null, PDO::PARAM_NULL);
            }
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            $error = "Erreur mise à jour membre: " . $e->getMessage();
            error_log($error);
            return $error;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id_membre = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur suppression membre: " . $e->getMessage());
            return false;
        }
    }
}
?>