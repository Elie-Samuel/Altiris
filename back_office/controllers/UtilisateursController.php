<?php
// back_office/controllers/UtilisateursController.php
require_once(__DIR__ . '/BaseController.php');

class UtilisateursController extends BaseController {
    public function index() {
        if (!$this->isAdmin()) {
            die("Accès refusé");
        }

        $query = $this->db->query("SELECT * FROM utilisateurs");
        $utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);

        $this->render('utilisateurs/index.php', ['utilisateurs' => $utilisateurs]);
    }

    public function create() {
        if (!$this->isAdmin()) {
            die("Accès refusé");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hashedPassword = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("INSERT INTO utilisateurs 
                                      (nom_utilisateur, email, mot_de_passe, role) 
                                      VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nom_utilisateur'],
                $_POST['email'],
                $hashedPassword,
                $_POST['role']
            ]);

            $this->redirect('/Altiris/back_office/controllers/UtilisateursController.php?action=index');
        }

        $this->render('utilisateurs/create.php');
    }

    // Ajoutez ici les méthodes edit, delete, etc.
}

// Gestion des actions
$action = $_GET['action'] ?? 'index';
$controller = new UtilisateursController();

if (method_exists($controller, $action)) {
    $id = $_GET['id'] ?? null;
    $controller->$action($id);
} else {
    die("Action non trouvée");
}
?>