<?php
require_once dirname(__DIR__) . '/models/ContactEnt.php';

class ContactEntController {
    private $model;

    public function __construct() {
        $this->model = new ContactEnt();
    }

    public function index() {
        return $this->model->getAll();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return null;
        }

        // Débogage des données reçues
        error_log("Données POST reçues : " . json_encode($_POST));

        // Validation des données
        $required = ['mail'];
        foreach ($required as $field) {
            if (empty(trim($_POST[$field] ?? ''))) {
                return "Le champ " . ucfirst($field) . " est obligatoire";
            }
        }

        // Validation email
        if (!filter_var($_POST['mail'] ?? '', FILTER_VALIDATE_EMAIL)) {
            return "Format d'email invalide";
        }

        // Vérifier si l'email existe déjà
        if ($this->model->emailExists($_POST['mail'] ?? '')) {
            return "Cet email est déjà utilisé";
        }

        // Préparation des données
        $data = [
            'adresse' => trim($_POST['adresse'] ?? ''),
            'lien_facebook' => trim($_POST['lien_facebook'] ?? ''),
            'mail' => trim($_POST['mail']),
            'phonne' => trim($_POST['phonne'] ?? '') // Valeur vide si non défini
        ];

        // Création du contact
        $result = $this->model->create($data);
        if ($result === true) {
            $lastId = $this->model->getConnection()->lastInsertId();
            error_log("Nouveau contact créé avec ID : $lastId");
            return true;
        } else {
            error_log("Échec création contact : " . $result);
            return $result;
        }
    }

    public function edit($id) {
        if (!is_numeric($id) || $id <= 0) {
            header("Location: /Altiris/contact-entreprise");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
            $required = ['mail'];
            foreach ($required as $field) {
                if (empty(trim($_POST[$field] ?? ''))) {
                    return "Le champ " . ucfirst($field) . " est obligatoire";
                }
            }

            // Validation email
            if (!filter_var($_POST['mail'] ?? '', FILTER_VALIDATE_EMAIL)) {
                return "Format d'email invalide";
            }

            // Préparation des données
            $data = [
                'adresse' => trim($_POST['adresse'] ?? ''),
                'lien_facebook' => trim($_POST['lien_facebook'] ?? ''),
                'mail' => trim($_POST['mail']),
                'phonne' => trim($_POST['phonne'] ?? '') // Valeur vide si non défini
            ];

            $result = $this->model->update($id, $data);
            if ($result === true) {
                $_SESSION['success'] = "Contact mis à jour avec succès";
                header("Location: /Altiris/contact-entreprise");
                exit;
            } else {
                return $result;
            }
        }

        // Récupérer les données du contact pour affichage
        return $this->model->getById($id);
    }

    public function delete($id) {
        if (is_numeric($id) && $id > 0) {
            return $this->model->delete($id);
        }
        return false;
    }
}
?>