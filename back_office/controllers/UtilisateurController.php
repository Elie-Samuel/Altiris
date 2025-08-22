<?php
require_once dirname(__DIR__) . '/models/Utilisateur.php';

class UtilisateurController {
    private $model;

    public function __construct() {
        $this->model = new Utilisateur();
    }

    private function handleImageUpload($file, $existingImage = null) {
        if (empty($file['name'])) {
            return $existingImage;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5 Mo
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/Assets/Images/';
        $relativePath = 'Assets/Images/';

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Erreur lors de l'upload de l'image";
        }

        if ($file['size'] > $maxFileSize) {
            return "L'image dépasse la taille maximale de 5 Mo";
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            return "Format d'image non autorisé (jpg, jpeg, png, gif, webp uniquement)";
        }

        $filename = 'profil_' . time() . '_' . basename($file['name']);
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return "Échec du déplacement de l'image";
        }

        if ($existingImage && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $existingImage)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $existingImage);
        }

        return $relativePath . $filename;
    }

    public function index() {
        return $this->model->getAll();
    }

    public function search($term) {
        return $this->model->search($term);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        $required = ['nom_utilisateur', 'email', 'mot_de_passe', 'confirm_password', 'Types'];
        foreach ($required as $field) {
            if (empty(trim($_POST[$field]))) {
                $_SESSION['error'] = "Le champ " . ucfirst(str_replace('_', ' ', $field)) . " est obligatoire";
                return false;
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Format d'email invalide";
            return false;
        }

        if ($_POST['mot_de_passe'] !== $_POST['confirm_password']) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas";
            return false;
        }

        if (strlen($_POST['mot_de_passe']) < 6) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères";
            return false;
        }

        if ($this->model->emailExists($_POST['email'])) {
            $_SESSION['error'] = "Cet email est déjà utilisé";
            return false;
        }

        if ($this->model->usernameExists($_POST['nom_utilisateur'])) {
            $_SESSION['error'] = "Ce nom d'utilisateur est déjà utilisé";
            return false;
        }

        $imagePath = $this->handleImageUpload($_FILES['profil']);
        if (is_string($imagePath) && strpos($imagePath, 'Erreur') === 0) {
            $_SESSION['error'] = $imagePath;
            return false;
        }

        $data = [
            'nom_utilisateur' => trim($_POST['nom_utilisateur']),
            'email' => trim($_POST['email']),
            'mot_de_passe' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
            'profil' => $imagePath,
            'status' => 'Actif',
            'Types' => trim($_POST['Types'])
        ];

        $result = $this->model->create($data);
        if ($result === true) {
            $_SESSION['success'] = "Utilisateur ajouté avec succès";
            return true;
        } else {
            if ($imagePath && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $imagePath)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $imagePath);
            }
            $_SESSION['error'] = $result;
            return false;
        }
    }

    public function edit($id) {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = "ID d'utilisateur invalide";
            return false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['nom_utilisateur', 'email', 'Types'];
            foreach ($required as $field) {
                if (empty(trim($_POST[$field]))) {
                    $_SESSION['error'] = "Le champ " . ucfirst(str_replace('_', ' ', $field)) . " est obligatoire";
                    return false;
                }
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Format d'email invalide";
                return false;
            }

            if ($this->model->emailExists($_POST['email'], $id)) {
                $_SESSION['error'] = "Cet email est déjà utilisé";
                return false;
            }

            if ($this->model->usernameExists($_POST['nom_utilisateur'], $id)) {
                $_SESSION['error'] = "Ce nom d'utilisateur est déjà utilisé";
                return false;
            }

            if (!empty($_POST['mot_de_passe']) && $_POST['mot_de_passe'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas";
                return false;
            }

            if (!empty($_POST['mot_de_passe']) && strlen($_POST['mot_de_passe']) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères";
                return false;
            }

            $currentUser = $this->model->getById($id);
            $existingImage = $currentUser['profil'] ?? null;

            $imagePath = $this->handleImageUpload($_FILES['profil'], $existingImage);
            if (is_string($imagePath) && strpos($imagePath, 'Erreur') === 0) {
                $_SESSION['error'] = $imagePath;
                return false;
            }

            $data = [
                'nom_utilisateur' => trim($_POST['nom_utilisateur']),
                'email' => trim($_POST['email']),
                'profil' => $imagePath,
                'status' => $_POST['status'] ?? 'Actif',
                'Types' => trim($_POST['Types'])
            ];

            if (!empty($_POST['mot_de_passe'])) {
                $data['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            }

            $result = $this->model->update($id, $data);
            if ($result === true) {
                $_SESSION['success'] = "Utilisateur modifié avec succès";
            } else {
                $_SESSION['error'] = $result;
            }
            return $result;
        }

        return $this->model->getById($id);
    }

    public function changeStatus($id, $status) {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = "ID d'utilisateur invalide";
            return false;
        }
        $result = $this->model->changeStatus($id, $status);
        if ($result === true) {
            $_SESSION['success'] = "Statut de l'utilisateur modifié avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors du changement de statut";
        }
        return $result;
    }

    public function delete($id) {
        if (is_numeric($id) && $id > 0) {
            $user = $this->model->getById($id);
            if ($user && !empty($user['profil']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $user['profil'])) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $user['profil']);
            }
            $result = $this->model->delete($id);
            if ($result === true) {
                $_SESSION['success'] = "Utilisateur supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur";
            }
            return $result;
        }
        $_SESSION['error'] = "ID d'utilisateur invalide";
        return false;
    }
}
?>