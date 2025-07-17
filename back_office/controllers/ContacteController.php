<?php
require_once dirname(__DIR__) . '/models/Contacte.php';
require_once dirname(__DIR__) . '/vendor/autoload.php'; // Chemin corrigé

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Activer les erreurs pour le débogage (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ContacteController {
    private $model;

    public function __construct() {
        $this->model = new Contacte();
    }

    public function index() {
        return $this->model->getAll();
    }

    public function edit($id) {
        if (!is_numeric($id) || $id <= 0) {
            header("Location: index.php");
            exit;
        }

        $contact = $this->model->getById($id);
        if ($contact === false || !is_array($contact)) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $_POST['response'] ?? '';
            if (empty($response)) {
                return "Veuillez sélectionner une réponse.";
            }

            if ($this->model->updateResponse($id, $response)) {
                if (isset($contact['email']) && filter_var($contact['email'], FILTER_VALIDATE_EMAIL)) {
                    $to = $contact['email'];
                    $subject = "Réponse à votre demande de rendez-vous";

                    ob_start(); // Capturer la sortie pour éviter les conflits avec header()
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'votre-email@gmail.com'; // Remplacez par votre email
                        $mail->Password = 'votre-mot-de-passe-app'; // Remplacez par votre mot de passe d'application
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->CharSet = 'UTF-8';
                        $mail->SMTPDebug = 2; // Débogage activé pour diagnostiquer

                        $mail->setFrom('no-reply@altiris.com', 'Altiris');
                        $mail->addAddress($to);
                        $mail->addReplyTo('no-reply@altiris.com', 'Support Altiris');

                        $mail->Subject = $subject;

                        ob_start();
                        $responseVar = $response;
                        $contactVar = $contact;
                        if (file_exists(dirname(__DIR__, 1) . '/views/email_template.php')) {
                            include dirname(__DIR__, 1) . '/views/email_template.php';
                        } else {
                            $mail->Body = "Bonjour,\n\nVotre demande de rendez-vous a été " . ($response === 'accepté' ? 'acceptée' : 'refusée') . ".\n\nDétails : " . ($contact['text'] ?? 'Aucun détail') . "\n\nCordialement,\nL'équipe Altiris";
                        }
                        $mail->Body = ob_get_clean();
                        $mail->isHTML(false);

                        $mail->send();
                        $output = ob_get_clean(); // Capturer la sortie de débogage
                        header("Location: index.php?success=1");
                        exit;
                    } catch (Exception $e) {
                        $output = ob_get_clean(); // Capturer la sortie avant de retourner l'erreur
                        error_log("Erreur d'envoi d'email pour ID $id : " . $mail->ErrorInfo);
                        return "Erreur d'envoi : " . $e->getMessage() . " (Détails SMTP : " . $mail->ErrorInfo . ")\n" . $output;
                    }
                } else {
                    error_log("Aucune adresse email valide pour ID $id : " . print_r($contact, true));
                    return "Aucune adresse email valide trouvée dans la base de données.";
                }
            }
        }

        return $contact;
    }
}
?>