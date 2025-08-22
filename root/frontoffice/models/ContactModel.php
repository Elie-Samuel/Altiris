<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class ContactModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getContactInfo() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM contact_ent LIMIT 1");
            return $stmt->fetch(PDO::FETCH_ASSOC) ?? false;
        } catch (PDOException $e) {
            error_log("ContactModel getContactInfo Error: " . $e->getMessage());
            return false;
        }
    }

    public function saveMessage($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO contacte 
                (name, email, tel, subject, adresse, text, response, date_creation)
                VALUES (:name, :email, :tel, :subject, :adresse, :text, 'pending', NOW())
            ");

            $result = $stmt->execute([
                ':name' => substr($data['name'], 0, 50),
                ':email' => substr($data['email'], 0, 50),
                ':tel' => substr($data['tel'] ?? 'Non spécifié', 0, 13),
                ':subject' => substr($data['subject'], 0, 100),
                ':adresse' => substr($data['adresse'] ?? 'Non spécifiée', 0, 50),
                ':text' => substr($data['message'], 0, 225)
            ]);

            if (!$result) {
                throw new PDOException(implode(' ', $stmt->errorInfo()));
            }

            return true;
        } catch (PDOException $e) {
            error_log("ContactModel saveMessage Error: " . $e->getMessage());
            throw $e;
        }
    }
}