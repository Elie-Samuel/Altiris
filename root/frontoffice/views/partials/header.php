<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') : 'ALTIRYS - Agence Digitale' ?></title>
    <meta name="description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : 'ALTIRYS - Votre partenaire digital de confiance pour le développement web, mobile, design et solutions numériques.' ?>">
    <meta name="keywords" content="développement web, application mobile, design, SEO, ALTIRYS, agence digitale">
    <meta name="author" content="ALTIRYS">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= isset($_SERVER['REQUEST_URI']) ? 'https://' . htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') : '' ?>">
    <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') : 'ALTIRYS' ?>">
    <meta property="og:description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : 'Votre partenaire digital de confiance' ?>">
    <meta property="og:image" content="/Altiris/Assets/Images/logo_Altirys.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= isset($_SERVER['REQUEST_URI']) ? 'https://' . htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') : '' ?>">
    <meta property="twitter:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') : 'ALTIRYS' ?>">
    <meta property="twitter:description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') : 'Votre partenaire digital de confiance' ?>">
    <meta property="twitter:image" content="/Altiris/Assets/Images/logo_Altirys.png">
    
    <link rel="icon" href="/Altiris/Assets/Images/logo_Altirys.png" type="image/x-icon">
    <link rel="canonical" href="<?= isset($_SERVER['REQUEST_URI']) ? 'https://' . htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') : '' ?>">
    
    <!-- Bootstrap CSS -->
    <link href="/Altiris/Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="/Altiris/Assets/fontawesome/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Slick Carousel -->
    <link rel="stylesheet" type="text/css" href="/Altiris/Assets/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/Altiris/Assets/slick-theme.css"/>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="/Altiris/root/frontoffice/css/global-theme.css"/>
    <link rel="stylesheet" type="text/css" href="/Altiris/root/frontoffice/css/header.css"/>
    <link href="/Altiris/root/frontoffice/css/home.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/blog.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/team.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/apropos.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/contact.css" rel="stylesheet">
    <link href="/Altiris/root/frontoffice/css/footer.css" rel="stylesheet">
</head>

