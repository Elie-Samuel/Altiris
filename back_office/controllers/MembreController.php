<?php
require_once dirname(__DIR__) . '/models/Membre.php';

class MembreController {
    private $model;

    public function __construct() {
        $this->model = new Membre();
    }

    public function index() {
        return $this->model->getAll();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        // Débogage des données reçues
        error_log("Données POST reçues : " . json_encode($_POST));
        error_log("Données FILES reçues : " . json_encode($_FILES));

        // Validation des données
        $required = ['prenom', 'nom', 'email'];
        foreach ($required as $field) {
            if (empty(trim($_POST[$field]))) {
                return "Le champ " . ucfirst($field) . " est obligatoire";
            }
        }

        // Validation email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            return "Format d'email invalide";
        }

        // Vérifier si l'email existe déjà
        if ($this->model->emailExists($_POST['email'])) {
            return "Cet email est déjà utilisé";
        }

        // Traitement de la photo (facultative pour tester)
        $photo = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Vérification du type
            $mime = mime_content_type($_FILES['photo']['tmp_name']);
            if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                return "Seuls les formats JPG et PNG sont acceptés";
            }

            // Vérification de la taille (max 2MB)
            if ($_FILES['photo']['size'] > 2097152) {
                return "La photo ne doit pas dépasser 2MB";
            }

            $photo = file_get_contents($_FILES['photo']['tmp_name']);
        }

        // Préparation des données
        $data = [
            'prenom' => trim($_POST['prenom']),
            'nom' => trim($_POST['nom']),
            'email' => trim($_POST['email']),
            'role' => $_POST['role'] ?? 'utilisateur',
            'statut' => $_POST['statut'] ?? 'actif',
            'Tel' => !empty($_POST['Tel']) ? trim($_POST['Tel']) : null,
            'competce_mbr' => !empty($_POST['competce_mbr']) ? trim($_POST['competce_mbr']) : null,
            'lien_facebook' => !empty($_POST['lien_facebook']) ? trim($_POST['lien_facebook']) : null
        ];

        // Création du membre
        $result = $this->model->create($data, $photo);
        if ($result === true) {
            $lastId = $this->model->getConnection()->lastInsertId();
            error_log("Nouveau membre créé avec ID : $lastId");
            return true;
        } else {
            error_log("Échec création membre : " . $result);
            return $result;
        }
    }

    public function edit($id) {
        if (!is_numeric($id) || $id <= 0) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
            $required = ['prenom', 'nom', 'email'];
            foreach ($required as $field) {
                if (empty(trim($_POST[$field]))) {
                    return "Le champ " . ucfirst($field) . " est obligatoire";
                }
            }

            // Validation email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return "Format d'email invalide";
            }

            // Préparation des données
            $data = [
                'prenom' => trim($_POST['prenom']),
                'nom' => trim($_POST['nom']),
                'email' => trim($_POST['email']),
                'role' => $_POST['role'] ?? 'utilisateur',
                'statut' => $_POST['statut'] ?? 'actif',
                'Tel' => !empty($_POST['Tel']) ? trim($_POST['Tel']) : null,
                'competce_mbr' => !empty($_POST['competce_mbr']) ? trim($_POST['competce_mbr']) : null,
                'lien_facebook' => !empty($_POST['lien_facebook']) ? trim($_POST['lien_facebook']) : null
            ];

            // Traitement de la photo
            $photo = null;
            $remove_photo = isset($_POST['remove_photo']);
            
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $mime = mime_content_type($_FILES['photo']['tmp_name']);
                if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                    return "Seuls les formats JPG et PNG sont acceptés";
                }
                if ($_FILES['photo']['size'] > 2097152) {
                    return "La photo ne doit pas dépasser 2MB";
                }
                $photo = file_get_contents($_FILES['photo']['tmp_name']);
            }

            $result = $this->model->update($id, $data, $photo, $remove_photo);
            if ($result === true) {
                return true;
            } else {
                return $result;
            }
        }

        return $this->model->getById($id);
    }

    public function delete($id) {
        if (is_numeric($id) && $id > 0) {
            return $this->model->delete($id);
        }
        return false;
    }
}
?>