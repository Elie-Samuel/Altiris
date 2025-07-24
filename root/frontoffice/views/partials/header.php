<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALTIRYS - FrontOffice</title>
    <link href="/Altiris/Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Altiris/Assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Altiris/Assets/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="/Altiris/Assets/slick.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1183ed;
            --secondary-color: #ff8412;
            --dark-color: #1a1a2e;
            --light-color: #ccc;
            --lighter-color: #eee;
            --background-color: rgb(15, 7, 22);
            --text-color: #fff;
            --gradient: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            --box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
            --transition: all 0.3s ease;
            --navbar-bg: rgba(17, 17, 17, 0.95);
            --navbar-text: #ffffff;
            --navbar-hover: #FFCC00;
            --navbar-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            padding-top: 100px;
            scroll-behavior: smooth;
        }

        /* Navbar */
        .navbar-uf {
            background: var(--navbar-bg);
            box-shadow: var(--navbar-shadow);
            padding: 1rem 0;
            transition: var(--transition);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(17, 131, 237, 0.2);
        }

        .navbar-uf.scrolled {
            padding: 0.5rem 0;
            background: rgba(10, 10, 10, 0.98);
        }

        /* Logo */
        .navbar-uf .navbar-brand .logo-container {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            box-shadow: 0 0 15px rgba(17, 131, 237, 0.3);
        }

        .navbar-uf .navbar-brand .logo-container:hover {
            transform: scale(1.1);
            border-color: var(--secondary-color);
            box-shadow: 0 0 20px rgba(255, 132, 18, 0.4);
        }

        .navbar-uf .navbar-brand .logo-rounded {
            width: 80%;
            height: 80%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        /* Navigation */
        .navbar-uf .navbar-nav {
            gap: 0.5rem;
        }

        .navbar-uf .nav-link {
            color: var(--lighter-color);
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            margin: 0 0.25rem;
            border-radius: 30px;
            transition: var(--transition);
            position: relative;
        }

        .navbar-uf .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient);
            transition: var(--transition);
        }

        .navbar-uf .nav-link:hover,
        .navbar-uf .nav-link.active {
            color: var(--text-color);
        }

        .navbar-uf .nav-link:hover::before,
        .navbar-uf .nav-link.active::before {
            width: 100%;
        }

        .navbar-uf .nav-link i {
            margin-right: 5px;
            transition: var(--transition);
        }

        .navbar-uf .nav-link:hover i {
            color: var(--secondary-color);
        }

        /* Mobile */
        @media (max-width: 991.98px) {
            .navbar-uf .navbar-collapse {
                background: rgba(10, 10, 10, 0.98);
                padding: 1rem;
                border-radius: 0 0 10px 10px;
                margin-top: 10px;
            }
            
            .navbar-uf .nav-link {
                padding: 0.75rem 1rem;
                margin: 0.25rem 0;
            }
        }

        /* Toggler */
        .navbar-toggler {
            border: none;
            outline: none;
            padding: 0.5rem;
        }

        .navbar-toggler-icon {
            background-image: none;
            width: 24px;
            height: 2px;
            background-color: var(--text-color);
            position: relative;
            transition: var(--transition);
        }

        .navbar-toggler-icon::before,
        .navbar-toggler-icon::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 2px;
            background-color: var(--text-color);
            left: 0;
            transition: var(--transition);
        }

        .navbar-toggler-icon::before {
            top: -6px;
        }

        .navbar-toggler-icon::after {
            top: 6px;
        }

        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon {
            background-color: transparent;
        }

        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::before {
            transform: rotate(45deg);
            top: 0;
        }

        .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::after {
            transform: rotate(-45deg);
            top: 0;
        }

        /* Content */
        .content-offset {
            padding-top: 120px;
            transition: var(--transition);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link" href="/Altiris/root/?page=team">
                            <i class="fas fa-users me-1"></i> Notre Équipe
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
    
    <div class="pt-5 mt-3 content-offset fade-in">
        <!-- Contenu principal ici -->
    </div>

    <!-- Scripts -->
    <script src="/Altiris/Assets/jquery-3.6.0.min.js"></script>
    <script src="/Altiris/Assets/slick.min.js"></script>
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

        // Animation for elements when they come into view
        document.addEventListener('DOMContentLoaded', function() {
            const animateElements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            animateElements.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>