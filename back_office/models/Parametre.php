<?php
class Parametre {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM parametres");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>