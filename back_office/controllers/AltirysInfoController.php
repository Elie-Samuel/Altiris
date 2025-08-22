<?php
require_once dirname(__DIR__) . '/models/AltirysInfo.php';

class AltirysInfoController {
    private $model;

    public function __construct() {
        $this->model = new AltirysInfo();
    }

    public function index() {
        $records = $this->model->readAll();
        return $records;
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $analyse = !empty($_POST['analyse']) ? trim($_POST['analyse']) : null;
            $mission = !empty($_POST['mission']) ? trim($_POST['mission']) : null;
            $vente_boost = !empty($_POST['vente_boost']) ? trim($_POST['vente_boost']) : null;
            $image = null;

            if ($analyse && $mission && $vente_boost) {
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
                } else {
                    $error = "Une image est obligatoire.";
                }

                if (!isset($error)) {
                    if ($this->model->create($analyse, $mission, $vente_boost, $image)) {
                        header("Location: /Altiris/information-altirys");
                        exit;
                    } else {
                        $error = "Erreur lors de la création.";
                        if ($image && file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }
                }
            } else {
                $error = "L'analyse, la mission et le boost de vente sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/altirys_info/create.php';
    }

    public function edit($id) {
        global $record, $error;
        if (!$id || !is_numeric($id) || $id <= 0) {
            header("Location: /Altiris/information-altirys");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $analyse = !empty($_POST['analyse']) ? trim($_POST['analyse']) : null;
            $mission = !empty($_POST['mission']) ? trim($_POST['mission']) : null;
            $vente_boost = !empty($_POST['vente_boost']) ? trim($_POST['vente_boost']) : null;
            $image = null;

            if ($analyse && $mission && $vente_boost) {
                $current_record = $this->model->read($id);
                $old_image = $current_record['image'] ?? null;

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
                } else {
                    $image = $old_image;
                }

                if (!isset($error)) {
                    if ($this->model->update($id, $analyse, $mission, $vente_boost, $image)) {
                        header("Location: /Altiris/information-altirys");
                        exit;
                    } else {
                        $error = "Erreur lors de la mise à jour.";
                        if ($image && $image !== $old_image && file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }
                }
            } else {
                $error = "L'analyse, la mission, le boost de vente et une image (si non modifiée) sont obligatoires.";
            }
        }
        $record = $this->model->read($id);
        if (!$record) {
            header("Location: /Altiris/information-altirys");
            exit;
        }
        require_once dirname(__DIR__) . '/views/altirys_info/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $record = $this->model->read($id);
            if ($record && isset($record['image']) && $record['image'] && file_exists(dirname(__DIR__, 2) . '/' . $record['image'])) {
                unlink(dirname(__DIR__, 2) . '/' . $record['image']);
            }
            $this->model->delete($id);
        }
        header("Location: /Altiris/information-altirys");
        exit;
    }
}
?>