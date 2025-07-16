<?php
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

    // Verify user and password (plain text comparison)
    if ($user && $user['mot_de_passe'] === $password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
        $_SESSION['role'] = $user['role'];
        header("Location: views/annonces/index.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Altiris Back Office</title>
    <link rel="stylesheet" href="../Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <h2 class="text-center">Altiris Back Office</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>