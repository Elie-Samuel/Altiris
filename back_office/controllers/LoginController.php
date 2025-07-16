<?php
require_once __DIR__ . '/../config/db.php';

class LoginController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // Validation simple (à remplacer par une vraie vérification)
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['logged_in'] = true;
                header('Location: /back_office/views/annonces/index.php');
                exit;
            } else {
                $error = "Identifiants incorrects";
            }
        }
        require_once __DIR__ . '/../login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /back_office/login.php');
        exit;
    }
}
?>