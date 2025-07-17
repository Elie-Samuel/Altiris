<?php
require_once dirname(__DIR__) . '/models/Service.php';

class ServiceController {
    private $model;

    public function __construct() {
        $this->model = new Service();
    }

    public function index() {
        $services = $this->model->readAll();
        return $services;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? $_POST['titre'] : null;
            $texte = !empty($_POST['texte']) ? $_POST['texte'] : null;
            $image = null;
            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['tmp_name'])) {
                $imageName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = 'Assets/Images/' . $imageName;
                } else {
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            } else {
                $error = "Veuillez fournir une image valide.";
            }
            if ($texte && $image && !$error) {
                if ($this->model->create($titre, $texte, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la création du service.";
                    if ($image && file_exists($targetFile)) unlink($targetFile);
                }
            } else {
                $error = $error ?: "Veuillez fournir un texte.";
                if ($image && file_exists($targetFile)) unlink($targetFile);
            }
        }
        require_once dirname(__DIR__) . '/views/services/create.php';
    }

    public function edit($id) {
        global $service, $error;
        if (!isset($id) || !is_numeric($id) || $id <= 0) {
            $error = "ID de service invalide.";
            require_once dirname(__DIR__) . '/views/services/edit.php';
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? $_POST['titre'] : null;
            $texte = !empty($_POST['texte']) ? $_POST['texte'] : null;
            $image = $service['image'] ?? null; // Garde l'image existante par défaut
            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['tmp_name'])) {
                if ($service['image'] && file_exists(dirname(__DIR__, 2) . '/' . $service['image'])) {
                    unlink(dirname(__DIR__, 2) . '/' . $service['image']); // Supprime l'ancienne image
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
                if ($this->model->update($id, $titre, $texte, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour du service.";
                    if ($image && file_exists($targetFile)) unlink($targetFile);
                }
            } else {
                $error = $error ?: "Veuillez fournir un texte.";
                if ($image && file_exists($targetFile)) unlink($targetFile);
            }
        }
        $service = $this->model->read($id);
        if (!$service) {
            $error = "Service introuvable.";
        }
        require_once dirname(__DIR__) . '/views/services/edit.php';
    }

    public function delete($id) {
        if (!isset($id) || !is_numeric($id) || $id <= 0) {
            header("Location: index.php");
            exit;
        }
        $service = $this->model->read($id);
        if ($service && isset($service['image']) && $service['image']) {
            $imagePath = dirname(__DIR__, 2) . '/' . $service['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $this->model->delete($id);
        header("Location: index.php");
        exit;
    }
}
?>