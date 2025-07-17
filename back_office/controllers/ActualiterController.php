<?php
require_once dirname(__DIR__) . '/models/Actualiter.php';

class ActualiterController {
    private $model;

    public function __construct() {
        $this->model = new Actualiter();
    }

    public function index() {
        return $this->model->readAll();
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $texte = !empty($_POST['texte']) ? trim($_POST['texte']) : null;
            $date = !empty($_POST['Date']) ? trim($_POST['Date']) : null;
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
            if ($texte && $image && !$error) {
                if ($date && strtotime($date) === false) {
                    $error = "Date invalide.";
                    unlink($targetFile); // Supprime l'image si la date est invalide
                } elseif ($this->model->create($texte, $date, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                    unlink($targetFile); // Supprime l'image en cas d'échec
                }
            } else {
                $error = $error ?: "Texte requis.";
                if ($image && file_exists($targetFile)) unlink($targetFile);
            }
        }
        require_once dirname(__DIR__) . '/views/actualiters/create.php';
    }

    public function edit($id) {
        global $actualiter, $error;
        if (!$id || !is_numeric($id) || $id <= 0) {
            header("Location: index.php");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $texte = !empty($_POST['texte']) ? trim($_POST['texte']) : null;
            $date = !empty($_POST['Date']) ? trim($_POST['Date']) : null;
            $image = $actualiter['image'] ?? null; // Garde l'image existante par défaut
            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if ($actualiter['image'] && file_exists(dirname(__DIR__, 2) . '/' . $actualiter['image'])) {
                    unlink(dirname(__DIR__, 2) . '/' . $actualiter['image']); // Supprime l'ancienne image
                }
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = 'Assets/Images/' . $imageName;
                } else {
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            }
            if ($texte && !$error) {
                if ($date && strtotime($date) === false) {
                    $error = "Date invalide.";
                } elseif ($this->model->update($id, $date, $texte, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                    if ($image && file_exists($targetFile)) unlink($targetFile);
                }
            } else {
                $error = $error ?: "Texte requis.";
                if ($image && file_exists($targetFile)) unlink($targetFile);
            }
        }
        $actualiter = $this->model->read($id);
        if (!$actualiter) {
            header("Location: index.php");
            exit;
        }
        require_once dirname(__DIR__) . '/views/actualiters/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $actualiter = $this->model->read($id);
            if ($actualiter && isset($actualiter['image']) && $actualiter['image']) {
                $imagePath = dirname(__DIR__, 2) . '/' . $actualiter['image'];
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