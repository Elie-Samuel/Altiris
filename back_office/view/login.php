<!-- <?php
// back_office/view/login.php
require_once(__DIR__ . '/../components/header.php');
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Connexion</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/Altiris/back_office/controllers/LoginController.php?action=login">
                        <div class="form-group">
                            <label for="nom_utilisateur">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" required>
                        </div>
                        <div class="form-group">
                            <label for="mot_de_passe">Mot de passe</label>
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once(__DIR__ . '/../components/footer.php');
?> -->