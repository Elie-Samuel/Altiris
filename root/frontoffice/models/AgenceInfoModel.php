<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class AgenceInfoModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAgenceInfo() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM agence_info LIMIT 1");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des infos agence: " . $e->getMessage());
            return null;
        }
    }
}