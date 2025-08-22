<?php
require_once '../../controllers/UtilisateurController.php';
$controller = new UtilisateurController();
$error = null;

// Vérifier la validité de l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID d'utilisateur invalide.";
    header("Location: /Altiris/utilisateurs");
    exit;
}

// Traiter la soumission du formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->edit($_GET['id']);
    if ($result === true) {
        header("Location: /Altiris/utilisateurs");
        exit;
    } else {
        $error = $result;
        $utilisateur = $_POST;
        $utilisateur['profil'] = !empty($_FILES['profil']['name']) ? null : ($utilisateur['profil'] ?? null);
    }
}

// Récupérer les données de l'utilisateur
$utilisateur = $controller->edit($_GET['id']);
if (!$utilisateur) {
    $_SESSION['error'] = "Utilisateur non trouvé";
    header("Location: /Altiris/utilisateurs");
    exit;
}

// Inclure header.php après toute logique de redirection
require_once '../../components/header.php';
?>

<link rel="stylesheet" href="/Altiris/back_office/css/utilisateur.css">

<div class="page-header">
    <h2><i class="fas fa-user-edit"></i> Modifier un Utilisateur</h2>
    <a href="/Altiris/utilisateurs" class="btn btn-secondary btn-lg">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="form-container fade-in-up">
    <form method="POST" enctype="multipart/form-data" id="editUserForm">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nom_utilisateur" class="form-label">
                        <i class="fas fa-user"></i> Nom d'utilisateur *
                    </label>
                    <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" 
                           required maxlength="50" value="<?php echo htmlspecialchars($utilisateur['nom_utilisateur'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Adresse email *
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           required maxlength="100" value="<?php echo htmlspecialchars($utilisateur['email'] ?? ''); ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mot_de_passe" class="form-label">
                        <i class="fas fa-lock"></i> Nouveau mot de passe
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" 
                               minlength="6">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('mot_de_passe')">
                            <i class="fas fa-eye" id="eye-mot_de_passe"></i>
                        </button>
                    </div>
                    <small class="form-text">Laissez vide pour ne pas modifier</small>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock"></i> Confirmer le mot de passe
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                               minlength="6">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye" id="eye-confirm_password"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Types" class="form-label">
                        <i class="fas fa-user-tag"></i> Type d'utilisateur *
                    </label>
                    <select class="form-control" id="Types" name="Types" required>
                        <option value="">Sélectionner un type</option>
                        <option value="Super admin" <?php echo ($utilisateur['Types'] ?? '') === 'Super admin' ? 'selected' : ''; ?>>
                            Super admin
                        </option>
                        <option value="Admin" <?php echo ($utilisateur['Types'] ?? '') === 'Admin' ? 'selected' : ''; ?>>
                            Admin
                        </option>
                        <option value="Utilisateur" <?php echo ($utilisateur['Types'] ?? '') === 'Utilisateur' ? 'selected' : ''; ?>>
                            Utilisateur
                        </option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="fas fa-toggle-on"></i> Status *
                    </label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Actif" <?php echo ($utilisateur['status'] ?? 'Actif') === 'Actif' ? 'selected' : ''; ?>>
                            Actif
                        </option>
                        <option value="Inactif" <?php echo ($utilisateur['status'] ?? '') === 'Inactif' ? 'selected' : ''; ?>>
                            Inactif
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="profil" class="form-label">
                <i class="fas fa-image"></i> Photo de profil
            </label>
            <input type="file" class="form-control" id="profil" name="profil" 
                   accept="image/jpeg,image/png,image/gif,image/webp">
            <small class="form-text">Formats acceptés : JPG, PNG, GIF, WebP (Max: 5MB)</small>
            <?php if (!empty($utilisateur['profil'])): ?>
                <div class="mt-2">
                    <label>Image actuelle :</label><br>
                    <img src="/Altiris/<?php echo htmlspecialchars($utilisateur['profil']); ?>" alt="Image de profil" class="profile-img">
                </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <a href="/Altiris/utilisateurs" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Modifier l'utilisateur
            </button>
        </div>
    </form>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById('eye-' + fieldId);
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('mot_de_passe').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password && password !== confirmPassword) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return false;
    }
    
    if (password && password.length < 6) {
        e.preventDefault();
        alert('Le mot de passe doit contenir au moins 6 caractères.');
        return false;
    }
});
</script>