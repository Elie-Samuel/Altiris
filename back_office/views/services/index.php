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
require_once dirname(__DIR__, 2) . '/controllers/ServiceController.php';
$controller = new ServiceController();
$services = $controller->index();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #004aad;
            --secondary: #ff6200;
            --dark: #0f172a;
            --light: #f8f9fa;
            --gray: #6c757d;
            --danger: #dc3545;
            --warning: #ff6200;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
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

        .btn-warning {
            background-color: var(--warning);
            color: var(--dark);
        }

        .btn-warning:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #1e293b;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: var(--primary);
            color: var(--light);
            padding: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 15px;
            color: var(--light);
        }

        .card-body p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .card-image {
            padding: 15px;
            text-align: center;
        }

        .card-image img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 6px;
            border: 2px solid var(--primary);
        }

        .card-image p {
            color: var(--gray);
            font-style: italic;
        }

        .card-actions {
            padding: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .no-services {
            text-align: center;
            color: var(--gray);
            font-size: 1.2rem;
            padding: 20px;
            background: #1e293b;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow);
        }

        @media (max-width: 768px) {
            .card-container {
                grid-template-columns: 1fr;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .card-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestion des Services</h2>
        <a href="/Altiris/services/ajouter" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Ajouter un service</a>
        <div class="card-container">
            <?php if (empty($services)): ?>
                <div class="no-services">
                    <p>Aucun service trouvé.</p>
                </div>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <div class="card">
                        <div class="card-header">
                            <?php if (isset($service['titre']) && $service['titre'] !== null): ?>
                                Titre: <?php echo htmlspecialchars($service['titre']); ?>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <p><?php echo isset($service['texte']) && $service['texte'] !== null ? htmlspecialchars(substr($service['texte'], 0, 50)) : '(Aucun texte)'; ?></p>
                        </div>
                        <div class="card-image">
                            <?php if (isset($service['image']) && $service['image'] !== null): ?>
                                <img src="/Altiris/<?php echo htmlspecialchars($service['image']); ?>" alt="Image du service">
                            <?php else: ?>
                                <p>(Aucune image)</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <a href="/Altiris/services/modifier/<?php echo isset($service['id']) ? $service['id'] : ''; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                            <a href="/Altiris/services/delete.php?id=<?php echo isset($service['id']) ? $service['id'] : ''; ?>" class="btn btn-danger delete-btn" data-id="<?php echo isset($service['id']) ? $service['id'] : ''; ?>"><i class="fas fa-trash"></i> Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Confirmation de suppression stylisée
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const serviceId = this.getAttribute('data-id');
                const confirmDelete = confirm(`Êtes-vous sûr de vouloir supprimer le service ID ${serviceId} ? Cette action est irréversible.`);
                if (confirmDelete) {
                    window.location.href = this.href;
                }
            });
        });

        // Animation légère pour les cartes au survol
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 6px 12px rgba(0, 0, 0, 0.2)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            });
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>