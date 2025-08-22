<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class CompetenceModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllCompetences() {
        try {
            $stmt = $this->db->query("SELECT id, nom, image_path FROM competence");
            $competences = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug
            error_log("Competences from DB: " . print_r($competences, true));
            
            return $competences;
        } catch (PDOException $e) {
            error_log("Error in CompetenceModel::getAllCompetences(): " . $e->getMessage());
            return [];
        }
    }
}