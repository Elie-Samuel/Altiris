<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header("Location: /connexion");
    exit;
}
require_once dirname(__DIR__) . '/models/Utilisateur.php';
require_once dirname(__DIR__) . '/models/Contacte.php';
$userModel = new Utilisateur();
$user = $userModel->read($_SESSION['user_id']);
$contactModel = new Contacte();
$newMessagesCount = $contactModel->countNewMessages();
$adminName = htmlspecialchars($user['nom_utilisateur'] ?? 'Elie Samuel');
$profileLink = "/Altiris/profil/" . $_SESSION['user_id'];
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/';
$defaultImage = '/Altiris/Assets/Images/default_profile.jpg';
$imagePath = !empty($user['profil']) && file_exists($uploadDir . $user['profil']) 
    ? '/Altiris/' . htmlspecialchars($user['profil']) 
    : $defaultImage;
$userType = $_SESSION['types'] ?? 'Admin';
// Get current page for breadcrumb and title
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
// Define page titles
$pageTitles = ['dashboard' => 'Tableau de Bord','annonces' => 'Gestion des Annonces','services' => 'Gestion des Services',
    'actualiters' => 'Gestion des Actualités','membres' => 'Gestion des Membres','contact_ent' => 'Contact Entreprise',
    'contacte' => 'Rendez-vous','competences' => 'Gestion des Compétences','temoignages' => 'Gestion des Témoignages',
    'altirys_info' => 'Information Altirys','apropos_hist' => 'Apropos Historique','apropos_desc' => 'Apropos Description',
    'apropos_mission' => 'Apropos Mission','apropos_valeur' => 'Apropos Valeur','blog_posts' => 'Articles Blog',
    'blog_desc' => 'Description Blog','legal_pages' => 'Pages Légales','social_links' => 'Liens Sociaux',
    'notifications' => 'Notifications','parametres' => 'Paramètres','utilisateurs' => 'Utilisateurs','profile' => 'Profil'
];
$pageTitle = $pageTitles[$currentDir] ?? 'Altiris Back Office';
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Altiris </title>
    <link rel="stylesheet" href="/Altiris/Assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Altiris/Assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/Altiris/back_office/css/altiris-theme.css">
    <link rel="icon" type="image/x-icon" href="/Altiris/Assets/Images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="/Altiris/Assets/js/jquery-3.7.1.min.js"></script>
    <script src="/Altiris/Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/Altiris/back_office/js/notifications.js"></script>
    <script src="/Altiris/back_office/js/theme-manager.js"></script>
