<?php
require_once dirname(__DIR__) . '/models/SocialLinks.php';

class SocialLinksController {
    public $model;

    public function __construct() {
        $this->model = new SocialLinks();
    }

    public function index() {
        $links = $this->model->readAll();
        return $links;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $platform = !empty($_POST['platform']) ? trim($_POST['platform']) : null;
            $url = !empty($_POST['url']) ? trim($_POST['url']) : null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $order_position = !empty($_POST['order_position']) && is_numeric($_POST['order_position']) ? (int)$_POST['order_position'] : null;
            $icon_class = !empty($_POST['icon_class']) ? trim($_POST['icon_class']) : null;

            if ($platform && $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    if ($this->model->create($platform, $url, $is_active, $order_position, $icon_class)) {
                        header("Location: /Altiris/liens-sociaux");
                        exit;
                    } else {
                        $error = "Erreur lors de la création.";
                    }
                } else {
                    $error = "L'URL fournie n'est pas valide.";
                }
            } else {
                $error = "La plateforme et l'URL sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/social_links/create.php';
    }

    public function edit($id) {
        global $link, $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $platform = !empty($_POST['platform']) ? trim($_POST['platform']) : null;
            $url = !empty($_POST['url']) ? trim($_POST['url']) : null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $order_position = !empty($_POST['order_position']) && is_numeric($_POST['order_position']) ? (int)$_POST['order_position'] : null;
            $icon_class = !empty($_POST['icon_class']) ? trim($_POST['icon_class']) : null;

            if ($platform && $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    if ($this->model->update($id, $platform, $url, $is_active, $order_position, $icon_class)) {
                        header("Location: /Altiris/liens-sociaux");
                        exit;
                    } else {
                        $error = "Erreur lors de la mise à jour.";
                    }
                } else {
                    $error = "L'URL fournie n'est pas valide.";
                }
            } else {
                $error = "La plateforme et l'URL sont obligatoires.";
            }
        }
        $link = $this->model->read($id);
        if (!$link) {
            header("Location: /Altiris/liens-sociaux");
            exit;
        }
        require_once dirname(__DIR__) . '/views/social_links/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $this->model->delete($id);
        }
        header("Location: /Altiris/liens-sociaux");
        exit;
    }
}
?>