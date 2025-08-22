<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class ContactFooterModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getFooterContactInfo() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT mail, phonne, adresse, lien_facebook 
                FROM contact_ent 
                ORDER BY date_creation DESC 
                LIMIT 1
            ");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur ContactFooterModel: " . $e->getMessage());
            return null;
        }
    }
}