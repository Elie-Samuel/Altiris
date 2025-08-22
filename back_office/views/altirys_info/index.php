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
$records = $controller->index();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Informations Altirys</title>
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

        .btn-container {
            text-align: center;
            margin-bottom: 2rem;
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
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning:hover {
            background-color: #e55a00;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .list-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .records-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(700px, 1fr)); /* Augmentation de la taille */
            gap: 20px;
        }

        .record-item {
            background: var(--dark);
            border-radius: 10px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
            display: grid;
            grid-template-columns: 600px 1fr; /* Image à gauche, contenu à droite */
            gap: 20px;
            align-items: start;
        }

        .record-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: var(--primary);
        }

        .record-image {
            text-align: center;
        }

        .record-image img {
            max-width: 100%;
            max-height: 500px;
            border-radius: 6px;
            border: 2px solid var(--primary);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .record-image img:hover {
            transform: scale(1.05);
        }

        .record-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .record-analyse,
        .record-mission,
        .record-vente-boost {
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .record-analyse strong,
        .record-mission strong,
        .record-vente-boost strong {
            color: var(--secondary);
            font-weight: 600;
        }

        .record-created {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 10px;
            text-align: right;
        }

        .record-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 15px;
        }

        .record-actions .btn {
            flex: 1;
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .text-center {
            text-align: center;
            color: var(--gray);
            font-size: 1.1rem;
            padding: 2rem;
            grid-column: 1 / -1;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%; /* Corrigé de 200% à 100% */
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            overflow: auto;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            opacity: 1;
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: zoomIn 0.3s;
        }

        .modal-content img {
            width: auto;
            max-width: 100%;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
        }

        .close-modal:hover {
            color: var(--secondary);
            transform: rotate(90deg);
        }

        @keyframes zoomIn {
            from { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
            to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }

        @media (max-width: 768px) {
            .records-list {
                grid-template-columns: 1fr;
            }

            .record-item {
                grid-template-columns: 1fr; /* Image au-dessus sur mobile */
                text-align: center;
            }

            .record-image {
                margin-bottom: 15px;
            }

            .modal-content {
                max-width: 95%;
            }

            .close-modal {
                top: 10px;
                right: 20px;
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <h2>Gestion des Informations Altirys</h2>
    
    <div class="btn-container">
        <a href="/Altiris/information-altirys/ajouter" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter une information
        </a>
    </div>

    <div class="list-container">
        <?php if (empty($records)): ?>
            <p class="text-center">Aucune information disponible.</p>
        <?php else: ?>
            <ul class="records-list">
                <?php foreach ($records as $record): ?>
                    <li class="record-item">
                        <div class="record-image">
                            <?php if (isset($record['image']) && $record['image'] !== null): ?>
                                <img src="/Altiris/<?= htmlspecialchars($record['image']) ?>" 
                                     alt="Altirys information image" 
                                     onclick="openModal(this.src)">
                            <?php else: ?>
                                <span>(Aucune image)</span>
                            <?php endif; ?>
                        </div>
                        <div class="record-content">
                            <div class="record-analyse">
                                <strong>Analyse:</strong> <?= htmlspecialchars($record['analyse'] ?? '(Aucune analyse)') ?>
                            </div>
                            <div class="record-mission">
                                <strong>Mission:</strong> <?= htmlspecialchars($record['mission'] ?? '(Aucune mission)') ?>
                            </div>
                            <div class="record-vente-boost">
                                <strong>Boost de vente:</strong> <?= htmlspecialchars($record['vente_boost'] ?? '(Aucun boost)') ?>
                            </div>
                            <div class="record-created">
                                Créé: <?= htmlspecialchars($record['created_at'] ?? '(Inconnu)') ?>
                            </div>
                            <div class="record-actions">
                                <a href="/Altiris/information-altirys/modifier/<?= $record['id'] ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="/Altiris/information-altirys/suppprimer/<?= $record['id'] ?>" class="btn btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette information ?');">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Modal Image -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Image agrandie">
        </div>
    </div>

    <script>
    // Gestion modale image
    function openModal(src) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modalImg.src = src;
        modal.style.display = 'block';
        setTimeout(() => modal.classList.add('show'), 10);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Fermer modale avec ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // Empêcher la propagation du clic dans la modale
    document.querySelector('.modal-content').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Animation au chargement
    document.addEventListener('DOMContentLoaded', () => {
        const items = document.querySelectorAll('.record-item');
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.animation = `fadeInUp 0.5s ease forwards ${index * 0.1}s`;
        });

        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>