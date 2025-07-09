<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALTRIS - FrontOffice</title>
    <link href="/Altiris/Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Altiris/Assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/style.css" rel="stylesheet">
  
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-uf fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/Altiris/root/frontoffice/home">
                <img src="/Altiris/Assets/images/logo_Altirys.png" alt="Logo ALTRIS" class="logo-rounded" height="100" width="100">
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
                  <!--   <li class="nav-item">
                        <a class="nav-link" href="/Altiris/root/frontoffice/services">
                            <i class="fas fa-cogs me-1"></i> Services
                        </a>
                    </li> -->
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
    <div class="pt-5 mt-3"> <!-- Espace pour la navbar fixe -->

    <script>
    // Navbar Scroll Effect
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

    // Mobile Menu Animation
    document.querySelector('.navbar-toggler').addEventListener('click', function() {
        this.classList.toggle('active');
    });
</script>