</head>
<body class="altiris-theme">
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-container">
                <div class="brand-logo">
                     <img src="/Altiris/Assets/Images/logo_altirys.png" alt="Logo Altiris" class="brand-logo">
                </div>
                <span class="brand-text">Altiris</span>
                <button class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()">
                    <i class="fas fa-angle-left"></i>
                </button>
            </div>
            <button class="sidebar-close d-lg-none" onclick="closeSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title">PRINCIPAL</span>
                <a class="nav-item" href="/Altiris/accueil">
                    <i class=" fas fa-tachometer-alt nav-icon"></i>
                    <span class="nav-text">Tableau de Bord</span>
                </a>
            </div>
            
            <div class="nav-section">
                <span class="nav-section-title">GESTION ACCUEIL</span>
                <a class="nav-item" href="/Altiris/annonces">
                    <i class="fas fa-bullhorn nav-icon"></i>
                    <span class="nav-text">Annonces</span>
                </a>
                <a class="nav-item" href="/Altiris/information-altirys">
                    <i class="fas fa-info-circle nav-icon"></i>
                    <span class="nav-text">Information Altirys</span>
                </a>
                <a class="nav-item" href="/Altiris/services">
                    <i class="fas fa-cogs nav-icon"></i>
                    <span class="nav-text">Services</span>
                </a>
                <a class="nav-item" href="/Altiris/actualites">
                    <i class="fas fa-newspaper nav-icon"></i>
                    <span class="nav-text">Actualités</span>
                </a>
                <a class="nav-item" href="/Altiris/temoignages">
                    <i class="fas fa-quote-left nav-icon"></i>
                    <span class="nav-text">Témoignages</span>
                </a>
                <a class="nav-item" href="/Altiris/competences">
                    <i class="fas fa-star nav-icon"></i>
                    <span class="nav-text">Compétences</span>
                </a>
            </div>
            
            <div class="nav-section">
                <span class="nav-section-title">GESTION MEMBRES</span>
                <a class="nav-item" href="/Altiris/membres">
                    <i class="fas fa-users nav-icon"></i>
                    <span class="nav-text">Membres</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">GESTION APROPOS</span>
                <a class="nav-item" href="/Altiris/apropos-historique">
                    <i class="fas fa-history nav-icon"></i>
                    <span class="nav-text">Apropos Historique</span>
                </a>
                <a class="nav-item" href="/Altiris/apropos-description">
                    <i class="fas fa-file-text nav-icon"></i>
                    <span class="nav-text">Apropos Description</span>
                </a>
                <a class="nav-item" href="/Altiris/apropos-mission">
                    <i class="fas fa-bullseye nav-icon"></i>
                    <span class="nav-text">Apropos Mission</span>
                </a>
                <a class="nav-item" href="/Altiris/apropos-valeur">
                    <i class="fas fa-gem nav-icon"></i>
                    <span class="nav-text">Apropos Valeur</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">GESTION CONTACTS</span>
                <a class="nav-item" href="/Altiris/contact-entreprise">
                    <i class="fas fa-building nav-icon"></i>
                    <span class="nav-text">Contact Entreprise</span>
                </a>
                <a class="nav-item" href="/Altiris/rendez-vous">
                    <i class="fas fa-calendar-alt nav-icon"></i>
                    <span class="nav-text">Rendez-vous</span>
                    <?php if ($newMessagesCount > 0): ?>
                        <span class="nav-badge"><?php echo $newMessagesCount > 99 ? '99+' : $newMessagesCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">GESTION BLOGS</span>
                <a class="nav-item" href="/Altiris/articles-blog">
                    <i class="fas fa-blog nav-icon"></i>
                    <span class="nav-text">Articles Blog</span>
                </a>
                <a class="nav-item" href="/Altiris/description-blog">
                    <i class="fas fa-file-alt nav-icon"></i>
                    <span class="nav-text">Description Blog</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">GESTION PIED DE PAGE</span>
                <a class="nav-item" href="/Altiris/pages-legales">
                    <i class="fas fa-gavel nav-icon"></i>
                    <span class="nav-text">Pages Légales</span>
                </a>
                <a class="nav-item" href="/Altiris/liens-sociaux">
                    <i class="fas fa-share-alt nav-icon"></i>
                    <span class="nav-text">Liens Sociaux</span>
                </a>
            </div>
            
            <div class="nav-section">
                <span class="nav-section-title">SYSTÈME</span>
                <a class="nav-item" href="/Altiris/notifications">
                    <i class="fas fa-bell nav-icon"></i>
                    <span class="nav-text">Notifications</span>
                    <span class="nav-badge" id="notificationBadge" style="display: none;">0</span>
                </a>
                <a class="nav-item" href="/Altiris/parametres">
                    <i class="fas fa-cog nav-icon"></i>
                    <span class="nav-text">Paramètres</span>
                </a>
                <?php if ($userType === 'Super admin'): ?>
                    <a class="nav-item" href="/Altiris/utilisateurs">
                        <i class="fas fa-user-shield nav-icon"></i>
                        <span class="nav-text">Utilisateurs</span>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <img src="<?php echo $imagePath; ?>" alt="Photo de profil" class="user-avatar">
                <div class="user-details">
                    <span class="user-name"><?php echo $adminName; ?></span>
                    <span class="user-role"><?php echo $userType; ?></span>
                </div>
            </div>
            <div class="sidebar-actions">
                <a href="<?php echo $profileLink; ?>" class="action-btn" title="Profil">
                    <i class="fas fa-user"></i>
                </a>
                <a href="#" class="action-btn" onclick="confirmLogout()" title="Déconnexion">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </aside>
    
    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="navbar-left">
            <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="breadcrumb-container d-none d-lg-block">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/Altiris/accueil">Accueil</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo $pageTitle; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="navbar-center d-none d-md-block">
            <div class="search-container">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher dans Altiris..." aria-label="Search">
                    <button class="search-clear" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="search-suggestions" id="searchSuggestions" style="display: none;">
                    <!-- Les suggestions de recherche apparaîtront ici -->
                </div>
            </div>
        </div>
        
        <div class="navbar-right">
            <div class="navbar-actions">
                <!-- Theme Toggle -->
                <button class="navbar-btn theme-toggle" onclick="toggleTheme()" title="Changer le thème">
                    <i class="fas fa-moon theme-icon"></i>
                </button>
                
                <!-- Notifications -->
                <div class="notification-dropdown-container">
                    <button class="navbar-btn notification-btn" id="notificationToggle">
                        <i class="fas fa-bell"></i>
                        <span class="notification-count" id="notificationCount" style="display: none;">0</span>
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h6>Notifications</h6>
                            <button class="mark-all-read" id="markAllRead">Tout marquer comme lu</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="empty-notifications">
                                <i class="fas fa-bell-slash"></i>
                                <p>Aucune notification</p>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="/Altiris/notifications">Voir toutes les notifications</a>
                        </div>
                    </div>
                </div>
                
                <!-- Messages -->
                <a href="/Altiris/rendez-vous" class="navbar-btn message-btn" title="Messages">
                    <i class="fas fa-envelope"></i>
                    <?php if ($newMessagesCount > 0): ?>
                        <span class="notification-count" id="messageCount"><?php echo $newMessagesCount > 99 ? '99+' : $newMessagesCount; ?></span>
                    <?php endif; ?>
                </a>
                
                <!-- Settings -->
                <a href="/Altiris/parametres" class="navbar-btn" title="Paramètres">
                    <i class="fas fa-cog"></i>
                </a>
                
                <!-- User Profile Dropdown -->
                <div class="user-dropdown-container">
                    <button class="user-profile-btn" id="userProfileToggle">
                        <img src="<?php echo $imagePath; ?>" alt="Photo de profil" class="user-avatar-small">
                        <span class="user-name-small d-none d-md-inline"><?php echo $adminName; ?></span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <img src="<?php echo $imagePath; ?>" alt="Photo de profil" class="user-avatar">
                            <div class="user-info">
                                <span class="user-name"><?php echo $adminName; ?></span>
                                <span class="user-role"><?php echo $userType; ?></span>
                            </div>
                        </div>
                        <div class="user-dropdown-menu">
                            <a href="<?php echo $profileLink; ?>" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Mon Profil</span>
                            </a>
                            <a href="/Altiris/parametres" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Paramètres</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item logout-item" onclick="confirmLogout()">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content Area -->
    <main class="main-content" id="mainContent">
        <div class="content-wrapper">
            <!-- Le contenu principal sera inséré ici par les fichiers incluant header.php -->

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
}

