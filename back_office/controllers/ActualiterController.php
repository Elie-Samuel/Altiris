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
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = file_get_contents($_FILES['image']['tmp_name']);
            } else {
                $error = "Image requise.";
            }
            if ($texte && $image && !$error) {
                if ($date && strtotime($date) === false) {
                    $error = "Date invalide.";
                } elseif ($this->model->create($texte, $date, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                }
            } else {
                $error = $error ?: "Texte requis.";
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
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = file_get_contents($_FILES['image']['tmp_name']);
            }
            if ($texte && !$error) {
                if ($date && strtotime($date) === false) {
                    $error = "Date invalide.";
                } elseif ($this->model->update($id, $date, $texte, $image)) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                }
            } else {
                $error = $error ?: "Texte requis.";
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
            $this->model->delete($id);
        }
        header("Location: index.php");
        exit;
    }
}
?>