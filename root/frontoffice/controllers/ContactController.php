<?php
namespace Altiris\FrontOffice\Controllers;

class ContactController {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
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
            $contactInfo = $this->db->query("SELECT * FROM contact_ent LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
            
            $error = $_SESSION['contact_error'] ?? null;
            $success = $_SESSION['contact_success'] ?? false;
            $message = $_SESSION['contact_message'] ?? '';
            
            unset($_SESSION['contact_error'], $_SESSION['contact_success'], $_SESSION['contact_message']);
            
            require __DIR__.'/../views/contact.php';
            
        } catch (\PDOException $e) {
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
                    htmlspecialchars($_POST['adresse'] ?? ''),
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
        require __DIR__.'/../views/errors/500.php';
        exit;
    }
}