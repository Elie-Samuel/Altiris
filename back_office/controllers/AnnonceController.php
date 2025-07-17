<?php
require_once dirname(__DIR__) . '/models/Annonce.php';

class AnnonceController {
    private $model;

    public function __construct() {
        $this->model = new Annonce();
    }

    public function index() {
        $annonces = $this->model->readAll();
        return $annonces;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $image = null;
            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = 'Assets/Images/' . $imageName;
                } else {
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            } else {
                $error = "Image requise.";
            }
            if ($text && $image && !$error) {
                if ($this->model->create($text, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                    if ($image && file_exists($targetFile)) unlink($targetFile);
                }
            } else {
                $error = $error ?: "Text requis.";
                if ($image && file_exists($targetFile)) unlink($targetFile);
            }
        }
        require_once dirname(__DIR__) . '/views/annonces/create.php';
    }

    public function edit($id) {
        global $annonce, $error;
        if (!$id || !is_numeric($id) || $id <= 0) {
            header("Location: index.php");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $image = $annonce['image'] ?? null; // Garde l'image existante par défaut
            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if ($annonce['image'] && file_exists(dirname(__DIR__, 2) . '/' . $annonce['image'])) {
                    unlink(dirname(__DIR__, 2) . '/' . $annonce['image']); // Supprime l'ancienne image
                }
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = 'Assets/Images/' . $imageName;
                } else {
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            }
            if ($text && !$error) {
                if ($this->model->update($id, $text, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                    if ($image && file_exists($targetFile)) unlink($targetFile);
                }
            } else {
                $error = $error ?: "Text requis.";
                if ($image && file_exists($targetFile)) unlink($targetFile);
            }
        }
        $annonce = $this->model->read($id);
        if (!$annonce) {
            header("Location: index.php");
            exit;
        }
        require_once dirname(__DIR__) . '/views/annonces/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $annonce = $this->model->read($id);
            if ($annonce && isset($annonce['image']) && $annonce['image']) {
                $imagePath = dirname(__DIR__, 2) . '/' . $annonce['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $this->model->delete($id);
        }
        header("Location: index.php");
        exit;
    }
}
?>