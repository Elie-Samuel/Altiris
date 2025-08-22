<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /Altiris/login.php");
    exit;
}
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/AltirysInfoController.php';
$controller = new AltirysInfoController();
$errors = $controller->create(); // Supposons que create() renvoie un tableau d'erreurs
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Information Altirys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #004aad;
            --secondary: #ff6200;
            --dark: #0f172a;
            --light: #f8f9fa;
            --gray: #6c757d;
            --danger: #dc3545;
            --success: #28a745;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            background: var(--dark);
            color: var(--light);
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            color: var(--light);
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary);
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--dark);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow);
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 2px solid var(--primary);
            background-color: #1e293b;
            color: var(--light);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(255, 98, 0, 0.2);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 5px;
            display: none;
        }

        .server-error {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        label {
            color: var(--light);
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            gap: 8px;
        }

        .btn i {
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #003d8f;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-secondary {
            background-color: var(--gray);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .alert-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }

            .btn-container {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Ajouter une Information Altirys</h2>
        
        <?php if (!empty($errors) && is_array($errors)): ?>
            <?php foreach ($errors as $field => $error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="createForm">
            <div class="mb-3">
                <label for="analyse">Analyse</label>
                <textarea class="form-control" id="analyse" name="analyse" rows="5" required><?php echo isset($_POST['analyse']) ? htmlspecialchars($_POST['analyse']) : ''; ?></textarea>
                <div class="invalid-feedback" id="analyse-error">Veuillez entrer uniquement des lettres, des chiffres, des espaces ou des points.</div>
                <?php if (!empty($errors['analyse'])): ?>
                    <div class="server-error"><?php echo htmlspecialchars($errors['analyse']); ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="mission">Mission</label>
                <textarea class="form-control" id="mission" name="mission" rows="5" required><?php echo isset($_POST['mission']) ? htmlspecialchars($_POST['mission']) : ''; ?></textarea>
                <div class="invalid-feedback" id="mission-error">Veuillez entrer uniquement des lettres, des chiffres, des espaces ou des points.</div>
                <?php if (!empty($errors['mission'])): ?>
                    <div class="server-error"><?php echo htmlspecialchars($errors['mission']); ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="vente_boost">Boost de Vente</label>
                <textarea class="form-control" id="vente_boost" name="vente_boost" rows="5" required><?php echo isset($_POST['vente_boost']) ? htmlspecialchars($_POST['vente_boost']) : ''; ?></textarea>
                <div class="invalid-feedback" id="vente_boost-error">Veuillez entrer uniquement des lettres, des chiffres, des espaces ou des points.</div>
                <?php if (!empty($errors['vente_boost'])): ?>
                    <div class="server-error"><?php echo htmlspecialchars($errors['vente_boost']); ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="image">Image (JPG/PNG, max 5 Mo)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/jpg,image/png" required>
                <div class="invalid-feedback" id="image-error">Veuillez sélectionner une image JPG ou PNG de 5 Mo maximum.</div>
                <?php if (!empty($errors['image'])): ?>
                    <div class="server-error"><?php echo htmlspecialchars($errors['image']); ?></div>
                <?php endif; ?>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Créer</button>
                <a href="/Altiris/information-altirys" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
        </form>
    </div>

    <script>
        // Filtrer les caractères non autorisés en temps réel pour les champs texte
        const textInputs = ['analyse', 'mission', 'vente_boost'];
        textInputs.forEach(id => {
            const input = document.getElementById(id);
            input.addEventListener('input', function() {
                // Remplacer tout caractère non autorisé par une chaîne vide
                this.value = this.value.replace(/[^a-zA-Z0-9\s\p{L}.]/gu, '');
                // Supprimer la classe is-invalid si le champ est valide
                if (/^[a-zA-Z0-9\s\p{L}.]*$/u.test(this.value)) {
                    this.classList.remove('is-invalid');
                    document.getElementById(`${id}-error`).style.display = 'none';
                }
            });
        });

        // Validation du champ image (format et taille)
        document.getElementById('image').addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024; // 5 Mo en octets
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

            if (file) {
                if (!allowedTypes.includes(file.type) || file.size > maxSize) {
                    this.classList.add('is-invalid');
                    document.getElementById('image-error').style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    document.getElementById('image-error').style.display = 'none';
                }
            }
        });

        // Validation lors de la soumission du formulaire
        document.getElementById('createForm').addEventListener('submit', function(e) {
            const analyse = document.getElementById('analyse').value;
            const mission = document.getElementById('mission').value;
            const venteBoost = document.getElementById('vente_boost').value;
            const image = document.getElementById('image').files[0];
            const letterNumberPattern = /^[a-zA-Z0-9\s\p{L}.]*$/u; // Lettres, chiffres, espaces, caractères accentués, points uniquement
            const maxSize = 5 * 1024 * 1024; // 5 Mo en octets
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

            let isValid = true;

            // Validation du champ analyse
            if (!letterNumberPattern.test(analyse)) {
                document.getElementById('analyse').classList.add('is-invalid');
                document.getElementById('analyse-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('analyse').classList.remove('is-invalid');
                document.getElementById('analyse-error').style.display = 'none';
            }

            // Validation du champ mission
            if (!letterNumberPattern.test(mission)) {
                document.getElementById('mission').classList.add('is-invalid');
                document.getElementById('mission-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('mission').classList.remove('is-invalid');
                document.getElementById('mission-error').style.display = 'none';
            }

            // Validation du champ vente_boost
            if (!letterNumberPattern.test(venteBoost)) {
                document.getElementById('vente_boost').classList.add('is-invalid');
                document.getElementById('vente_boost-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('vente_boost').classList.remove('is-invalid');
                document.getElementById('vente_boost-error').style.display = 'none';
            }

            // Validation du champ image
            if (image) {
                if (!allowedTypes.includes(image.type) || image.size > maxSize) {
                    document.getElementById('image').classList.add('is-invalid');
                    document.getElementById('image-error').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('image').classList.remove('is-invalid');
                    document.getElementById('image-error').style.display = 'none';
                }
            } else {
                document.getElementById('image').classList.add('is-invalid');
                document.getElementById('image-error').style.display = 'block';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Empêche la soumission si la validation échoue
            }
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>