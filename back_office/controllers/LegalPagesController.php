<?php
require_once dirname(__DIR__) . '/models/LegalPages.php';

class LegalPagesController {
    public $model;

    public function __construct() {
        $this->model = new LegalPages();
    }

    public function index() {
        $pages = $this->model->readAll();
        return $pages;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = !empty($_POST['title']) ? trim($_POST['title']) : null;
            $slug = !empty($_POST['slug']) ? trim($_POST['slug']) : null;
            $content = !empty($_POST['content']) ? trim($_POST['content']) : null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($title && $slug && $content) {
                if (preg_match('/^[a-z0-9-]+$/', $slug)) {
                    if ($this->model->create($title, $slug, $content, $is_active)) {
                        header("Location: /Altiris/pages-legales");
                        exit;
                    } else {
                        $error = "Erreur lors de la création. Vérifiez que le slug est unique.";
                    }
                } else {
                    $error = "Le slug ne doit contenir que des lettres minuscules, chiffres et tirets.";
                }
            } else {
                $error = "Le titre, le slug et le contenu sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/legal_pages/create.php';
    }

    public function edit($id) {
        global $page, $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = !empty($_POST['title']) ? trim($_POST['title']) : null;
            $slug = !empty($_POST['slug']) ? trim($_POST['slug']) : null;
            $content = !empty($_POST['content']) ? trim($_POST['content']) : null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($title && $slug && $content) {
                if (preg_match('/^[a-z0-9-]+$/', $slug)) {
                    if ($this->model->update($id, $title, $slug, $content, $is_active)) {
                        header("Location: /Altiris/pages-legales");
                        exit;
                    } else {
                        $error = "Erreur lors de la mise à jour. Vérifiez que le slug est unique.";
                    }
                } else {
                    $error = "Le slug ne doit contenir que des lettres minuscules, chiffres et tirets.";
                }
            } else {
                $error = "Le titre, le slug et le contenu sont obligatoires.";
            }
        }
        $page = $this->model->read($id);
        if (!$page) {
            header("Location: /Altiris/pages-legales");
            exit;
        }
        require_once dirname(__DIR__) . '/views/legal_pages/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $this->model->delete($id);
        }
        header("Location: /Altiris/pages-legales");
        exit;
    }
}
?>