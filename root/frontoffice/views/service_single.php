<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($service['titre'] ?? 'Détails du Service'); ?> - ALTIRYS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/home.css" rel="stylesheet">
    <style>
        /* Style masculin avec palette sombre et typographie audacieuse */
        body {
            background-color: #1a1a1a; /* Fond noir foncé */
            color: #e0e0e0; /* Texte gris clair pour contraste */
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #2d2d2d; /* Fond gris foncé pour le conteneur */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); /* Ombre pour un effet robuste */
        }
        h1 {
            color: #00b4d8; /* Bleu nuit métallique pour les titres */
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }
        .img-fluid {
            border: 2px solid #00b4d8; /* Bordure bleu métallique */
            border-radius: 5px;
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            transition: transform 0.3s ease; /* Animation au survol */
        }
        .img-fluid:hover {
            transform: scale(1.05); /* Zoom léger au survol */
        }
        p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #b0b0b0; /* Gris moyen pour le texte */
        }
        .btn-primary {
            background-color: #00b4d8; /* Bleu métallique pour le bouton */
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0096c7; /* Bleu plus foncé au survol */
        }
        /* Style pour le header et footer (à ajuster si nécessaire) */
        header, footer {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        a {
            color: #00b4d8;
            text-decoration: none;
        }
        a:hover {
            color: #0096c7;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1><?php echo htmlspecialchars($service['titre'] ?? 'Service'); ?></h1>
                <img src="<?php echo htmlspecialchars($service['image_path'] ?? '/Altiris/Assets/Images/logo_Altirys.jpg'); ?>" alt="<?php echo htmlspecialchars($service['titre'] ?? 'Service'); ?>" class="img-fluid mb-3">
                <p><?php echo nl2br(htmlspecialchars($service['texte'] ?? 'Aucune description disponible.')); ?></p>
                <a href="/Altiris/root/?page=home" class="btn btn-primary">Retour</a>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>