<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALTIRYS - FrontOffice</title>
    <link href="/Altiris/Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Altiris/Assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/style.css" rel="stylesheet">
    <style>
        :root {
            --navbar-bg: #000000;
            --navbar-text: #ffffff;
            --navbar-hover: #FFCC00;
            --navbar-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        @keyframes pulseWhite {
            0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
        }

        .navbar-uf {
            background: var(--navbar-bg);
            box-shadow: var(--navbar-shadow);
            padding: 0.8rem 1rem;
            transition: all 0.4s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .navbar-uf.scrolled {
            padding: 0.4rem 1rem;
            background: rgba(0, 0, 0, 0.96);
            backdrop-filter: blur(8px);
        }

        /* Logo rond */
        .navbar-uf .navbar-brand .logo-container {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid var(--navbar-text);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.5s ease;
            animation: pulseWhite 2s infinite;
        }

        .navbar-uf .navbar-brand .logo-container:hover {
            transform: scale(1.1);
            border-color: var(--navbar-hover);
        }

        .navbar-uf .navbar-brand .logo-container::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.6s ease;
        }

        .navbar-uf .navbar-brand .logo-container:hover::before {
            opacity: 1;
        }

        .navbar-uf .navbar-brand .logo-rounded {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.3s ease;
        }

        /* Navigation */
        .navbar-uf .navbar-nav {
            gap: 0.5rem;
        }

        .navbar-uf .nav-link {
            color: var(--navbar-text);
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            margin: 0 0.25rem;
            border-radius: 30px;
            transition: all 0.4s ease;
        }

        .navbar-uf .nav-link:hover,
        .navbar-uf .nav-link.active {
            color: var(--navbar-hover);
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }

        /* Mobile */
        @media (max-width: 991.98px) {
            .navbar-uf .navbar-collapse {
                background: rgba(0, 0, 0, 0.95);
                backdrop-filter: blur(10px);
                padding: 1rem;
                border-radius: 0 0 10px 10px;
            }
        }

        /* Débogage temporaire */
        .logo-container {
            outline: 1px solid red; /* Limites du conteneur */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-uf fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/Altiris/root/frontoffice/home">
                <div class="logo-container">
                    <img src="/Altiris/Assets/Images/logo_Altirys.png" alt="Logo ALTIRYS" class="logo-rounded">
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/Altiris/root/frontoffice/home">
                            <i class="fas fa-home me-1"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Altiris/root/frontoffice/personnel">
                            <i class="fas fa-users me-1"></i> Personnel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Altiris/root/frontoffice/apropos">
                            <i class="fas fa-info-circle me-1"></i> À propos
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="pt-5 mt-3 content-offset">
        <!-- Contenu principal ici -->
    </div>

    <script src="/Altiris/Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-uf');
            const content = document.querySelector('.content-offset');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                content.style.paddingTop = '90px';
            } else {
                navbar.classList.remove('scrolled');
                content.style.paddingTop = '120px';
            }
        });

        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            this.classList.toggle('active');
        });
    </script>
</body>
</html>