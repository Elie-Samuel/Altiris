<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\ContactModel;
use Altiris\FrontOffice\Models\ContactPageSettingsModel; // New import
use PDO;

class ContactController {
    private $db;
    private $contactModel;
    private $contactPageSettingsModel; // New property

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->contactModel = new ContactModel($db);
        $this->contactPageSettingsModel = new ContactPageSettingsModel($db); // Initialize new model
        $this->startSession();
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400,
                'read_and_close'  => false
            ]);
        }
    }

    public function index() {
        try {
            $contactInfo = $this->contactModel->getContactInfo(); // From contact_ent
            $pageSettings = $this->contactPageSettingsModel->getSettings(); // From contact_page_settings

            $error = $_SESSION['contact_error'] ?? null;
            $success = $_SESSION['contact_success'] ?? false;
            $message = $_SESSION['contact_message'] ?? '';

            unset($_SESSION['contact_error'], $_SESSION['contact_success'], $_SESSION['contact_message']);

            // Pass all data to the view
            require __DIR__.'/../views/contact.php';

        } catch (PDOException $e) {
            error_log("ContactController Error: " . $e->getMessage());
            $this->renderErrorView();
        }
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->validateContactForm();

                $stmt = $this->db->prepare("INSERT INTO contacte
                    (email, tel, adresse, text, name, subject, date_creation)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())");

                $stmt->execute([
                    filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    $this->sanitizePhone($_POST['tel'] ?? ''),
                    htmlspecialchars($_POST['adresse'] ?? ''), // Assuming 'adresse' might be passed from form
                    htmlspecialchars($_POST['message']),
                    htmlspecialchars($_POST['name'] ?? ''),
                    htmlspecialchars($_POST['subject'] ?? '')
                ]);

                $_SESSION['contact_success'] = true;
                $_SESSION['contact_message'] = "Votre message a été envoyé avec succès!";

            } catch (\Exception $e) {
                error_log("Contact Submit Error: " . $e->getMessage());
                $_SESSION['contact_error'] = $e->getMessage();
            }

            header('Location: /Altiris/root/?page=contact');
            exit;
        }
    }

    private function validateContactForm() {
        if (empty($_POST['email'])) {
            throw new \Exception("L'email est obligatoire");
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email invalide");
        }

        if (empty($_POST['message'])) {
            throw new \Exception("Le message est obligatoire");
        }
    }

    private function sanitizePhone($phone) {
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    private function renderErrorView() {
        http_response_code(500);
        require __DIR__.'/../views/errors/500.php'; // Assuming you have an error view
        exit;
    }
}