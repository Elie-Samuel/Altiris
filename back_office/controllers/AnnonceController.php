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
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = file_get_contents($_FILES['image']['tmp_name']);
            } else {
                $error = "Image requise.";
            }
            if ($text && $image && !$error) {
                if ($this->model->create($text, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                }
            } else {
                $error = $error ?: "Text requis.";
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
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = file_get_contents($_FILES['image']['tmp_name']);
            }
            if ($text && !$error) {
                if ($this->model->update($id, $text, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                }
            } else {
                $error = $error ?: "Text requis.";
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
            $this->model->delete($id);
        }
        header("Location: index.php");
        exit;
    }
}
?>