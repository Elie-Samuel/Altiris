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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? $_POST['titre'] : null;
            $texte = !empty($_POST['texte']) ? $_POST['texte'] : null;
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['tmp_name'])) {
                $image = file_get_contents($_FILES['image']['tmp_name']);
            }
            if ($texte && $image) {
                if ($this->model->create($titre, $texte, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la création du service.";
                }
            } else {
                $error = "Veuillez fournir un texte et une image valide.";
            }
            require_once dirname(__DIR__) . '/views/services/create.php';
        } else {
            require_once dirname(__DIR__) . '/views/services/create.php';
        }
    }

    public function edit($id) {
        if (!isset($id) || !is_numeric($id) || $id <= 0) {
            $error = "ID de service invalide.";
            require_once dirname(__DIR__) . '/views/services/edit.php';
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? $_POST['titre'] : null;
            $texte = !empty($_POST['texte']) ? $_POST['texte'] : null;
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['tmp_name'])) {
                $image = file_get_contents($_FILES['image']['tmp_name']);
            }
            if ($texte) {
                if ($this->model->update($id, $titre, $texte, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour du service.";
                }
            } else {
                $error = "Veuillez fournir un texte.";
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
        $this->model->delete($id);
        header("Location: index.php");
        exit;
    }
}
?>