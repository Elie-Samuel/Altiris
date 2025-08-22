<?php
require_once dirname(__DIR__) . '/models/AproposHist.php';

class AproposHistController {
    public $model;

    public function __construct() {
        $this->model = new AproposHist();
    }

    public function index() {
        $history = $this->model->readAll();
        return $history;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $date = !empty($_POST['date']) ? trim($_POST['date']) : null;

            if ($titre && $text && $date) {
                if ($this->model->create($titre, $text, $date)) {
                    header("Location: /Altiris/apropos-historique");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                }
            } else {
                $error = "Le titre, le texte et la date sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/apropos_hist/create.php';
    }

    public function edit($id) {
        global $history, $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $date = !empty($_POST['date']) ? trim($_POST['date']) : null;

            if ($titre && $text && $date) {
                if ($this->model->update($id, $titre, $text, $date)) {
                    header("Location: /Altiris/apropos-historique");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                }
            } else {
                $error = "Le titre, le texte et la date sont obligatoires.";
            }
        }
        $history = $this->model->read($id);
        if (!$history) {
            header("Location: /Altiris/apropos-historique");
            exit;
        }
        require_once dirname(__DIR__) . '/views/apropos_hist/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $this->model->delete($id);
        }
        header("Location: /Altiris/apropos-historique");
        exit;
    }
}
?>