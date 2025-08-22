<?php
// D:\wamp64\www\Altiris\root\frontoffice\send_email.php
header('Content-Type: application/json');

// 1. Chargement des dépendances
require __DIR__.'/../../vendor/autoload.php';

// 2. Configuration SMTP (identique à votre exemple fonctionnel)
const SMTP_CONFIG = [
    'host' => 'smtp.gmail.com',
    'username' => 'rafanomezantsoaherindrainyelie@gmail.com',
    'password' => 'hggiurdwkqhuqvwl',
    'port' => 587,
    'encryption' => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS,
    'from_email' => 'rafanomezantsoaherindrainyelie@gmail.com',
    'from_name' => 'Altiris'
];

// 3. Vérification méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// 4. Validation des données
$required_fields = ['member_email', 'member_name', 'sender_name', 'sender_email', 'subject', 'message'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field] ?? '')) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Le champ $field est requis"]);
        exit;
    }
}

// 5. Nettoyage des données
$data = [
    'member_email' => filter_var($_POST['member_email'], FILTER_SANITIZE_EMAIL),
    'member_name' => htmlspecialchars($_POST['member_name'], ENT_QUOTES, 'UTF-8'),
    'sender_name' => htmlspecialchars($_POST['sender_name'], ENT_QUOTES, 'UTF-8'),
    'sender_email' => filter_var($_POST['sender_email'], FILTER_SANITIZE_EMAIL),
    'subject' => htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8'),
    'message' => nl2br(htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8'))
];

// 6. Envoi d'email avec gestion d'erreur détaillée
try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    // Configuration SMTP (identique à votre exemple)
    $mail->isSMTP();
    $mail->Host = SMTP_CONFIG['host'];
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_CONFIG['username'];
    $mail->Password = SMTP_CONFIG['password'];
    $mail->SMTPSecure = SMTP_CONFIG['encryption'];
    $mail->Port = SMTP_CONFIG['port'];
    $mail->CharSet = 'UTF-8';
    
    // Expéditeur/Destinataire
    $mail->setFrom(SMTP_CONFIG['from_email'], SMTP_CONFIG['from_name']);
    $mail->addAddress($data['member_email'], $data['member_name']);
    $mail->addReplyTo($data['sender_email'], $data['sender_name']);
    
    // Contenu de l'email
    $mail->Subject = $data['subject'];
    
    // Template HTML (version simplifiée de votre exemple)
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?= $data['subject'] ?></title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .header { background: #1183ed; color: white; padding: 20px; }
            .content { padding: 20px; }
            .footer { margin-top: 20px; font-size: 0.8em; color: #666; }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Nouveau message de <?= $data['sender_name'] ?></h2>
        </div>
        <div class="content">
            <?= $data['message'] ?>
        </div>
        <div class="footer">
            <p>Cet email vous a été envoyé via le système de contact Altiris</p>
        </div>
    </body>
    </html>
    <?php
    $mail->Body = ob_get_clean();
    $mail->AltBody = strip_tags($data['message']);
    
    // Envoi et réponse
    $mail->send();
    echo json_encode([
        'success' => true,
        'message' => 'Message envoyé avec succès à '.$data['member_name']
    ]);

} catch (Exception $e) {
    // Gestion d'erreur détaillée comme dans votre exemple
    $errorMessage = "Erreur d'envoi : ".$e->getMessage();
    
    if (strpos($e->getMessage(), '535') !== false) {
        $errorMessage = "Erreur d'authentification SMTP";
    } elseif (strpos($e->getMessage(), '550') !== false) {
        $errorMessage = "Destinataire invalide";
    }
    
    error_log("Erreur send_email: ".$errorMessage);
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $errorMessage
    ]);
}