<<<<<<< HEAD
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
=======
<!-- <?php
// back_office/controllers/LoginController.php
require_once(__DIR__ . '/BaseController.php');

class LoginController extends BaseController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = ?");
            $stmt->execute([$_POST['nom_utilisateur']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Version SANS password_verify() (mot de passe en clair)
            if ($user && $_POST['mot_de_passe'] === $user['mot_de_passe']) {
                session_start();
                $_SESSION['user'] = $user;
                $this->redirect('/Altiris/back_office/controllers/AnnoncesController.php?action=index');
            } else {
                $error = "Identifiant ou mot de passe incorrect";
                $this->render('login.php', ['error' => $error]);
            }
        } else {
            $this->render('login.php');
        }
    }

   public function logout() {
    // Démarrer la session si elle ne l'est pas déjà
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Détruire toutes les données de session
    $_SESSION = array();
    
    // Si vous voulez détruire complètement la session, effacez aussi
    // le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Finalement, détruire la session
    session_destroy();
    
    // Rediriger vers la page de login
    $this->redirect('/Altiris/back_office/view/login.php');
}

}

// Gestion des actions
$action = $_GET['action'] ?? 'login';
$controller = new LoginController();

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    die("Action non trouvée");
}
?> -->
>>>>>>> e094eeb8efa2873c0981c539bb3c64d9e64feb63
