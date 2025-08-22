<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/connexion");
    exit;
}
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/MembreController.php';

$controller = new MembreController();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->create();
    if ($result === true) {
        $_SESSION['success'] = "Membre ajouté avec succès";
        header("Location: /Altiris/membres");
        exit;
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un membre - Altiris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #004aad;
            --secondary-color: #ff6200;
            --dark-bg: #0f172a;
            --light-text: #f9f9f9;
            --error-color: #dc3545;
            --success-color: #28a745;
        }

        .modal-container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: var(--dark-bg);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            padding: 2rem;
            border: 1px solid var(--primary-color);
        }

        h2 {
            color: var(--light-text);
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 0.5rem;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .alert-danger {
            background-color: var(--error-color);
            color: white;
        }

        .form-label {
            color: var(--light-text);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #334155;
            background-color: #1e293b;
            color: var(--light-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 74, 173, 0.25);
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .was-validated .form-control:invalid {
            border-color: var(--error-color);
        }

        .was-validated .form-control:invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #003d8f;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #475569;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #334155;
            transform: translateY(-2px);
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 10px;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .text-muted {
            color: #94a3b8 !important;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .modal-container {
                padding: 1.5rem;
                margin: 1rem;
            }
        }

        /* Animation pour le modal */
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-container {
            animation: modalFadeIn 0.3s ease-out;
        }
    </style>
</head>
<body>
    <div class="modal-container">
        <h2>Ajouter un membre</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" 
                               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" 
                               pattern="[A-Za-zÀ-ÿ\s\-']+" 
                               title="Seules les lettres, espaces, traits d'union et apostrophes sont autorisés"
                               required>
                        <div class="invalid-feedback">Veuillez renseigner un nom valide (lettres seulement)</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Prénoms</label>
                        <input type="text" name="prenom" class="form-control" 
                               value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" 
                               pattern="[A-Za-zÀ-ÿ\s\-']+" 
                               title="Seules les lettres, espaces, traits d'union et apostrophes sont autorisés"
                               required>
                        <div class="invalid-feedback">Veuillez renseigner un prénom valide (lettres seulement)</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                <div class="invalid-feedback">Veuillez renseigner un email valide</div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Texte</label>
                        <input type="text" name="role" class="form-control" 
                               value="<?= htmlspecialchars($_POST['role'] ?? '') ?>"
                               pattern="[A-Za-zÀ-ÿ\s\-']+" 
                               title="Seules les lettres, espaces, traits d'union et apostrophes sont autorisés">
                        <div class="invalid-feedback">Veuillez utiliser seulement des lettres</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="Tel" class="form-control" 
                               value="<?= htmlspecialchars($_POST['Tel'] ?? '') ?>" 
                               pattern="[0-9]+" 
                               title="Seuls les chiffres sont autorisés">
                        <div class="invalid-feedback">Veuillez renseigner un numéro valide (chiffres seulement)</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Compétences</label>
                <textarea name="competce_mbr" class="form-control" rows="3"><?= htmlspecialchars($_POST['competce_mbr'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Lien Facebook</label>
                <input type="url" name="lien_facebook" class="form-control" 
                       value="<?= htmlspecialchars($_POST['lien_facebook'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png">
                <small class="text-muted">Formats acceptés: JPG, PNG (max 2MB)</small>
                <div class="invalid-feedback">Veuillez sélectionner une photo valide</div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="/Altiris/membres" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

    <script>
    // Activation de la validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    // Animation des boutons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Contrôle de saisie pour les champs nom, prénom et texte
    document.querySelectorAll('input[name="nom"], input[name="prenom"], input[name="role"]').forEach(input => {
        input.addEventListener('keypress', function(e) {
            // Autorise seulement les lettres, espaces, apostrophes et traits d'union
            if (!/[A-Za-zÀ-ÿ\s\-\']/.test(e.key)) {
                e.preventDefault();
            }
        });
    });

    // Contrôle de saisie pour le champ téléphone
    document.querySelector('input[name="Tel"]').addEventListener('keypress', function(e) {
        // Autorise seulement les chiffres
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });

    // Contrôle de saisie pour le champ compétences
    document.querySelector('textarea[name="competce_mbr"]').addEventListener('keypress', function(e) {
        // Autorise seulement les lettres, espaces, apostrophes et traits d'union
        if (!/[A-Za-zÀ-ÿ\s\-\']/.test(e.key)) {
            e.preventDefault();
        }
    });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>