<?php
// altiris/root/frontoffice/models/ContactModel.php
namespace Altiris\FrontOffice\Models; // Added namespace

use PDO;
use PDOException;

class ContactModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getContactInfo() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM contact_ent LIMIT 1");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des informations de contact: " . $e->getMessage());
            return false;
        }
    }
}
