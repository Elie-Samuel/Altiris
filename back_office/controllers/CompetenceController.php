<?php
require_once dirname(__DIR__) . '/models/Competence.php';

class CompetenceController {
    private $model;

   public function __construct() {
        $this->model = new Competence();
    }

     public function index() {
        return $this->model->getAll();
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = !empty($_POST['nom']) ? trim($_POST['nom']) : null;
            $image_path = '';

            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $target_file = $uploadDir . $image_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_path = 'Assets/Images/' . $image_name;
                } else {
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            } else {
                $error = "Image requise.";
            }

            if ($nom && $image_path && !$error) {
                if ($this->model->create($nom, $image_path)) {
                    header("Location: /Altiris/competences?success=1&message=Compétence créée avec succès");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                    if ($image_path && file_exists($target_file)) unlink($target_file);
                }
            } else {
                $error = $error ?: "Nom requis.";
                if ($image_path && file_exists($target_file)) unlink($target_file);
            }
        }
        require_once dirname(__DIR__) . '/views/competences/create.php';
    }

    public function edit($id) {
        global $competence, $error;
        if (!$id || !is_numeric($id) || $id <= 0) {
            header("Location: /Altiris/competences");
            exit;
        }

        $competence = $this->model->getById($id);
        if (!$competence) {
            $error = "Compétence non trouvée.";
            require_once dirname(__DIR__) . '/views/competences//Altiris/competences'; // Rediriger vers index avec erreur
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = !empty($_POST['nom']) ? trim($_POST['nom']) : null;
            $image_path = $competence['image_path'] ?? null; // Utilise l'image existante si null

            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if ($competence['image_path'] && file_exists(dirname(__DIR__, 2) . '/' . $competence['image_path'])) {
                    unlink(dirname(__DIR__, 2) . '/' . $competence['image_path']);
                }
                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $target_file = $uploadDir . $image_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_path = 'Assets/Images/' . $image_name;
                } else {
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            }

            if ($nom && !$error) {
                if ($this->model->update($id, $nom, $image_path)) {
                    header("Location: /Altiris/competences?success=1&message=Compétence mise à jour avec succès");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                    if ($image_path && file_exists($target_file)) unlink($target_file);
                }
            } else {
                $error = $error ?: "Nom requis.";
                if ($image_path && file_exists($target_file)) unlink($target_file);
            }
        }

        require_once dirname(__DIR__) . '/views/competences/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $competence = $this->model->getById($id);
            if ($competence && isset($competence['image_path']) && $competence['image_path']) {
                $image_path = dirname(__DIR__, 2) . '/' . $competence['image_path'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $this->model->delete($id);
        }
        header("Location: /Altiris/competences?success=1&message=Compétence supprimée avec succès");
        exit;
    }
}
?>