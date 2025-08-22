<?php
require_once dirname(__DIR__) . '/models/AproposValeur.php';

class AproposValeurController {
    private $model;

    public function __construct() {
        $this->model = new AproposValeur();
    }

    public function index() {
        $records = $this->model->readAll();
        return $records;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $icon_bootstrap = !empty($_POST['icon_bootstrap']) ? trim($_POST['icon_bootstrap']) : null;

            if ($titre && $text && $icon_bootstrap) {
                if (strlen($titre) > 255 || strlen($icon_bootstrap) > 255) {
                    $error = "Le titre et l'icône ne doivent pas dépasser 255 caractères.";
                } elseif (!preg_match('/^fa[s|r|l|b] fa-[a-z-]+$/i', $icon_bootstrap)) {
                    $error = "L'icône doit être une classe Font Awesome valide (ex: fas fa-rocket).";
                } elseif ($this->model->create($titre, $text, $icon_bootstrap)) {
                    header("Location: /Altiris/apropos-valeur");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                }
            } else {
                $error = "Le titre, le texte et l'icône sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/apropos_valeur/create.php';
    }

    public function edit($id) {
        global $record, $error;
        if (!$id || !is_numeric($id) || $id <= 0) {
            header("Location: /Altiris/apropos-valeur");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $icon_bootstrap = !empty($_POST['icon_bootstrap']) ? trim($_POST['icon_bootstrap']) : null;

            if ($titre && $text && $icon_bootstrap) {
                if (strlen($titre) > 255 || strlen($icon_bootstrap) > 255) {
                    $error = "Le titre et l'icône ne doivent pas dépasser 255 caractères.";
                } elseif (!preg_match('/^fa[s|r|l|b] fa-[a-z-]+$/i', $icon_bootstrap)) {
                    $error = "L'icône doit être une classe Font Awesome valide (ex: fas fa-rocket).";
                } elseif ($this->model->update($id, $titre, $text, $icon_bootstrap)) {
                    header("Location: /Altiris/apropos-valeur");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                }
            } else {
                $error = "Le titre, le texte et l'icône sont obligatoires.";
            }
        }
        $record = $this->model->read($id);
        if (!$record) {
            header("Location: /Altiris/apropos-valeur");
            exit;
        }
        require_once dirname(__DIR__) . '/views/apropos_valeur/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $this->model->delete($id);
        }
        header("Location: /Altiris/apropos-valeur");
        exit;
    }
}
?>