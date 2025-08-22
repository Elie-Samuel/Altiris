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
require_once dirname(__DIR__, 2) . '/controllers/AnnonceController.php';
$controller = new AnnonceController();
$annonces = $controller->index();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Annonces - Altiris</title>
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
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: 200;
            position: relative;
            padding-bottom: 10px;
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

        .search-box {
            max-width: 500px;
            position: absolute;
            top: 160px;
            right: 100px;
            z-index: 10;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border-radius: 8px;
            border: 2px solid var(--primary);
            background-color: #1e293b;
            color: var(--light);
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(255, 98, 0, 0.2);
        }

        .search-box::before {
            content: '\f002';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            z-index: 1;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 10px;
            margin-top: 40px; /* Espace pour la barre de recherche en haut */
        }

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            color: var(--gray);
            font-size: 1.1rem;
            padding: 2rem;
        }

        .card-annonce {
            background: var(--dark);
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-annonce:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            border-color: var(--primary);
        }

        .card-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card-annonce:hover .card-image img {
            transform: scale(1.05);
        }

        .card-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 74, 173, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .card-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.3rem;
            margin-bottom: 8px;
            color: #ffffff;
            font-weight: normal;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-subtitle {
            font-size: 1rem;
            color: #ffffff;
            margin-bottom: 12px;
            font-weight: normal;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-text {
            color: #ffffff;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 15px;
            font-weight: normal;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .card-actions .btn {
            flex: 1;
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
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
            .cards-container {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .modal-content {
                max-width: 95%;
            }
            
            .close-modal {
                top: 10px;
                right: 20px;
                font-size: 1.8rem;
            }

            .search-box {
                max-width: 100%;
                position: relative;
                top: auto;
                right: auto;
                margin: 0 auto 2rem;
            }

            .cards-container {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Rechercher une annonce...">
    </div>

    <h2>Gestion des Annonces</h2>
    
    <div class="btn-container">
        <a href="/Altiris/annonces/ajouter" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter une annonce
        </a>
    </div>

    <?php if (empty($annonces)): ?>
        <p class="no-results">Aucune annonce disponible.</p>
    <?php else: ?>
    <div class="cards-container" id="cardsContainer">
        <?php foreach ($annonces as $annonce): ?>
        <div class="card-annonce" data-search="<?= strtolower(htmlspecialchars($annonce['titre'] . ' ' . $annonce['titre1'] . ' ' . $annonce['text'])) ?>">
            <div class="card-image" onclick="openModal(this.querySelector('img').src)">
                <?php if (!empty($annonce['image'])): ?>
                    <img src="/Altiris/<?= htmlspecialchars($annonce['image']) ?>" alt="Image annonce">
                <?php else: ?>
                    <img src="/Altiris/assets/images/no-image.png" alt="Pas d'image">
                <?php endif; ?>
            </div>
            <div class="card-content">
                <h3 class="card-title" title="<?= htmlspecialchars($annonce['titre']) ?>">
                    Titre: <?= htmlspecialchars($annonce['titre'] ?? '(Sans titre)') ?>
                </h3>
                <h4 class="card-subtitle" title="<?= htmlspecialchars($annonce['titre1']) ?>">
                    Sous titre: <?= htmlspecialchars($annonce['titre1'] ?? '(Sans titre sup.)') ?>
                </h4>
                <p class="card-text" title="<?= htmlspecialchars(strip_tags($annonce['text'])) ?>">
                    Texte: <?= htmlspecialchars(truncateText(strip_tags($annonce['text'] ?? ''), 100)) ?>
                </p>
                <div class="card-actions">
                    <a href="/Altiris/annonces/modifier/<?= $annonce['id'] ?>" class="btn btn-warning" title="Modifier">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="/Altiris/annonces/supprimer/<?= $annonce['id'] ?>" class="btn btn-danger" title="Supprimer" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Modal Image -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Image agrandie">
        </div>
    </div>

    <script>
    // Fonction helper pour la recherche
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Recherche avec debounce
    document.getElementById('searchInput').addEventListener('input', debounce(function() {
        const filter = this.value.trim().toLowerCase();
        const cards = document.querySelectorAll('.card-annonce');
        let hasResults = false;
        
        cards.forEach(card => {
            const text = card.getAttribute('data-search');
            const isMatch = text.includes(filter);
            card.style.display = isMatch ? 'flex' : 'none';
            if (isMatch) hasResults = true;
        });

        // Afficher message si aucun résultat
        const noResults = document.querySelector('.no-results') || 
                          document.createElement('p');
        if (!noResults.classList) {
            noResults.className = 'no-results';
            noResults.textContent = 'Aucun résultat trouvé';
            document.getElementById('cardsContainer').prepend(noResults);
        }
        
        noResults.style.display = hasResults ? 'none' : 'block';
    }, 300));

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
        const cards = document.querySelectorAll('.card-annonce');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.animation = `fadeInUp 0.5s ease forwards ${index * 0.1}s`;
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
<?php 
function truncateText($text, $length) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}
ob_end_flush(); 
?>