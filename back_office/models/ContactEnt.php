<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class ContactEnt {
    private $conn;
    private $table = 'contact_ent';

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
        $query = "SELECT id_ent, adresse, lien_facebook, mail, phonne, 
                 DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i') as date_creation 
                 FROM $this->table 
                 ORDER BY date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT id_ent, adresse, lien_facebook, mail, phonne 
                 FROM $this->table 
                 WHERE id_ent = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM $this->table WHERE mail = :mail";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mail', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function create($data) {
        $query = "INSERT INTO $this->table 
                 (adresse, lien_facebook, mail, phonne, date_creation) 
                 VALUES (:adresse, :lien_facebook, :mail, :phonne, NOW())";

        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':adresse', $data['adresse']);
            $stmt->bindParam(':lien_facebook', $data['lien_facebook']);
            $stmt->bindParam(':mail', $data['mail']);
            $stmt->bindParam(':phonne', $data['phonne']);
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            $error = "Erreur création contact: " . $e->getMessage() . " | Data: " . json_encode($data);
            error_log($error);
            return $error;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE $this->table SET 
                 adresse = :adresse, 
                 lien_facebook = :lien_facebook, 
                 mail = :mail, 
                 phonne = :phonne, 
                 date_mise_a_jour = NOW() 
                 WHERE id_ent = :id";

        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':adresse', $data['adresse']);
            $stmt->bindParam(':lien_facebook', $data['lien_facebook']);
            $stmt->bindParam(':mail', $data['mail']);
            $stmt->bindParam(':phonne', $data['phonne']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            $error = "Erreur mise à jour contact: " . $e->getMessage();
            error_log($error);
            return $error;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id_ent = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur suppression contact: " . $e->getMessage());
            return false;
        }
    }
}
?>