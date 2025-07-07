<?php
class Statistique {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getStats() {
        $stmt = $this->pdo->query("SELECT a.titre, s.vues, s.clics FROM statistiques s JOIN annonces a ON s.id_annonce = a.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>