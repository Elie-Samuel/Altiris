<?php
require_once dirname(__DIR__) . '/models/BlogPost.php';

class BlogPostController {
    private $model;

    public function __construct() {
        $this->model = new BlogPost();
    }

    public function index() {
        $posts = $this->model->readAll();
        return $posts;
    }

    public function create() {
        global $error;
        $categories = ['technologie', 'web', 'application', 'jeux', 'design'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = !empty($_POST['title']) ? trim($_POST['title']) : null;
            $content = !empty($_POST['content']) ? trim($_POST['content']) : null;
            $excerpt = !empty($_POST['excerpt']) ? trim($_POST['excerpt']) : null;
            $category = !empty($_POST['category']) ? trim($_POST['category']) : null;
            $published = isset($_POST['published']) ? 1 : 0;
            $image_path = null;

            if ($title && $content && $excerpt && $category && in_array($category, $categories)) {
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
                        $image_path = 'Assets/Images/' . $file_name;
                    }
                } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $error = "Erreur lors du téléchargement de l'image.";
                }

                if (!isset($error)) {
                    if ($this->model->create($title, $content, $excerpt, $category, $published, $image_path)) {
                        header("Location: /Altiris/article-blog");
                        exit;
                    } else {
                        $error = "Erreur lors de la création.";
                        if ($image_path && file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }
                }
            } else {
                $error = "Le titre, le contenu, l'extrait et une catégorie valide sont obligatoires.";
            }
        }
        require_once dirname(__DIR__) . '/views/blog_posts/create.php';
    }

    public function edit($id) {
        global $post, $error;
        $categories = ['technologie', 'web', 'application', 'jeux', 'design'];
        if (!$id || !is_numeric($id) || $id <= 0) {
            header("Location: /Altiris/article-blog");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = !empty($_POST['title']) ? trim($_POST['title']) : null;
            $content = !empty($_POST['content']) ? trim($_POST['content']) : null;
            $excerpt = !empty($_POST['excerpt']) ? trim($_POST['excerpt']) : null;
            $category = !empty($_POST['category']) ? trim($_POST['category']) : null;
            $published = isset($_POST['published']) ? 1 : 0;
            $image_path = null;

            if ($title && $content && $excerpt && $category && in_array($category, $categories)) {
                $current_post = $this->model->read($id);
                $old_image = $current_post['image_path'] ?? null;

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
                        $image_path = 'Assets/Images/' . $file_name;
                        if ($old_image && file_exists(dirname(__DIR__, 2) . '/' . $old_image)) {
                            unlink(dirname(__DIR__, 2) . '/' . $old_image);
                        }
                    }
                } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $error = "Erreur lors du téléchargement de l'image.";
                } else {
                    $image_path = $old_image;
                }

                if (!isset($error)) {
                    if ($this->model->update($id, $title, $content, $excerpt, $category, $published, $image_path)) {
                        header("Location: /Altiris/article-blog");
                        exit;
                    } else {
                        $error = "Erreur lors de la mise à jour.";
                        if ($image_path && $image_path !== $old_image && file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }
                }
            } else {
                $error = "Le titre, le contenu, l'extrait et une catégorie valide sont obligatoires.";
            }
        }
        $post = $this->model->read($id);
        if (!$post) {
            header("Location: /Altiris/article-blog");
            exit;
        }
        require_once dirname(__DIR__) . '/views/blog_posts/edit.php';
    }

    public function delete($id) {
        if ($id && is_numeric($id) && $id > 0) {
            $post = $this->model->read($id);
            if ($post && isset($post['image_path']) && $post['image_path'] && file_exists(dirname(__DIR__, 2) . '/' . $post['image_path'])) {
                unlink(dirname(__DIR__, 2) . '/' . $post['image_path']);
            }
            $this->model->delete($id);
        }
        header("Location: /Altiris/article-blog");
        exit;
    }
}
?>