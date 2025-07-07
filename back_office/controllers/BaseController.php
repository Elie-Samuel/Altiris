<?php
// back_office/controllers/BaseController.php
require_once(__DIR__ . '/../../config/db.php');

class BaseController {
    protected $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    protected function render($viewPath, $data = []) {
        extract($data);
        require_once(__DIR__ . '/../view/' . $viewPath);
    }

    protected function redirect($url) {
        header("Location: $url");
        exit();
    }

    protected function isAdmin() {
        session_start();
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'administrateur';
    }

   protected function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user']);
}
protected function checkLogoutRequest() {
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        $this->logout();
    }
}
}
?>