<?php
// Altiris/back_office/contact-admin.php
session_start();
require_once '../config/db.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Africa/Nairobi'); // EAT (UTC+3)

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');

    // Validation
    if (empty($name)) {
        $error = "Le nom est requis.";
    } elseif (strlen($name) > 100) {
        $error = "Le nom est trop long (maximum 100 caractères).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse e-mail invalide.";
    } elseif (strlen($email) > 100) {
        $error = "L'adresse e-mail est trop longue (maximum 100 caractères).";
    } elseif (empty($message)) {
        $error = "Le message est requis.";
    } elseif (strlen($message) > 1000) {
        $error = "Le message est trop long (maximum 1000 caractères).";
    } else {
        // Récupérer l'e-mail du Super Admin
        $database = new Database();
        $conn = $database->getConnection();
        $query = "SELECT email FROM utilisateurs WHERE Types = 'Super admin' LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $superAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($superAdmin) {
            // Paramètres SMTP
            $smtpEmail = 'rafanomezantsoaherindrainyelie@gmail.com';
            $smtpPassword = 'hggiurdwkqhuqvwl';
            $to = $superAdmin['email']; // E-mail du Super Admin
            $subject = "Demande de contact - Altiris";

            ob_start();
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

                $mail->setFrom($smtpEmail, 'Altiris');
                $mail->addAddress($to);
                $mail->addReplyTo($email, $name);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = '
                    <h2>Nouvelle demande de contact</h2>
                    <p><strong>Nom :</strong> ' . htmlspecialchars($name) . '</p>
                    <p><strong>Email :</strong> ' . htmlspecialchars($email) . '</p>
                    <p><strong>Message :</strong></p>
                    <p>' . nl2br(htmlspecialchars($message)) . '</p>
                    <p>Cette demande a été envoyée depuis le formulaire de contact Altiris.</p>
                    <p>Cordialement,<br>L\'équipe Altiris</p>
                ';
                $mail->AltBody = "Nouvelle demande de contact\n\nNom : $name\nEmail : $email\nMessage:\n$message\n";

                $mail->send();

                // Insérer dans la table contact_requests
                $insertQuery = "INSERT INTO contact_requests (name, email, message) VALUES (:name, :email, :message)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(':name', $name);
                $insertStmt->bindParam(':email', $email);
                $insertStmt->bindParam(':message', $message);

                if ($insertStmt->execute()) {
                    $success = "Votre message a été envoyé avec succès. Vous serez redirigé dans 2 secondes.";
                    header("Refresh: 2; url=/Altiris/connexion");
                } else {
                    $error = "Erreur lors de l'enregistrement de la demande : " . implode(", ", $insertStmt->errorInfo());
                }
            } catch (Exception $e) {
                $error = "Échec de l'envoi du message. Erreur : {$mail->ErrorInfo}";
            }
            ob_end_clean();
        } else {
            $error = "Aucun Super Admin n'est configuré dans le système.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez l'administrateur - Altiris Back Office</title>
    <link rel="stylesheet" href="Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="back_office/css/contactadmin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Ajout de la favicon -->
    <link rel="icon" type="image/x-icon" href="/Altiris/Assets/Images/favicon.ico">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-left">
            <div class="login-brand">
                <img src="Assets/Images/logo_Altirys.png" alt="Logo Altiris" class="logo-img">
                <span>Altiris</span>
            </div>
            <div class="login-quote">
                <blockquote>
                    "Simplifiez la gestion de votre entreprise avec tous les outils dont votre équipe a besoin."
                </blockquote>
                <cite>
                    <strong>Équipe Altiris</strong><br>
                    <span>Directeur de la Technologie Marketing</span>
                </cite>
            </div>
        </div>
        <div class="login-right">
            <div class="login-form-container">
                <div class="login-header">
                    <h1>Contactez l'administrateur</h1>
                    <p>Remplissez le formulaire pour demander un compte ou poser une question.</p>
                </div>
                <?php if ($error): ?>
                    <div class="login-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="login-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php else: ?>
                    <form method="POST" class="login-form">
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" id="name" name="name" placeholder="Votre nom" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="votre@email.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <div class="input-wrapper">
                                <i class="fas fa-comment"></i>
                                <textarea id="message" name="message" placeholder="Votre message" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="login-btn">
                            <span>Envoyer</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                    <div class="login-footer">
                        <p><a href="/Altiris/connexion">Retour à la connexion</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const loginRight = document.querySelector('.login-right');
            const loginLeft = document.querySelector('.login-left');
            
            setTimeout(() => {
                loginLeft.style.opacity = '1';
                loginLeft.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                loginRight.style.opacity = '1';
                loginRight.style.transform = 'translateX(0)';
            }, 300);
        });
    </script>
    <script src="../Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>