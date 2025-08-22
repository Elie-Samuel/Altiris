<?php
// Altiris/back_office/login.php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to database
    $database = new Database();
    $conn = $database->getConnection();

    // Query to find user by email
    $query = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify user, password, and type
    if ($user && password_verify($password, $user['mot_de_passe']) && in_array($user['Types'], ['Super admin', 'Admin'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
        $_SESSION['types'] = $user['Types'];
        header("Location: /Altiris/accueil");
        exit;
    } else {
        $error = "Email, mot de passe incorrect ou type d'utilisateur non autorisé.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Altiris Back Office</title>
    <link rel="stylesheet" href="Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="back_office/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Ajout de la favicon -->
    <link rel="icon" type="image/x-icon" href="/Altiris/Assets/Images/favicon.ico">
</head>
<body class="login-body">
    <div class="login-container">
        <!-- Section gauche avec image et citation -->
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

        <!-- Section droite avec formulaire -->
        <div class="login-right">
            <div class="login-form-container">
                <div class="login-header">
                    <h1>Bienvenue sur Altiris</h1>
                    <p>Connectez-vous à votre espace d'administration pour gérer efficacement votre système.</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="login-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error); ?>
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

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Se souvenir de moi
                        </label>
                        <a href="/Altiris/forgot-password" class="forgot-password">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="login-btn">
                        <span>Se connecter</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
                <div class="login-footer">
                    <p>Vous n'avez pas de compte ? <a href="/Altiris/contact-admin">Contactez l'administrateur</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordEye = document.getElementById('password-eye');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordEye.classList.remove('fa-eye');
                passwordEye.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordEye.classList.remove('fa-eye-slash');
                passwordEye.classList.add('fa-eye');
            }
        }

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