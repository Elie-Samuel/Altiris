<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $meta['title'] ?></title>
    <meta name="description" content="<?= $meta['description'] ?>">
    
    <!-- CSS -->
    <link href="/Altiris/Assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Altiris/Assets/slick.css"/>
    
    <style>
        :root {
            --primary: #ffffff;  /* Texte blanc */
            --primary-dark: #e0e0e0;
            --bg-main: #000000;  /* Noir absolu */
            --bg-card: #000000;  /* Noir absolu */
            --text-light: #ffffff;
            --text-muted: #a0a0a0;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-light);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Suppression de toutes les bordures */
        header, footer, .member-slide {
            border: none !important;
        }

        /* Header intégré */
        header {
            background-color: var(--bg-main);
            padding: 1.5rem 0;
        }

        /* Main Content */
        .team-hero {
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 1rem;
            background-color: var(--bg-main);
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Member Card */
        .member-slide {
            display: flex !important;
            background: var(--bg-card);
            height: 500px;
            margin: 0 10px;
            position: relative;
        }

        /* Image Side */
        .member-image {
            flex: 0 0 45%;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .member-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.9) 100%);
        }

        /* Content Side */
        .member-content {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .member-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .member-title {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .member-bio {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 1.05rem;
        }

        .member-contact {
            margin-top: auto;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .contact-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--primary);
        }

        .contact-link {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .contact-link:hover {
            color: var(--primary-dark);
        }

        .btn-contact {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem 2rem;
            background: var(--primary);
            color: #000000;
            border-radius: 6px;
            font-weight: 600;
            transition: var(--transition);
            margin-top: 2rem;
            border: none;
            cursor: pointer;
            width: 100%;
            text-decoration: none;
        }

        .btn-contact:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Slider Navigation */
        .slider-nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .slider-btn {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .slider-btn:hover {
            background: var(--primary);
            color: #000000;
        }

        /* Footer */
        footer {
            background-color: var(--bg-main);
            padding: 2rem 0;
            margin-top: 3rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .member-slide {
                height: auto;
                flex-direction: column;
            }
            
            .member-image {
                height: 350px;
                flex: 0 0 auto;
            }
        }

        @media (max-width: 768px) {
            .member-content {
                padding: 2rem;
            }
            
            .member-name {
                font-size: 1.8rem;
            }
            
            .section-title h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__.'/partials/header.php'; ?>
    
    <main class="team-hero">
        <div class="section-title">
            <h1>Notre Équipe d'Experts</h1>
            <p>Des professionnels dévoués à votre réussite digitale</p>
        </div>
        
        <div class="team-slider">
            <?php foreach ($members as $member): ?>
            <div class="member-slide">
                <div class="member-image" 
                     style="background-image: url('<?= !empty($member['photo']) ? $base_url . htmlspecialchars($member['photo']) : $base_url . 'assets/images/default-profile.jpg' ?>')">
                </div>
                
                <div class="member-content">
                    <div class="member-title">UTILISATEUR</div>
                    <h2 class="member-name"><?= htmlspecialchars($member['prenom'] . ' ' . htmlspecialchars($member['nom'])) ?></h2>
                    
                    <div class="member-bio">
                        <?= !empty($member['competce_mbr']) ? htmlspecialchars($member['competce_mbr']) : '' ?>
                    </div>
                    
                    <div class="member-contact">
                        <?php if (!empty($member['email'])): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <a href="mailto:<?= htmlspecialchars($member['email']) ?>" class="contact-link">
                                <?= htmlspecialchars($member['email']) ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($member['Tel'])): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <a href="tel:<?= htmlspecialchars($member['Tel']) ?>" class="contact-link">
                                <?= htmlspecialchars($member['Tel']) ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($member['lien_facebook'])): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <a href="<?= htmlspecialchars($member['lien_facebook']) ?>" target="_blank" class="contact-link">
                                Profil Facebook
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?= $base_url ?>?page=rendez_vous" class="btn-contact">
                        <i class="fas fa-calendar-alt mr-2"></i> Contacter
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="slider-nav">
            <button class="slider-btn prev-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-btn next-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </main>

    <script src="/Altiris/Assets/jquery-3.6.0.min.js"></script>
    <script src="/Altiris/Assets/slick.min.js"></script>
    <script>
    $(document).ready(function(){
        $('.team-slider').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 400,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: false,
            fade: false
        });

        $('.prev-btn').click(function(){
            $('.team-slider').slick('slickPrev');
        });
        
        $('.next-btn').click(function(){
            $('.team-slider').slick('slickNext');
        });
    });
    </script>
</body>
</html>