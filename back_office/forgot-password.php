<?php
// Altiris/back_office/forgot-password.php
session_start();
require_once '../config/db.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('Africa/Nairobi'); // EAT (UTC+3)

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse e-mail invalide.";
    } elseif (strlen($email) > 100) {
        $error = "L'adresse e-mail est trop longue (maximum 100 caractères).";
    } else {
        $database = new Database();
        $conn = $database->getConnection();
        $query = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+4 hours')); // Expire dans 4 heures

            $insertQuery = "INSERT INTO password_resets (email, code, expires_at) 
                            VALUES (:email, :code, :expires_at)
                            ON DUPLICATE KEY UPDATE code = :code, expires_at = :expires_at";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':code', $code);
            $insertStmt->bindParam(':expires_at', $expiresAt);

            if ($insertStmt->execute()) {
                $checkQuery = "SELECT * FROM password_resets WHERE email = :email";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bindParam(':email', $email);
                $checkStmt->execute();
                $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $smtpEmail = 'rafanomezantsoaherindrainyelie@gmail.com';
                    $smtpPassword = 'hggiurdwkqhuqvwl';

                    $to = $email;
                    $subject = "Réinitialisation de votre mot de passe Altiris";

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
                        $mail->addReplyTo($smtpEmail, 'Support Altiris');

                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body = '
                            <h2>Réinitialisation de mot de passe</h2>
                            <p>Bonjour,</p>
                            <p>Vous avez demandé une réinitialisation de votre mot de passe. Utilisez le code suivant pour définir un nouveau mot de passe :</p>
                            <p><strong>' . htmlspecialchars($code) . '</strong></p>
                            <p>Ce code expire dans 4 heures. Rendez-vous sur la page de réinitialisation pour continuer.</p>
                            <p><a href="http://localhost/Altiris/change-password">Changer mon mot de passe</a></p>
                            <p>Si vous n\'avez pas demandé cette réinitialisation, veuillez contacter notre support.</p>
                            <p>Cordialement,<br>L\'équipe Altiris</p>
                        ';
                        $mail->AltBody = "Bonjour, votre code de réinitialisation est : " . $code . ". Rendez-vous sur http://localhost/Altiris/change-password pour définir un nouveau mot de passe. Ce code expire dans 4 heures.";

                        $mail->send();
                        $success = 'Un e-mail avec un code de réinitialisation a été envoyé à votre adresse. Code: ' . $code . ', Expires: ' . $expiresAt . ', Server Time: ' . date('Y-m-d H:i:s');
                        header("Location: /Altiris/change-password");
                        exit;
                    } catch (Exception $e) {
                        $error = "Échec de l'envoi de l'e-mail. Erreur : {$mail->ErrorInfo}";
                    }
                    ob_end_clean();
                } else {
                    $error = "Erreur : le code n'a pas été enregistré dans la base de données.";
                }
            } else {
                $error = "Échec de la génération du code de réinitialisation. Erreur : " . implode(", ", $insertStmt->errorInfo());
            }
        } else {
            $error = "Aucun compte n'est associé à cette adresse e-mail.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Altiris Back Office</title>
    <link rel="stylesheet" href="Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="back_office/css/forgotmdp.css">
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
                    <span>Directeur de la Technologie</span>
                </cite>
            </div>
        </div>
        <div class="login-right">
            <div class="login-form-container">
                <div class="login-header">
                    <h1>Réinitialisation du mot de passe</h1>
                    <p>Entrez votre adresse e-mail pour recevoir un code de réinitialisation.</p>
                </div>
                <?php if ($error): ?>
                    <div class="login-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="login-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="admin@altiris.com" required>
                        </div>
                    </div>
                    <button type="submit" class="login-btn">
                        <span>Envoyer le code</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
                <div class="login-footer">
                    <p><a href="/Altiris/connexion  ">Retour à la connexion</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="../Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>