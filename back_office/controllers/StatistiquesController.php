<?php
require_once '../../config/db.php';
require_once '../models/Statistique.php';

session_start();

class StatistiquesController {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function dashboard() {
        $statistique = new Statistique($this->pdo);
        $stats = $statistique->getStats();
        require_once '../components/header.php';
        require_once '../view/statistiques/dashboard.php';
        require_once '../components/footer.php';
    }
}
?>