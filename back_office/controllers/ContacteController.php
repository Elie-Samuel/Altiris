<?php
require_once dirname(__DIR__) . '/models/Contacte.php';
require_once dirname(__DIR__) . '/controllers/NotificationController.php';
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContacteController {
    private $model;
    private $notificationController;

    public function __construct() {
        $this->model = new Contacte();
        $this->notificationController = new NotificationController();
    }

    public function index() {
        $contacts = $this->model->getAll();
        error_log("index : " . count($contacts) . " contacts récupérés");
        return $contacts;
    }

    public function countNewMessages() {
        $count = $this->model->countNewMessages();
        error_log("countNewMessages : $count");
        return $count;
    }

    public function edit($id) {
        if (!is_numeric($id) || $id <= 0) {
            error_log("edit : ID invalide ($id)");
            $this->redirectWithError("/Altiris/rendez-vous", "ID invalide.");
        }

        $contact = $this->model->getById($id);
        if ($contact === false || !is_array($contact)) {
            error_log("edit : Contact introuvable pour ID $id");
            $this->redirectWithError("/Altiris/rendez-vous", "Contact introuvable.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $_POST['response'] ?? '';
            if (empty($response)) {
                error_log("edit : Réponse vide pour ID $id");
                return "Veuillez sélectionner une réponse.";
            }

            if ($this->model->updateResponse($id, $response)) {
                $this->notificationController->createSystemNotification(
                    "Réponse envoyée",
                    "Réponse envoyée à " . $contact['name'] . " pour sa demande de rendez-vous",
                    "success",
                    ['contact_id' => $id, 'response' => $response]
                );

                if (isset($contact['email']) && filter_var($contact['email'], FILTER_VALIDATE_EMAIL)) {
                    $smtpEmail = 'rafanomezantsoaherindrainyelie@gmail.com';
                    $smtpPassword = 'hggiurdwkqhuqvwl';
                    $to = $contact['email'];
                    $subject = "Réponse à votre demande de rendez-vous";

                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = $smtpEmail;
                        $mail->Password = $smtpPassword;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->CharSet = 'UTF-8';
                        $mail->SMTPDebug = 0;

                        $mail->setFrom($smtpEmail, 'Altiris');
                        $mail->addAddress($to);
                        $mail->addReplyTo($smtpEmail, 'Support Altiris');
                        $mail->Subject = $subject;

                        ob_start();
                        $responseVar = $response;
                        $contactVar = $contact;
                        $templatePath = dirname(__DIR__, 1) . '/views/email_template/email_template.php';
                        if (file_exists($templatePath)) {
                            include $templatePath;
                            $mail->Body = ob_get_clean();
                            $mail->isHTML(true);
                        } else {
                            $mail->Body = "Bonjour,\n\nVotre demande de rendez-vous a été " . ($response === 'accepté' ? 'acceptée' : 'refusée') . ".\n\nDétails : " . ($contact['text'] ?? 'Aucun détail') . ($contact['date_creation'] ?? '' ? "\n\nDate : " . $contact['date_creation'] : '') . "\n\nCordialement,\nL'équipe Altiris";
                            $mail->isHTML(false);
                        }

                        if (!$mail->send()) {
                            throw new Exception("Échec de l'envoi de l'email : " . $mail->ErrorInfo);
                        }

                        error_log("edit : Email envoyé à $to pour ID $id");
                        $this->redirectWithSuccess("/Altiris/rendez-vous", "Email envoyé avec succès à $to.");
                    } catch (Exception $e) {
                        $errorInfo = $mail->ErrorInfo ?? $e->getMessage();
                        error_log("edit : Erreur d'envoi d'email pour ID $id : $errorInfo");
                        $errorMessage = "Erreur d'envoi : $errorInfo";
                        if (strpos($errorInfo, '535') !== false) {
                            $errorMessage .= " (Authentification échouée - Vérifiez le mot de passe pour $smtpEmail).";
                        } elseif (strpos($errorInfo, '550') !== false) {
                            $errorMessage .= " (Destinataire invalide ou boîte pleine : $to).";
                        } elseif (strpos($errorInfo, 'connection timed out') !== false) {
                            $errorMessage .= " (Problème de connexion - Vérifiez le port 587 ou le pare-feu).";
                        }
                        $this->redirectWithError("/Altiris/rendez-vous", $errorMessage);
                    }
                } else {
                    error_log("edit : Email invalide pour ID $id : " . print_r($contact, true));
                    $this->redirectWithError("/Altiris/rendez-vous", "Aucune adresse email valide trouvée.");
                }
            } else {
                error_log("edit : Échec mise à jour réponse pour ID $id");
                $this->redirectWithError("/Altiris/rendez-vous", "Échec de la mise à jour de la réponse.");
            }
        }

        return $contact;
    }

    private function redirectWithSuccess($location, $message) {
        if (headers_sent()) {
            echo "<script>alert('Succès : " . htmlspecialchars($message) . "'); window.location.href='$location';</script>";
            exit;
        }
        header("Location: $location?success=1&message=" . urlencode($message));
        exit;
    }

    private function redirectWithError($location, $message) {
        if (headers_sent()) {
            echo "<script>alert('Erreur : " . htmlspecialchars($message) . "'); window.location.href='$location';</script>";
            exit;
        }
        header("Location: $location?error=" . urlencode($message));
        exit;
    }
}
?>