<?php
namespace Altiris\FrontOffice\Controllers;

use PDO;
use Altiris\FrontOffice\Models\ContactModel;
use Altiris\FrontOffice\Models\ContactPageSettingsModel;

class ContactController {
    private $contactModel;
    private $contactPageSettingsModel;
    private $recaptchaSecret = '6LdaY6crAAAAAGFlkSqhCqqxDCFw7r-P7SBAHlS5'; 

    public function __construct(PDO $db) {
        $this->contactModel = new ContactModel($db);
        $this->contactPageSettingsModel = new ContactPageSettingsModel($db);
        $this->startSession();
    }

    private function startSession() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function index() {
        try {
            $viewData = [
                'contactInfo' => $this->contactModel->getContactInfo(),
                'pageSettings' => $this->contactPageSettingsModel->getSettings(),
                'error' => $_SESSION['contact_error'] ?? null,
                'success' => $_SESSION['contact_success'] ?? false,
                'message' => $_SESSION['contact_message'] ?? '',
                'formData' => $_SESSION['form_data'] ?? []
            ];

            unset($_SESSION['contact_error'], $_SESSION['contact_success'], 
                 $_SESSION['contact_message'], $_SESSION['form_data']);

            $this->renderView('contact', $viewData);

        } catch (\Exception $e) {
            error_log("ContactController index Error: " . $e->getMessage());
            $this->renderErrorView();
        }
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Altiris/contact');
            exit;
        }

        try {
            $this->validateForm($_POST);

            $this->contactModel->saveMessage([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'tel' => ($_POST['phone_prefix'] ?? '+33') . ' ' . ($_POST['phone_number'] ?? ''),
                'subject' => $_POST['subject'],
                'adresse' => $_POST['adresse'] ?? '',
                'message' => $_POST['message']
            ]);

            $_SESSION['contact_success'] = true;
            $_SESSION['contact_message'] = "Votre message a été envoyé avec succès !";

        } catch (\Exception $e) {
            $_SESSION['contact_error'] = $e->getMessage();
            $_SESSION['form_data'] = $_POST;
        }

        header('Location: /Altiris/contact');
        exit;
    }

    private function validateForm($data) {
        $errors = [];

        // Validation reCAPTCHA
        if (!isset($data['g-recaptcha-response']) || empty($data['g-recaptcha-response'])) {
            $errors[] = "La vérification reCAPTCHA est obligatoire";
        } else {
            $response = $data['g-recaptcha-response'];
            $remoteIp = $_SERVER['REMOTE_ADDR'];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $postData = http_build_query([
                'secret' => $this->recaptchaSecret,
                'response' => $response,
                'remoteip' => $remoteIp
            ]);

            // Utiliser curl pour la requête
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, value: false); // Désactivé pour tests locaux
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($ch);
            if ($result === false) {
                $curlError = curl_error($ch);
                error_log("cURL Error: " . $curlError);
                $errors[] = "Erreur de connexion à reCAPTCHA. Veuillez réessayer. (Détail : " . $curlError . ")";
            } else {
                $resultJson = json_decode($result);
                error_log("reCAPTCHA Request: secret=***, response=" . substr($response, 0, 10) . "... , remoteip=$remoteIp");
                error_log("reCAPTCHA Response: " . json_encode($resultJson));
                if (!$resultJson || !$resultJson->success) {
                    $errors[] = "Échec de la vérification reCAPTCHA. Veuillez réessayer. (Détail : " . json_encode($resultJson) . ")";
                }
            }
            curl_close($ch);
        }

        // Validation des champs requis
        $required = [
            'name' => "Le nom est obligatoire",
            'email' => "L'email est obligatoire",
            'subject' => "Le sujet est obligatoire",
            'message' => "Le message est obligatoire"
        ];

        foreach ($required as $field => $error) {
            if (empty(trim($data[$field] ?? ''))) {
                $errors[] = $error;
            }
        }

        // Validation email
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide";
        }

        // Validation téléphone
        if (!empty($data['phone_number'])) {
            $phonePrefix = $data['phone_prefix'] ?? '+33';
            $phoneNumber = trim($data['phone_number']);
            if (!preg_match('/^[0-9]{6,13}$/', $phoneNumber)) {
                $errors[] = "Le numéro de téléphone doit contenir entre 6 et 13 chiffres";
            }
        }

        // Validation longueur message
        if (!empty($data['message']) && strlen($data['message']) > 225) {
            $errors[] = "Le message ne doit pas dépasser 225 caractères";
        }

        if (!empty($errors)) {
            throw new \Exception(implode("<br>", $errors));
        }
    }

    private function renderView($view, $data = []) {
        extract($data);
        ob_start();
        require __DIR__."/../views/partials/header.php";
        require __DIR__."/../views/{$view}.php";
        require __DIR__."/../views/partials/footer.php";
        echo ob_get_clean();
    }

    private function renderErrorView() {
        http_response_code(500);
        require __DIR__.'/../views/errors/500.php';
        exit;
    }
}