<body>
    <!-- Theme Toggle Button -->
    <div class="theme-toggle-container">
        <button id="themeToggle" class="theme-toggle-btn" aria-label="Changer le thème">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-uf fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/Altiris/home">
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
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'home') ? 'active' : '' ?>" href="/Altiris/home">
                            <i class="fas fa-home me-1"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'team') ? 'active' : '' ?>" href="/Altiris/team">
                            <i class="fas fa-users me-1"></i> Notre Équipe
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'apropos') ? 'active' : '' ?>" href="/Altiris/apropos">
                            <i class="fas fa-info-circle me-1"></i> À propos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'contact') ? 'active' : '' ?>" href="/Altiris/contact">
                            <i class="fas fa-address-book me-1"></i> Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'blog') ? 'active' : '' ?>" href="/Altiris/blog">
                            <i class="fas fa-blog me-1"></i> Blog
                        </a>
                    </li>
                </ul>
                
                <!-- Search Area -->
                <div class="search-zone" id="searchZone">
                    <div class="position-relative">
                        <input type="text" class="search-input" id="memberSearch" placeholder="Rechercher un membre..." aria-label="Rechercher un membre">
                        <div class="search-results" id="searchResults"></div>
                    </div>
                </div>
                
                <!-- Dark Mode Toggle -->
                <button type="button" class="dark-mode-toggle" id="darkModeToggle" title="Basculer en mode sombre" aria-label="Basculer entre mode sombre et clair">
                    <i class="fas fa-moon" id="darkModeIcon"></i>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="pt-5 mt-3 content-offset fade-in">
        <!-- Content will be inserted here -->
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="/Altiris/Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Slick Carousel JS -->
    <script type="text/javascript" src="/Altiris/Assets/slick.min.js"></script>

    <script>
        // Gestion du thème - Version vanilla JavaScript (fonctionne sans connexion)
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const body = document.body;
            
            // Fonction pour appliquer le thème
            function applyTheme(isDark) {
                if (isDark) {
                    body.classList.add('dark-mode');
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');
                    }
                    if (darkModeIcon) {
                        darkModeIcon.classList.remove('fa-moon');
                        darkModeIcon.classList.add('fa-sun');
                    }
                } else {
                    body.classList.remove('dark-mode');
                    if (themeIcon) {
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');
                    }
                    if (darkModeIcon) {
                        darkModeIcon.classList.remove('fa-sun');
                        darkModeIcon.classList.add('fa-moon');
                    }
                }
            }

            // Initialiser le thème
            function initTheme() {
                const savedTheme = localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = savedTheme === 'dark' || (!savedTheme && systemPrefersDark);
                applyTheme(isDark);
            }
            
            initTheme();
            
            // Fonction pour basculer le thème
            function toggleTheme() {
                const isDark = body.classList.contains('dark-mode');
                applyTheme(!isDark);
                localStorage.setItem('theme', !isDark ? 'dark' : 'light');
            }
            
            // Event listeners pour les boutons
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }
            
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', toggleTheme);
            }
        });

        // Gestion de la recherche avec jQuery (si disponible)
        jQuery(document).ready(function($) {
            // Vérifier si jQuery est chargé
            if (typeof $ === 'undefined') {
                console.warn('jQuery non disponible, certaines fonctionnalités peuvent être limitées.');
            }

            // Vérifier si nous sommes sur la page team
            const isTeamPage = window.location.pathname.includes('/team');
            const $searchZone = $('#searchZone');
            
            if (isTeamPage) {
                $searchZone.addClass('show');
                initMemberSearch();
            }

            function initMemberSearch() {
                const $searchInput = $('#memberSearch');
                const $searchResults = $('#searchResults');
                
                $searchInput.on('input', function() {
                    const query = this.value.trim();
                    
                    if (query.length < 2) {
                        $searchResults.hide().empty();
                        return;
                    }
                    
                    $.ajax({
                        url: '/Altiris/root/frontoffice/search_members.php',
                        method: 'GET',
                        data: { q: query },
                        dataType: 'json',
                        headers: {
                            'Accept': 'application/json'
                        },
                        success: function(data) {
                            displaySearchResults(data);
                        },
                        error: function(error) {
                            console.error('Erreur de recherche:', error);
                            $searchResults.html('<div class="search-result-item">Erreur lors de la recherche</div>').show();
                        }
                    });
                });
                
                $(document).on('click', function(e) {
                    if (!$searchInput.is(e.target) && !$searchResults.is(e.target) && $searchResults.has(e.target).length === 0) {
                        $searchResults.hide().empty();
                    }
                });
            }
            
            function displaySearchResults(members) {
                const $searchResults = $('#searchResults');
                
                if (!Array.isArray(members) || members.length === 0) {
                    $searchResults.html('<div class="search-result-item">Aucun membre trouvé</div>').show();
                } else {
                    $searchResults.html(members.map(member => `
                        <div class="search-result-item" onclick="scrollToMember('${member.id_membre}')">
                            <div class="member-name">${sanitizeHTML(member.prenom + ' ' + member.nom)}</div>
                            <div class="member-role">${sanitizeHTML(member.role)}</div>
                        </div>
                    `).join('')).show();
                }
            }
            
            function scrollToMember(memberId) {
                $('#searchResults').hide().empty();
                const $memberElement = $(`[data-member-id="${memberId}"]`);
                if ($memberElement.length) {
                    const slideIndex = $memberElement.index();
                    $('.team-slider').slick('slickGoTo', slideIndex);
                    $memberElement.addClass('highlight');
                    setTimeout(() => $memberElement.removeClass('highlight'), 2000);
                }
            }

            function sanitizeHTML(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }
        });
    </script>
</body>
</html>