<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class ContactPageSettingsModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getSettings() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM contact_page_settings LIMIT 1");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des paramètres de la page de contact: " . $e->getMessage());
            return false;
        }
    }
}
