<?php
require_once dirname(__DIR__) . '/models/BlogDesc.php';

class BlogDescController {
    public $model;

    public function __construct() {
        $this->model = new BlogDesc();
    }

    public function index() {
        $blogDesc = $this->model->read();
        return $blogDesc;
    }

    public function create() {
        global $error;
        if ($this->model->read()) {
            $error = "Une description de blog existe déjà. Veuillez la modifier.";
            require_once dirname(__DIR__) . '/views/blog_desc/index.php';
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $sous_titre = !empty($_POST['sous_titre']) ? trim($_POST['sous_titre']) : null;
            $description = !empty($_POST['description']) ? trim($_POST['description']) : null;

            if ($titre && $sous_titre && $description) {
                if ($this->model->create($titre, $sous_titre, $description)) {
                    header("Location: /Altiris/description-blog");
                    exit;
                } else {
                    $error = "Erreur lors de la création.";
                }
            } else {
                $error = "Le titre, le sous-titre et la description sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/blog_desc/create.php';
    }

    public function edit($id) {
        global $blogDesc, $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $sous_titre = !empty($_POST['sous_titre']) ? trim($_POST['sous_titre']) : null;
            $description = !empty($_POST['description']) ? trim($_POST['description']) : null;

            if ($titre && $sous_titre && $description) {
                if ($this->model->update($id, $titre, $sous_titre, $description)) {
                    header("Location: /Altiris/description-blog");
                    exit;
                } else {
                    $error = "Erreur lors de la mise à jour.";
                }
            } else {
                $error = "Le titre, le sous-titre et la description sont obligatoires.";
            }
        }
        $blogDesc = $this->model->read($id);
        if (!$blogDesc) {
            header("Location: /Altiris/description-blog");
            exit;
        }
        require_once dirname(__DIR__) . '/views/blog_desc/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $this->model->delete($id);
        }
        header("Location: /Altiris/description-blog");
        exit;
    }
}
?>