function toggleSidebarCollapse() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const navbar = document.querySelector('.top-navbar');
    
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('sidebar-collapsed');
    navbar.classList.toggle('sidebar-collapsed');
    
    // Save state
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

function confirmLogout() {
    if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
        window.location.href = "/Altiris/connexion";
    }
}

function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    // Update icon
    const themeIcon = document.querySelector('.theme-icon');
    themeIcon.className = newTheme === 'dark' ? 'fas fa-sun theme-icon' : 'fas fa-moon theme-icon';
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    const themeIcon = document.querySelector('.theme-icon');
    if (themeIcon) {
        themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun theme-icon' : 'fas fa-moon theme-icon';
    }
    
    // Initialize sidebar state
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed && window.innerWidth >= 992) {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const navbar = document.querySelector('.top-navbar');
        
        sidebar.classList.add('collapsed');
        mainContent.classList.add('sidebar-collapsed');
        navbar.classList.add('sidebar-collapsed');
    }
    
    // Set active nav item
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-item');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (currentPath.includes(href.split('/').pop()) || href === currentPath)) {
            link.classList.add('active');
        }
    });

    // Initialize dropdowns
    const notificationToggle = document.getElementById('notificationToggle');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const userProfileToggle = document.getElementById('userProfileToggle');
    const userDropdown = document.getElementById('userDropdown');

    if (notificationToggle && notificationDropdown) {
        notificationToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            if (userDropdown) userDropdown.classList.remove('show');
        });
    }

    if (userProfileToggle && userDropdown) {
        userProfileToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            if (notificationDropdown) notificationDropdown.classList.remove('show');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.notification-dropdown-container') && notificationDropdown) {
            notificationDropdown.classList.remove('show');
        }
        if (!e.target.closest('.user-dropdown-container') && userDropdown) {
            userDropdown.classList.remove('show');
        }
    });

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const searchClear = document.querySelector('.search-clear');
    const searchSuggestions = document.getElementById('searchSuggestions');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                searchClear.style.display = 'block';
                // Add search suggestions logic here
            } else {
                searchClear.style.display = 'none';
                searchSuggestions.style.display = 'none';
            }
        });
    }

    if (searchClear) {
        searchClear.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            searchSuggestions.style.display = 'none';
        });
    }

    // Message count update
    function updateMessageCount() {
        fetch('/Altiris/back_office/api/new-messages.php', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('messageCount');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour du badge : ', error);
        });
    }

    updateMessageCount();
    setInterval(updateMessageCount, 30000);

    // Listen for message marked as read events
    document.addEventListener('messageMarkedAsRead', function() {
        updateMessageCount();
    });

    // Close sidebar on link click (mobile)
    document.querySelectorAll('.sidebar .nav-item').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });
});
</script>