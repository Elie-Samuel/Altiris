<?php
require_once '../../config/db.php';
require_once '../models/Parametre.php';

session_start();

class ParametresController {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function index() {
        $parametre = new Parametre($this->pdo);
        $parametres = $parametre->all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->pdo->prepare("UPDATE parametres SET valeur = :valeur WHERE cle = :cle");
            $stmt->execute([':valeur' => $_POST['valeur'], ':cle' => $_POST['cle']]);
            header('Location: ' . BASE_URL . 'parametres');
            exit;
        }
        require_once '../components/header.php';
        require_once '../view/parametres/index.php';
        require_once '../components/footer.php';
    }
}
?>