<?php
// back_office/controllers/AnnoncesController.php
require_once(__DIR__ . '/BaseController.php');

class AnnoncesController extends BaseController {
    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/Altiris/back_office/view/login.php');
        }

        $query = $this->db->query("SELECT a.*, u.nom_utilisateur 
                                  FROM annonces a 
                                  LEFT JOIN utilisateurs u ON a.cree_par = u.id");
        $annonces = $query->fetchAll(PDO::FETCH_ASSOC);

        $this->render('annonces/index.php', ['annonces' => $annonces]);
    }

    public function create() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/Altiris/back_office/view/login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("INSERT INTO annonces 
                                      (titre, description, url_media, date_debut, date_fin, statut, categorie, cree_par) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['titre'],
                $_POST['description'],
                $_POST['url_media'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['statut'],
                $_POST['categorie'],
                $_SESSION['user']['id']
            ]);

            $this->redirect('/Altiris/back_office/controllers/AnnoncesController.php?action=index');
        }

        $this->render('annonces/create.php');
    }

    public function edit($id) {
        if (!$this->isLoggedIn()) {
            $this->redirect('/Altiris/back_office/view/login.php');
        }

        $stmt = $this->db->prepare("SELECT * FROM annonces WHERE id = ?");
        $stmt->execute([$id]);
        $annonce = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("UPDATE annonces 
                                      SET titre = ?, description = ?, url_media = ?, 
                                          date_debut = ?, date_fin = ?, statut = ?, categorie = ? 
                                      WHERE id = ?");
            $stmt->execute([
                $_POST['titre'],
                $_POST['description'],
                $_POST['url_media'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['statut'],
                $_POST['categorie'],
                $id
            ]);

            $this->redirect('/Altiris/back_office/controllers/AnnoncesController.php?action=index');
        }

        $this->render('annonces/edit.php', ['annonce' => $annonce]);
    }
}

// Gestion des actions
$action = $_GET['action'] ?? 'index';
$controller = new AnnoncesController();

if (method_exists($controller, $action)) {
    $id = $_GET['id'] ?? null;
    $controller->$action($id);
} else {
    die("Action non trouvée");
}
?>