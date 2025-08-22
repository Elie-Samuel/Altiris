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
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $titre1 = !empty($_POST['titre1']) ? trim($_POST['titre1']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $image = null;

            if ($titre && $titre1 && $text) {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
                    $max_size = 5 * 1024 * 1024; // 5MB
                    $file_type = $_FILES['image']['type'];
                    $file_size = $_FILES['image']['size'];
                    $file_tmp = $_FILES['image']['tmp_name'];
                    $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $file_name = time() . '_' . basename($_FILES['image']['name']);
                    $upload_dir = dirname(__DIR__, 2) . '/Assets/Images/';
                    $upload_path = $upload_dir . $file_name;

                    if (!in_array($file_type, $allowed_types)) {
                        $error = "Type de fichier non autorisé. Seuls JPG, JPEG et PNG sont acceptés.";
                    } elseif ($file_size > $max_size) {
                        $error = "La taille du fichier dépasse la limite de 5 Mo.";
                    } elseif (!file_exists($upload_dir) && !mkdir($upload_dir, 0755, true)) {
                        $error = "Impossible de créer le dossier d'upload.";
                    } elseif (!move_uploaded_file($file_tmp, $upload_path)) {
                        $error = "Erreur lors du téléchargement de l'image.";
                    } else {
                        $image = 'Assets/Images/' . $file_name;
                    }
                } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $error = "Erreur lors du téléchargement de l'image.";
                }

                if (!isset($error)) {
                    if ($this->model->create($titre, $titre1, $text, $image)) {
                        header("Location: /Altiris/annonces");
                        exit;
                    } else {
                        $error = "Erreur lors de la création.";
                        if ($image && file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }
                }
            } else {
                $error = "Le titre, le titre supplémentaire et le texte sont obligatoires.";
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
            $titre = !empty($_POST['titre']) ? trim($_POST['titre']) : null;
            $titre1 = !empty($_POST['titre1']) ? trim($_POST['titre1']) : null;
            $text = !empty($_POST['text']) ? trim($_POST['text']) : null;
            $image = null;

            if ($titre && $titre1 && $text) {
                $current_annonce = $this->model->read($id);
                $old_image = $current_annonce['image'] ?? null;

                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
                    $max_size = 5 * 1024 * 1024; // 5MB
                    $file_type = $_FILES['image']['type'];
                    $file_size = $_FILES['image']['size'];
                    $file_tmp = $_FILES['image']['tmp_name'];
                    $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $file_name = time() . '_' . basename($_FILES['image']['name']);
                    $upload_dir = dirname(__DIR__, 2) . '/Assets/Images/';
                    $upload_path = $upload_dir . $file_name;

                    if (!in_array($file_type, $allowed_types)) {
                        $error = "Type de fichier non autorisé. Seuls JPG, JPEG et PNG sont acceptés.";
                    } elseif ($file_size > $max_size) {
                        $error = "La taille du fichier dépasse la limite de 5 Mo.";
                    } elseif (!file_exists($upload_dir) && !mkdir($upload_dir, 0755, true)) {
                        $error = "Impossible de créer le dossier d'upload.";
                    } elseif (!move_uploaded_file($file_tmp, $upload_path)) {
                        $error = "Erreur lors du téléchargement de l'image.";
                    } else {
                        $image = 'Assets/Images/' . $file_name;
                        if ($old_image && file_exists(dirname(__DIR__, 2) . '/' . $old_image)) {
                            unlink(dirname(__DIR__, 2) . '/' . $old_image);
                        }
                    }
                } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $error = "Erreur lors du téléchargement de l'image.";
                } else {
                    $image = $old_image;
                }

                if (!isset($error)) {
                    if ($this->model->update($id, $titre, $titre1, $text, $image)) {
                        header("Location: /Altiris/annonces");
                        exit;
                    } else {
                        $error = "Erreur lors de la mise à jour.";
                        if ($image && $image !== $old_image && file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }
                }
            } else {
                $error = "Le titre, le titre supplémentaire et le texte sont obligatoires.";
            }
        }
        $annonce = $this->model->read($id);
        if (!$annonce) {
            header("Location: /Altiris/annonces");
            exit;
        }
        require_once dirname(__DIR__) . '/views/annonces/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $annonce = $this->model->read($id);
            if ($annonce && isset($annonce['image']) && $annonce['image'] && file_exists(dirname(__DIR__, 2) . '/' . $annonce['image'])) {
                unlink(dirname(__DIR__, 2) . '/' . $annonce['image']);
            }
            $this->model->delete($id);
        }
        header("Location: /Altiris/annonces");
        exit;
    }
}
?>