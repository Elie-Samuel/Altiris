<?php
// Altiris/back_office/change-password.php
session_start();
require_once '../config/db.php';

date_default_timezone_set('Africa/Nairobi'); // EAT (UTC+3)

$error = '';
$success = '';
$step = 'verify_code';
$code_verified = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_code'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $code = strtoupper(trim($_POST['code']));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Adresse e-mail invalide.";
        } elseif (strlen($email) > 100) {
            $error = "L'adresse e-mail est trop longue (maximum 100 caractères).";
        } elseif (!preg_match('/^[A-Z0-9]{6}$/', $code)) {
            $error = "Code de réinitialisation invalide.";
        } else {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT * FROM password_resets WHERE email = :email AND code = :code AND expires_at > NOW()";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':code', $code);
            if (!$stmt->execute()) {
                $error = "Erreur d'exécution de la requête : " . implode(", ", $stmt->errorInfo());
            } else {
                $reset = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($reset) {
                    $step = 'change_password';
                    $code_verified = true;
                    $_SESSION['reset_email'] = $email;
                } else {
                    $error = "Code de réinitialisation invalide ou expiré (Email: $email, Code: $code, NOW(): " . date('Y-m-d H:i:s') . ").";
                    $checkQuery = "SELECT code, expires_at FROM password_resets WHERE email = :email";
                    $checkStmt = $conn->prepare($checkQuery);
                    $checkStmt->bindParam(':email', $email);
                    $checkStmt->execute();
                    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        $error .= " Entrée trouvée : Code = {$row['code']}, Expires = {$row['expires_at']}.";
                    } else {
                        $error .= " Aucune entrée trouvée pour cet e-mail.";
                    }
                }
            }
        }
    } elseif (isset($_POST['change_password']) && isset($_SESSION['reset_email'])) {
        $email = $_SESSION['reset_email'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            if (strlen($newPassword) < 8) {
                $error = "Le mot de passe doit contenir au moins 8 caractères.";
            } else {
                $database = new Database();
                $conn = $database->getConnection();
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE utilisateurs SET mot_de_passe = :mot_de_passe WHERE email = :email";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':mot_de_passe', $hashedPassword);
                $updateStmt->bindParam(':email', $email);

                if ($updateStmt->execute()) {
                    $deleteQuery = "DELETE FROM password_resets WHERE email = :email";
                    $deleteStmt = $conn->prepare($deleteQuery);
                    $deleteStmt->bindParam(':email', $email);
                    $deleteStmt->execute();

                    unset($_SESSION['reset_email']);
                    $success = "Mot de passe changé avec succès. Vous serez redirigé vers la page de connexion dans 2 secondes.";
                    $step = 'success';
                    header("Refresh: 2; url=/Altiris/connexion");
                } else {
                    $error = "Erreur lors du changement de mot de passe.";
                }
            }
        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe - Altiris Back Office</title>
    <link rel="stylesheet" href="Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="back_office/css/changemdp.css">
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
                    <h1>Changer votre mot de passe</h1>
                    <p>
                        <?php
                        if ($step === 'verify_code') {
                            echo 'Entrez votre e-mail et le code reçu.';
                        } elseif ($step === 'change_password') {
                            echo 'Code vérifié avec succès. Définissez un nouveau mot de passe.';
                        } else {
                            echo 'Mot de passe mis à jour.';
                        }
                        ?>
                    </p>
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
                <?php elseif ($step === 'verify_code'): ?>
                    <form method="POST" class="login-form">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="admin@altiris.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="code">Code de réinitialisation</label>
                            <div class="input-wrapper">
                                <i class="fas fa-key"></i>
                                <input type="text" id="code" name="code" placeholder="Entrez le code reçu" required>
                            </div>
                        </div>
                        <button type="submit" name="verify_code" class="login-btn">
                            <span>Vérifier le code</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                    <div class="login-footer">
                        <p><a href="/Altiris/forgot-password">Renvoyer un code</a></p>
                        <p><a href="/Altiris/login">Retour à la connexion</a></p>
                    </div>
                <?php elseif ($step === 'change_password'): ?>
                    <form method="POST" class="login-form">
                        <div class="form-group">
                            <label for="new_password">Nouveau mot de passe</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="new_password" name="new_password" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmer le mot de passe</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                            </div>
                        </div>
                        <button type="submit" name="change_password" class="login-btn">
                            <span>Changer le mot de passe</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                    <div class="login-footer">
                        <p><a href="/Altiris/forgot-password">Renvoyer un code</a></p>
                        <p><a href="/Altiris/connexion">Retour à la connexion</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>