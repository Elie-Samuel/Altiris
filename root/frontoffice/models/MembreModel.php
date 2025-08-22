<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class MembreModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllActiveMembers() {
        try {
            $query = "SELECT id_membre, prenom, nom, role, photo, Tel, email, lien_facebook, competce_mbr 
                      FROM membres 
                      ORDER BY nom, prenom";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[MembreModel] Error: " . $e->getMessage());
            return [];
        }
    }
}