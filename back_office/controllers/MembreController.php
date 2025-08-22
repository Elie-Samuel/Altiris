<?php
require_once dirname(__DIR__) . '/models/Membre.php';

class MembreController {
    private $model;

    public function __construct() {
        $this->model = new Membre();
    }

    /**
     * Récupère la liste des membres avec filtres de recherche et de texte
     * @param string|null $search Terme de recherche
     * @param string|null $roleFilter Filtre texte pour le rôle
     * @return array Liste des membres filtrés
     */
    public function getMembres($search = null, $roleFilter = null) {
        // Récupérer tous les membres
        $membres = $this->model->getAll();

        // Filtrer par recherche si présente
        if ($search) {
            $search = strtolower(trim($search));
            $membres = array_filter($membres, function($membre) use ($search) {
                return stripos(strtolower($membre['prenom'] . ' ' . $membre['nom'] . ' ' . $membre['email'] . ' ' . $membre['role']), $search) !== false;
            });
        }

        // Filtrer par texte de rôle si présent (recherche partielle)
        if ($roleFilter) {
            $roleFilter = strtolower(trim($roleFilter));
            $membres = array_filter($membres, function($membre) use ($roleFilter) {
                return $roleFilter === '' || stripos(strtolower($membre['role']), $roleFilter) !== false;
            });
        }

        // Réindexer le tableau pour éviter les clés manquantes
        return array_values($membres);
    }

    /**
     * Méthode par défaut pour retourner tous les membres (sans filtres)
     * @return array Liste des membres
     */
    public function index() {
        return $this->getMembres(); // Utilise la nouvelle méthode getMembres sans filtres
    }

    /**
     * Crée un nouveau membre
     * @return mixed Résultat de la création (true, message d'erreur, ou null)
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        error_log("Données POST reçues : " . json_encode($_POST));
        error_log("Données FILES reçues : " . json_encode($_FILES));

        $required = ['prenom', 'nom', 'email'];
        foreach ($required as $field) {
            if (empty(trim($_POST[$field]))) {
                return "Le champ " . ucfirst($field) . " est obligatoire";
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            return "Format d'email invalide";
        }

        if ($this->model->emailExists($_POST['email'])) {
            return "Cet email est déjà utilisé";
        }

        $photo = null;
        $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['photo']['tmp_name'];
            if (file_exists($tmpFile)) {
                $mime = mime_content_type($tmpFile);
                if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                    return "Seuls les formats JPG et PNG sont acceptés";
                }
                if ($_FILES['photo']['size'] > 2097152) {
                    return "La photo ne doit pas dépasser 2MB";
                }
                $photoName = time() . '_' . basename($_FILES['photo']['name']);
                $targetFile = $uploadDir . $photoName;
                if (move_uploaded_file($tmpFile, $targetFile)) {
                    $photo = 'Assets/Images/' . $photoName;
                } else {
                    error_log("Échec du déplacement du fichier temporaire vers $targetFile");
                    return "Erreur lors du téléchargement de la photo.";
                }
            } else {
                error_log("Fichier temporaire $tmpFile introuvable");
                return "Fichier temporaire introuvable.";
            }
        }

        $data = [
            'prenom' => trim($_POST['prenom']),
            'nom' => trim($_POST['nom']),
            'email' => trim($_POST['email']),
            'role' => trim($_POST['role'] ?? ''), // Rôle devient un texte libre
            'Tel' => !empty($_POST['Tel']) ? trim($_POST['Tel']) : null,
            'competce_mbr' => !empty($_POST['competce_mbr']) ? trim($_POST['competce_mbr']) : null,
            'lien_facebook' => !empty($_POST['lien_facebook']) ? trim($_POST['lien_facebook']) : null
        ];

        $result = $this->model->create($data, $photo);
        if ($result === true) {
            $lastId = $this->model->getConnection()->lastInsertId();
            error_log("Nouveau membre créé avec ID : $lastId");
            return true;
        } else {
            error_log("Échec création membre : " . $result);
            if ($photo && file_exists($targetFile)) unlink($targetFile);
            return $result;
        }
    }

    /**
     * Modifie un membre existant
     * @param int $id ID du membre
     * @return mixed Résultat de la modification (true, message d'erreur, ou données du membre)
     */
    public function edit($id) {
        if (!is_numeric($id) || $id <= 0) {
            header("Location: /Altiris/membres");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $required = ['prenom', 'nom', 'email'];
            foreach ($required as $field) {
                if (empty(trim($_POST[$field]))) {
                    return "Le champ " . ucfirst($field) . " est obligatoire";
                }
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                return "Format d'email invalide";
            }

            $data = [
                'prenom' => trim($_POST['prenom']),
                'nom' => trim($_POST['nom']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role'] ?? ''), // Rôle devient un texte libre
                'Tel' => !empty($_POST['Tel']) ? trim($_POST['Tel']) : null,
                'competce_mbr' => !empty($_POST['competce_mbr']) ? trim($_POST['competce_mbr']) : null,
                'lien_facebook' => !empty($_POST['lien_facebook']) ? trim($_POST['lien_facebook']) : null
            ];

            $photo = null;
            $remove_photo = isset($_POST['remove_photo']);
            $uploadDir = dirname(__DIR__, 2) . '/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $tmpFile = $_FILES['photo']['tmp_name'];
                if (file_exists($tmpFile)) {
                    $mime = mime_content_type($tmpFile);
                    if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                        return "Seuls les formats JPG et PNG sont acceptés";
                    }
                    if ($_FILES['photo']['size'] > 2097152) {
                        return "La photo ne doit pas dépasser 2MB";
                    }
                    $photoName = time() . '_' . basename($_FILES['photo']['name']);
                    $targetFile = $uploadDir . $photoName;
                    if (move_uploaded_file($tmpFile, $targetFile)) {
                        $photo = 'Assets/Images/' . $photoName;
                    } else {
                        error_log("Échec du déplacement du fichier temporaire vers $targetFile");
                        return "Erreur lors du téléchargement de la photo.";
                    }
                } else {
                    error_log("Fichier temporaire $tmpFile introuvable");
                    return "Fichier temporaire introuvable.";
                }
            }

            $result = $this->model->update($id, $data, $photo, $remove_photo);
            if ($result === true) {
                return true;
            } else {
                if ($photo && file_exists($targetFile)) unlink($targetFile);
                return $result ?: "Erreur lors de la mise à jour.";
            }
        }

        $membre = $this->model->getById($id);
        if (!$membre) {
            header("Location: /Altiris/membres");
            exit;
        }
        return $membre;
    }

    /**
     * Supprime un membre
     * @param int $id ID du membre
     * @return bool Succès ou échec de la suppression
     */
    public function delete($id) {
        if (is_numeric($id) && $id > 0) {
            $membre = $this->model->getById($id);
            if ($membre && isset($membre['photo']) && $membre['photo']) {
                $imagePath = dirname(__DIR__, 2) . '/' . $membre['photo'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            return $this->model->delete($id);
        }
        return false;
    }
}
?>