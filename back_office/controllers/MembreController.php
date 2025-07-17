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

        error_log("Données POST reçues : " . json_encode($_POST));
        error_log("Données FILES reçues : " . json_encode($_FILES));

        $required = ['prenom', 'nom', 'email'];
        foreach ($required as $field) {
            if (empty(trim($_POST[$field]))) {
                return "Le champ " . ucfirst($field) . " est obligatoire";
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            return "Format d'email invalide";
        }

        if ($this->model->emailExists($_POST['email'])) {
            return "Cet email est déjà utilisé";
        }

        $photo = null;
        $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['photo']['tmp_name'];
            if (file_exists($tmpFile)) {
                $mime = mime_content_type($tmpFile);
                if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                    return "Seuls les formats JPG et PNG sont acceptés";
                }
                if ($_FILES['photo']['size'] > 2097152) {
                    return "La photo ne doit pas dépasser 2MB";
                }
                $photoName = time() . '_' . basename($_FILES['photo']['name']);
                $targetFile = $uploadDir . $photoName;
                if (move_uploaded_file($tmpFile, $targetFile)) {
                    $photo = 'Assets/Images/' . $photoName;
                } else {
                    error_log("Échec du déplacement du fichier temporaire vers $targetFile");
                    return "Erreur lors du téléchargement de la photo.";
                }
            } else {
                error_log("Fichier temporaire $tmpFile introuvable");
                return "Fichier temporaire introuvable.";
            }
        }

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

        $result = $this->model->create($data, $photo);
        if ($result === true) {
            $lastId = $this->model->getConnection()->lastInsertId();
            error_log("Nouveau membre créé avec ID : $lastId");
            return true;
        } else {
            error_log("Échec création membre : " . $result);
            if ($photo && file_exists($targetFile)) unlink($targetFile);
            return $result;
        }
    }

    public function edit($id) {
        if (!is_numeric($id) || $id <= 0) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['prenom', 'nom', 'email'];
            foreach ($required as $field) {
                if (empty(trim($_POST[$field]))) {
                    return "Le champ " . ucfirst($field) . " est obligatoire";
                }
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return "Format d'email invalide";
            }

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

            $photo = null;
            $remove_photo = isset($_POST['remove_photo']);
            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $tmpFile = $_FILES['photo']['tmp_name'];
                if (file_exists($tmpFile)) {
                    $mime = mime_content_type($tmpFile);
                    if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                        return "Seuls les formats JPG et PNG sont acceptés";
                    }
                    if ($_FILES['photo']['size'] > 2097152) {
                        return "La photo ne doit pas dépasser 2MB";
                    }
                    $photoName = time() . '_' . basename($_FILES['photo']['name']);
                    $targetFile = $uploadDir . $photoName;
                    if (move_uploaded_file($tmpFile, $targetFile)) {
                        $photo = 'Assets/Images/' . $photoName;
                    } else {
                        error_log("Échec du déplacement du fichier temporaire vers $targetFile");
                        return "Erreur lors du téléchargement de la photo.";
                    }
                } else {
                    error_log("Fichier temporaire $tmpFile introuvable");
                    return "Fichier temporaire introuvable.";
                }
            }

            $result = $this->model->update($id, $data, $photo, $remove_photo);
            if ($result === true) {
                return true;
            } else {
                if ($photo && file_exists($targetFile)) unlink($targetFile);
                return $result ?: "Erreur lors de la mise à jour.";
            }
        }

        $membre = $this->model->getById($id);
        if (!$membre) {
            header("Location: index.php");
            exit;
        }
        return $membre;
    }

    public function delete($id) {
        if (is_numeric($id) && $id > 0) {
            $membre = $this->model->getById($id);
            if ($membre && isset($membre['photo']) && $membre['photo']) {
                $imagePath = dirname(__DIR__, 2) . '/' . $membre['photo'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            return $this->model->delete($id);
        }
        return false;
    }
}
?>