<?php
// Page de détail d'un service
$pageTitle = 'Détail Service - ALTIRYS';

// Récupérer l'ID du service
$serviceId = $_GET['id'] ?? 0;

// Simuler la récupération des données (à remplacer par une vraie requête DB)
$service = [
    'id' => $serviceId,
    'titre' => 'Service détaillé',
    'texte' => 'Description complète du service...',
    'image_path' => '/Altiris/Assets/Images/logo_Altirys.jpg',
    'features' => [
        'Développement sur mesure',
        'Support technique 24/7',
        'Maintenance incluse',
        'Formation utilisateur'
    ]
];

require_once __DIR__ . '/partials/header.php';
?>

<section class="service-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="service-detail">
                    <header class="service-header">
                        <h1 class="service-title"><?= htmlspecialchars($service['titre']) ?></h1>
                    </header>
                    
                    <div class="service-image">
                        <img src="<?= htmlspecialchars($service['image_path']) ?>" 
                             alt="<?= htmlspecialchars($service['titre']) ?>"
                             class="img-fluid rounded">
                    </div>
                    
                    <div class="service-content">
                        <p><?= nl2br(htmlspecialchars($service['texte'])) ?></p>
                        
                        <h3>Fonctionnalités incluses :</h3>
                        <ul class="service-features">
                            <?php foreach ($service['features'] as $feature): ?>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <?= htmlspecialchars($feature) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <footer class="service-footer">
                        <div class="service-actions">
                            <a href="/Altiris/contact" class="btn btn-primary">
                                <i class="fas fa-envelope"></i> Demander un devis
                            </a>
                            <a href="/Altiris/home" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Retour à l'accueil
                            </a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.service-detail-section {
    padding: 100px 0;
    background-color: var(--bg-primary);
}

.service-detail {
    background: var(--card-bg);
    border-radius: 15px;
    padding: 3rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.service-header {
    margin-bottom: 2rem;
    text-align: center;
}

.service-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.3;
}

.service-image {
    margin-bottom: 2rem;
}

.service-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.service-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
    margin-bottom: 2rem;
}

.service-content h3 {
    color: var(--text-primary);
    margin: 2rem 0 1rem 0;
}

.service-features {
    list-style: none;
    padding: 0;
}

.service-features li {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.8rem;
    color: var(--text-primary);
}

.service-features i {
    color: var(--accent-color);
    font-size: 1.2rem;
}

.service-footer {
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.service-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
    border: 2px solid var(--primary-color);
}

.btn-primary:hover {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
    color: white;
}

.btn-outline-secondary {
    border: 2px solid var(--text-secondary);
    color: var(--text-secondary);
    background: transparent;
}

.btn-outline-secondary:hover {
    background: var(--text-secondary);
    color: white;
}

@media (max-width: 768px) {
    .service-detail {
        padding: 2rem;
    }
    
    .service-title {
        font-size: 2rem;
    }
    
    .service-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<?php require_once __DIR__ . '/partials/footer.php'; ?>