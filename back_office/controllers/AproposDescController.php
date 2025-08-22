<?php
require_once dirname(__DIR__) . '/models/AproposDesc.php';

class AproposDescController {
    private $model;

    public function __construct() {
        $this->model = new AproposDesc();
    }

    public function index() {
        $records = $this->model->readAll();
        return $records;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $sous_titre = !empty($_POST['sous_titre']) ? trim($_POST['sous_titre']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;

            if ($titre && $sous_titre && $text) {
                if ($this->model->create($titre, $sous_titre, $text)) {
                    header("Location: /Altiris/apropos-description");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                }
            } else {
                $error = "Le titre, le sous-titre et le texte sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/apropos_desc/create.php';
    }

    public function edit($id) {
        global $record, $error;
        if (!$id || !is_numeric($id) || $id <= 0) {
            header("Location: /Altiris/apropos-description");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $sous_titre = !empty($_POST['sous_titre']) ? trim($_POST['sous_titre']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;

            if ($titre && $sous_titre && $text) {
                if ($this->model->update($id, $titre, $sous_titre, $text)) {
                    header("Location: /Altiris/apropos-description");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                }
            } else {
                $error = "Le titre, le sous-titre et le texte sont obligatoires.";
            }
        }
        $record = $this->model->read($id);
        if (!$record) {
            header("Location: /Altiris/apropos-description");
            exit;
        }
        require_once dirname(__DIR__) . '/views/apropos_desc/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $this->model->delete($id);
        }
        header("Location: /Altiris/apropos-description");
        exit;
    }
}
?>