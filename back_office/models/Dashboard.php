<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Dashboard {
    private $conn;
    private $tables = [
        'utilisateurs' => 'utilisateurs',
        'annonce' => 'annonce',
        'service' => 'service',
        'actualiter' => 'actualiter',
        'membres' => 'membres',
        'contacte' => 'contacte',
        'temoignage' => 'temoignage',
        'competence' => 'competence',
        'contact_ent' => 'contact_ent',
        'blog_posts' => 'blog_posts',
        'legal_pages' => 'legal_pages',
        'social_links' => 'social_links',
        'apropos_hist' => 'apropos_hist',
        'blog_desc' => 'blog_desc',
        'altirys_info' => 'altirys_info',
        'apropos_desc' => 'apropos_desc',
        'apropos_mission' => 'apropos_mission',
        'apropos_valeur' => 'apropos_valeur',
        'contact_requests' => 'contact_requests',
        'notifications' => 'notifications',
        'parametres' => 'parametres',
        'rendez_vous' => 'rendez_vous'
    ];

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();
            if (!$this->conn) {
                error_log("Dashboard.php: Échec de la connexion à la base de données. Vérifiez config/db.php.");
                throw new Exception("Échec de la connexion à la base de données.");
            }
        } catch (Exception $e) {
            error_log("Dashboard.php: Erreur lors de l'initialisation de la base de données : " . $e->getMessage());
            throw $e;
        }
    }

    private function checkTableExists($table) {
        try {
            $query = "SHOW TABLES LIKE :table";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':table', $table);
            $stmt->execute();
            $exists = $stmt->rowCount() > 0;
            if (!$exists) {
                error_log("Dashboard.php: La table $table n'existe pas dans la base de données.");
            }
            return $exists;
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la vérification de l'existence de la table $table : " . $e->getMessage());
            return false;
        }
    }

    private function checkTableColumns($table, $requiredColumns) {
        if (!$this->checkTableExists($table)) {
            return false;
        }
        try {
            $query = "DESCRIBE $table";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $columns = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
            $missingColumns = array_diff($requiredColumns, $columns);
            if (!empty($missingColumns)) {
                error_log("Dashboard.php: Colonnes manquantes dans la table $table : " . implode(', ', $missingColumns));
                return false;
            }
            return true;
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la vérification des colonnes de la table $table : " . $e->getMessage());
            return false;
        }
    }

    public function getGeneralStats() {
        try {
            $stats = [];

            // Statistiques des utilisateurs
            if ($this->checkTableExists($this->tables['utilisateurs'])) {
                $query = "SELECT COUNT(*) as total_users";
                $hasStatus = $this->checkTableColumns($this->tables['utilisateurs'], ['status']);
                $hasTypes = $this->checkTableColumns($this->tables['utilisateurs'], ['Types']);
                $hasDateCreation = $this->checkTableColumns($this->tables['utilisateurs'], ['date_creation']);

                if ($hasStatus) {
                    $query .= ", SUM(CASE WHEN LOWER(status) = 'actif' THEN 1 ELSE 0 END) as active_users";
                } else {
                    $query .= ", 0 as active_users";
                }
                if ($hasTypes) {
                    $query .= ", SUM(CASE WHEN LOWER(Types) = 'super admin' THEN 1 ELSE 0 END) as super_admins";
                    $query .= ", SUM(CASE WHEN LOWER(Types) = 'admin' THEN 1 ELSE 0 END) as admins";
                } else {
                    $query .= ", 0 as super_admins, 0 as admins";
                }
                if ($hasDateCreation) {
                    $query .= ", SUM(CASE WHEN DATE(date_creation) >= CURDATE() - INTERVAL 30 DAY THEN 1 ELSE 0 END) as new_users_month";
                } else {
                    $query .= ", 0 as new_users_month";
                }
                $query .= " FROM " . $this->tables['utilisateurs'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_users' => 0, 'active_users' => 0, 'super_admins' => 0, 'admins' => 0, 'new_users_month' => 0];
            } else {
                error_log("Dashboard.php: Table utilisateurs manquante.");
                $stats['users'] = ['total_users' => 0, 'active_users' => 0, 'super_admins' => 0, 'admins' => 0, 'new_users_month' => 0];
            }

            // Statistiques des annonces
            if ($this->checkTableExists($this->tables['annonce'])) {
                $query = "SELECT COUNT(*) as total_annonces FROM " . $this->tables['annonce'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['annonces'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_annonces' => 0];
            } else {
                error_log("Dashboard.php: Table annonce manquante.");
                $stats['annonces'] = ['total_annonces' => 0];
            }

            // Statistiques des services
            if ($this->checkTableExists($this->tables['service'])) {
                $query = "SELECT COUNT(*) as total_services FROM " . $this->tables['service'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['services'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_services' => 0];
            } else {
                error_log("Dashboard.php: Table service manquante.");
                $stats['services'] = ['total_services' => 0];
            }

            // Statistiques des actualités
            if ($this->checkTableExists($this->tables['actualiter'])) {
                $hasDate = $this->checkTableColumns($this->tables['actualiter'], ['Date']);
                $query = "SELECT COUNT(*) as total_actualites";
                if ($hasDate) {
                    $query .= ", SUM(CASE WHEN DATE(Date) >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) as recent_actualites";
                } else {
                    $query .= ", 0 as recent_actualites";
                }
                $query .= " FROM " . $this->tables['actualiter'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['actualites'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_actualites' => 0, 'recent_actualites' => 0];
            } else {
                error_log("Dashboard.php: Table actualiter manquante.");
                $stats['actualites'] = ['total_actualites' => 0, 'recent_actualites' => 0];
            }

            // Statistiques des membres
            if ($this->checkTableExists($this->tables['membres'])) {
                $hasStatut = $this->checkTableColumns($this->tables['membres'], ['statut']);
                $hasDateCreation = $this->checkTableColumns($this->tables['membres'], ['date_creation']);
                $query = "SELECT COUNT(*) as total_membres";
                if ($hasStatut) {
                    $query .= ", SUM(CASE WHEN LOWER(statut) = 'actif' THEN 1 ELSE 0 END) as active_membres";
                } else {
                    $query .= ", 0 as active_membres";
                }
                if ($hasDateCreation) {
                    $query .= ", SUM(CASE WHEN DATE(date_creation) >= CURDATE() - INTERVAL 30 DAY THEN 1 ELSE 0 END) as new_membres_month";
                } else {
                    $query .= ", 0 as new_membres_month";
                }
                $query .= " FROM " . $this->tables['membres'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['membres'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_membres' => 0, 'active_membres' => 0, 'new_membres_month' => 0];
            } else {
                error_log("Dashboard.php: Table membres manquante.");
                $stats['membres'] = ['total_membres' => 0, 'active_membres' => 0, 'new_membres_month' => 0];
            }

            // Statistiques des contacts
            if ($this->checkTableExists($this->tables['contacte'])) {
                $hasStatus = $this->checkTableColumns($this->tables['contacte'], ['status']);
                $hasResponse = $this->checkTableColumns($this->tables['contacte'], ['response']);
                $query = "SELECT COUNT(*) as total_contacts";
                if ($hasStatus) {
                    $query .= ", SUM(CASE WHEN LOWER(status) = 'nouveau' THEN 1 ELSE 0 END) as new_contacts";
                } else {
                    $query .= ", 0 as new_contacts";
                }
                if ($hasResponse) {
                    $query .= ", SUM(CASE WHEN LOWER(response) = 'accepté' THEN 1 ELSE 0 END) as accepted_contacts";
                    $query .= ", SUM(CASE WHEN LOWER(response) = 'refusé' THEN 1 ELSE 0 END) as rejected_contacts";
                } else {
                    $query .= ", 0 as accepted_contacts, 0 as rejected_contacts";
                }
                $query .= " FROM " . $this->tables['contacte'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['contacts'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_contacts' => 0, 'new_contacts' => 0, 'accepted_contacts' => 0, 'rejected_contacts' => 0];
            } else {
                error_log("Dashboard.php: Table contacte manquante.");
                $stats['contacts'] = ['total_contacts' => 0, 'new_contacts' => 0, 'accepted_contacts' => 0, 'rejected_contacts' => 0];
            }

            // Statistiques des témoignages
            if ($this->checkTableExists($this->tables['temoignage'])) {
                $hasDateTem = $this->checkTableColumns($this->tables['temoignage'], ['date_tem']);
                $query = "SELECT COUNT(*) as total_temoignages";
                if ($hasDateTem) {
                    $query .= ", SUM(CASE WHEN DATE(date_tem) >= CURDATE() - INTERVAL 30 DAY THEN 1 ELSE 0 END) as recent_temoignages";
                } else {
                    $query .= ", 0 as recent_temoignages";
                }
                $query .= " FROM " . $this->tables['temoignage'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['temoignages'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_temoignages' => 0, 'recent_temoignages' => 0];
            } else {
                error_log("Dashboard.php: Table temoignage manquante.");
                $stats['temoignages'] = ['total_temoignages' => 0, 'recent_temoignages' => 0];
            }

            // Statistiques des compétences
            if ($this->checkTableExists($this->tables['competence'])) {
                $query = "SELECT COUNT(*) as total_competences FROM " . $this->tables['competence'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['competences'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_competences' => 0];
            } else {
                error_log("Dashboard.php: Table competence manquante.");
                $stats['competences'] = ['total_competences' => 0];
            }

            // Statistiques des contacts entreprise
            if ($this->checkTableExists($this->tables['contact_ent'])) {
                $query = "SELECT COUNT(*) as total_contact_ent FROM " . $this->tables['contact_ent'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['contact_ent'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_contact_ent' => 0];
            } else {
                error_log("Dashboard.php: Table contact_ent manquante.");
                $stats['contact_ent'] = ['total_contact_ent' => 0];
            }

            // Statistiques des blogs
            if ($this->checkTableExists($this->tables['blog_posts'])) {
                $query = "SELECT COUNT(*) as total_blog_posts FROM " . $this->tables['blog_posts'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['blog_posts'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_blog_posts' => 0];
            } else {
                error_log("Dashboard.php: Table blog_posts manquante.");
                $stats['blog_posts'] = ['total_blog_posts' => 0];
            }

            // Statistiques des pages légales
            if ($this->checkTableExists($this->tables['legal_pages'])) {
                $query = "SELECT COUNT(*) as total_legal_pages FROM " . $this->tables['legal_pages'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['legal_pages'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_legal_pages' => 0];
            } else {
                error_log("Dashboard.php: Table legal_pages manquante.");
                $stats['legal_pages'] = ['total_legal_pages' => 0];
            }

            // Statistiques des liens sociaux
            if ($this->checkTableExists($this->tables['social_links'])) {
                $query = "SELECT COUNT(*) as total_social_links FROM " . $this->tables['social_links'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['social_links'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_social_links' => 0];
            } else {
                error_log("Dashboard.php: Table social_links manquante.");
                $stats['social_links'] = ['total_social_links' => 0];
            }

            // Statistiques de l'historique à propos
            if ($this->checkTableExists($this->tables['apropos_hist'])) {
                $query = "SELECT COUNT(*) as total_apropos_hist FROM " . $this->tables['apropos_hist'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['apropos_hist'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_apropos_hist' => 0];
            } else {
                error_log("Dashboard.php: Table apropos_hist manquante.");
                $stats['apropos_hist'] = ['total_apropos_hist' => 0];
            }

            // Statistiques de la description du blog
            if ($this->checkTableExists($this->tables['blog_desc'])) {
                $query = "SELECT COUNT(*) as total_blog_desc FROM " . $this->tables['blog_desc'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['blog_desc'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_blog_desc' => 0];
            } else {
                error_log("Dashboard.php: Table blog_desc manquante.");
                $stats['blog_desc'] = ['total_blog_desc' => 0];
            }

            // Statistiques pour altirys_info
            if ($this->checkTableExists($this->tables['altirys_info'])) {
                $query = "SELECT COUNT(*) as total_altirys_info FROM " . $this->tables['altirys_info'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['altirys_info'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_altirys_info' => 0];
            } else {
                error_log("Dashboard.php: Table altirys_info manquante.");
                $stats['altirys_info'] = ['total_altirys_info' => 0];
            }

            // Statistiques pour apropos_desc
            if ($this->checkTableExists($this->tables['apropos_desc'])) {
                $query = "SELECT COUNT(*) as total_apropos_desc FROM " . $this->tables['apropos_desc'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['apropos_desc'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_apropos_desc' => 0];
            } else {
                error_log("Dashboard.php: Table apropos_desc manquante.");
                $stats['apropos_desc'] = ['total_apropos_desc' => 0];
            }

            // Statistiques pour apropos_mission
            if ($this->checkTableExists($this->tables['apropos_mission'])) {
                $query = "SELECT COUNT(*) as total_apropos_mission FROM " . $this->tables['apropos_mission'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['apropos_mission'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_apropos_mission' => 0];
            } else {
                error_log("Dashboard.php: Table apropos_mission manquante.");
                $stats['apropos_mission'] = ['total_apropos_mission' => 0];
            }

            // Statistiques pour apropos_valeur
            if ($this->checkTableExists($this->tables['apropos_valeur'])) {
                $query = "SELECT COUNT(*) as total_apropos_valeur FROM " . $this->tables['apropos_valeur'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['apropos_valeur'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_apropos_valeur' => 0];
            } else {
                error_log("Dashboard.php: Table apropos_valeur manquante.");
                $stats['apropos_valeur'] = ['total_apropos_valeur' => 0];
            }

            // Statistiques pour contact_requests
            if ($this->checkTableExists($this->tables['contact_requests'])) {
                $hasStatus = $this->checkTableColumns($this->tables['contact_requests'], ['status']);
                $query = "SELECT COUNT(*) as total_contact_requests";
                if ($hasStatus) {
                    $query .= ", SUM(CASE WHEN LOWER(status) = 'pending' THEN 1 ELSE 0 END) as pending_contact_requests";
                } else {
                    $query .= ", 0 as pending_contact_requests";
                }
                $query .= " FROM " . $this->tables['contact_requests'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['contact_requests'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_contact_requests' => 0, 'pending_contact_requests' => 0];
            } else {
                error_log("Dashboard.php: Table contact_requests manquante.");
                $stats['contact_requests'] = ['total_contact_requests' => 0, 'pending_contact_requests' => 0];
            }

            // Statistiques pour notifications
            if ($this->checkTableExists($this->tables['notifications'])) {
                $hasStatus = $this->checkTableColumns($this->tables['notifications'], ['status']);
                $query = "SELECT COUNT(*) as total_notifications";
                if ($hasStatus) {
                    $query .= ", SUM(CASE WHEN LOWER(status) = 'unread' THEN 1 ELSE 0 END) as unread_notifications";
                } else {
                    $query .= ", 0 as unread_notifications";
                }
                $query .= " FROM " . $this->tables['notifications'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['notifications'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_notifications' => 0, 'unread_notifications' => 0];
            } else {
                error_log("Dashboard.php: Table notifications manquante.");
                $stats['notifications'] = ['total_notifications' => 0, 'unread_notifications' => 0];
            }

            // Statistiques pour parametres
            if ($this->checkTableExists($this->tables['parametres'])) {
                $query = "SELECT COUNT(*) as total_parametres FROM " . $this->tables['parametres'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['parametres'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_parametres' => 0];
            } else {
                error_log("Dashboard.php: Table parametres manquante.");
                $stats['parametres'] = ['total_parametres' => 0];
            }

            // Statistiques pour rendez_vous
            if ($this->checkTableExists($this->tables['rendez_vous'])) {
                $hasStatus = $this->checkTableColumns($this->tables['rendez_vous'], ['status']);
                $hasDate = $this->checkTableColumns($this->tables['rendez_vous'], ['date_rdv']);
                $query = "SELECT COUNT(*) as total_rendez_vous";
                if ($hasStatus) {
                    $query .= ", SUM(CASE WHEN LOWER(status) = 'confirmed' THEN 1 ELSE 0 END) as confirmed_rendez_vous";
                } else {
                    $query .= ", 0 as confirmed_rendez_vous";
                }
                if ($hasDate) {
                    $query .= ", SUM(CASE WHEN DATE(date_rdv) >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) as recent_rendez_vous";
                } else {
                    $query .= ", 0 as recent_rendez_vous";
                }
                $query .= " FROM " . $this->tables['rendez_vous'];
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['rendez_vous'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_rendez_vous' => 0, 'confirmed_rendez_vous' => 0, 'recent_rendez_vous' => 0];
            } else {
                error_log("Dashboard.php: Table rendez_vous manquante.");
                $stats['rendez_vous'] = ['total_rendez_vous' => 0, 'confirmed_rendez_vous' => 0, 'recent_rendez_vous' => 0];
            }

            return $stats;
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la récupération des statistiques générales : " . $e->getMessage());
            return [
                'users' => ['total_users' => 0, 'active_users' => 0, 'super_admins' => 0, 'admins' => 0, 'new_users_month' => 0],
                'annonces' => ['total_annonces' => 0],
                'services' => ['total_services' => 0],
                'actualites' => ['total_actualites' => 0, 'recent_actualites' => 0],
                'membres' => ['total_membres' => 0, 'active_membres' => 0, 'new_membres_month' => 0],
                'contacts' => ['total_contacts' => 0, 'new_contacts' => 0, 'accepted_contacts' => 0, 'rejected_contacts' => 0],
                'temoignages' => ['total_temoignages' => 0, 'recent_temoignages' => 0],
                'competences' => ['total_competences' => 0],
                'contact_ent' => ['total_contact_ent' => 0],
                'blog_posts' => ['total_blog_posts' => 0],
                'legal_pages' => ['total_legal_pages' => 0],
                'social_links' => ['total_social_links' => 0],
                'apropos_hist' => ['total_apropos_hist' => 0],
                'blog_desc' => ['total_blog_desc' => 0],
                'altirys_info' => ['total_altirys_info' => 0],
                'apropos_desc' => ['total_apropos_desc' => 0],
                'apropos_mission' => ['total_apropos_mission' => 0],
                'apropos_valeur' => ['total_apropos_valeur' => 0],
                'contact_requests' => ['total_contact_requests' => 0, 'pending_contact_requests' => 0],
                'notifications' => ['total_notifications' => 0, 'unread_notifications' => 0],
                'parametres' => ['total_parametres' => 0],
                'rendez_vous' => ['total_rendez_vous' => 0, 'confirmed_rendez_vous' => 0, 'recent_rendez_vous' => 0]
            ];
        }
    }

    public function getRecentActivities() {
        try {
            $activities = [];

            // Derniers utilisateurs créés
            if ($this->checkTableExists($this->tables['utilisateurs']) && $this->checkTableColumns($this->tables['utilisateurs'], ['nom_utilisateur', 'email', 'date_creation'])) {
                $query = "SELECT nom_utilisateur, email, date_creation, 'user' as type 
                          FROM " . $this->tables['utilisateurs'] . " 
                          ORDER BY date_creation DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table utilisateurs ou colonnes manquantes pour activités récentes.");
                $recentUsers = [];
            }

            // Derniers contacts
            if ($this->checkTableExists($this->tables['contacte']) && $this->checkTableColumns($this->tables['contacte'], ['name', 'email', 'subject', 'date_creation'])) {
                $query = "SELECT name, email, subject, date_creation, 'contact' as type 
                          FROM " . $this->tables['contacte'] . " 
                          ORDER BY date_creation DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentContacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table contacte ou colonnes manquantes pour activités récentes.");
                $recentContacts = [];
            }

            // Derniers membres
            if ($this->checkTableExists($this->tables['membres']) && $this->checkTableColumns($this->tables['membres'], ['prenom', 'nom', 'email', 'date_creation'])) {
                $query = "SELECT CONCAT(prenom, ' ', nom) as name, email, date_creation, 'membre' as type 
                          FROM " . $this->tables['membres'] . " 
                          ORDER BY date_creation DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentMembres = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table membres ou colonnes manquantes pour activités récentes.");
                $recentMembres = [];
            }

            // Derniers articles de blog
            if ($this->checkTableExists($this->tables['blog_posts']) && $this->checkTableColumns($this->tables['blog_posts'], ['title', 'created_at'])) {
                $query = "SELECT title as name, 'blog' as email, created_at as date_creation, 'blog_post' as type 
                          FROM " . $this->tables['blog_posts'] . " 
                          ORDER BY created_at DESC 
                          LIMIT 3";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table blog_posts ou colonnes manquantes pour activités récentes.");
                $recentBlogs = [];
            }

            // Derniers témoignages
            if ($this->checkTableExists($this->tables['temoignage']) && $this->checkTableColumns($this->tables['temoignage'], ['Nom', 'date_tem'])) {
                $query = "SELECT Nom as name, 'temoignage' as email, date_tem as date_creation, 'temoignage' as type 
                          FROM " . $this->tables['temoignage'] . " 
                          ORDER BY date_tem DESC 
                          LIMIT 3";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentTemoignages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table temoignage ou colonnes manquantes pour activités récentes.");
                $recentTemoignages = [];
            }

            // Derniers contact_requests
            if ($this->checkTableExists($this->tables['contact_requests']) && $this->checkTableColumns($this->tables['contact_requests'], ['name', 'email', 'date_creation'])) {
                $query = "SELECT name, email, date_creation, 'contact_request' as type 
                          FROM " . $this->tables['contact_requests'] . " 
                          ORDER BY date_creation DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentContactRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table contact_requests ou colonnes manquantes pour activités récentes.");
                $recentContactRequests = [];
            }

            // Dernières notifications
            if ($this->checkTableExists($this->tables['notifications']) && $this->checkTableColumns($this->tables['notifications'], ['message', 'created_at'])) {
                $query = "SELECT message as name, 'notification' as email, created_at as date_creation, 'notification' as type 
                          FROM " . $this->tables['notifications'] . " 
                          ORDER BY created_at DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table notifications ou colonnes manquantes pour activités récentes.");
                $recentNotifications = [];
            }

            // Derniers rendez_vous
            if ($this->checkTableExists($this->tables['rendez_vous']) && $this->checkTableColumns($this->tables['rendez_vous'], ['title', 'date_rdv'])) {
                $query = "SELECT title as name, 'rendez-vous' as email, date_rdv as date_creation, 'rendez_vous' as type 
                          FROM " . $this->tables['rendez_vous'] . " 
                          ORDER BY date_rdv DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $recentRendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table rendez_vous ou colonnes manquantes pour activités récentes.");
                $recentRendezVous = [];
            }

            // Fusionner et trier par date
            $activities = array_merge(
                $recentUsers,
                $recentContacts,
                $recentMembres,
                $recentBlogs,
                $recentTemoignages,
                $recentContactRequests,
                $recentNotifications,
                $recentRendezVous
            );
            usort($activities, function($a, $b) {
                return strtotime($b['date_creation']) - strtotime($a['date_creation']);
            });

            return array_slice($activities, 0, 10);
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la récupération des activités récentes : " . $e->getMessage());
            return [];
        }
    }

    public function getMonthlyStats() {
        try {
            $monthlyData = [];

            // Données des 12 derniers mois
            for ($i = 11; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthName = date('M Y', strtotime("-$i months"));

                // Nouveaux utilisateurs
                $users = 0;
                if ($this->checkTableExists($this->tables['utilisateurs']) && $this->checkTableColumns($this->tables['utilisateurs'], ['date_creation'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['utilisateurs'] . " WHERE DATE_FORMAT(date_creation, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $users = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table utilisateurs ou colonne date_creation manquante pour stats mensuelles.");
                }

                // Nouveaux contacts
                $contacts = 0;
                if ($this->checkTableExists($this->tables['contacte']) && $this->checkTableColumns($this->tables['contacte'], ['date_creation'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['contacte'] . " WHERE DATE_FORMAT(date_creation, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $contacts = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table contacte ou colonne date_creation manquante pour stats mensuelles.");
                }

                // Nouveaux membres
                $membres = 0;
                if ($this->checkTableExists($this->tables['membres']) && $this->checkTableColumns($this->tables['membres'], ['date_creation'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['membres'] . " WHERE DATE_FORMAT(date_creation, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $membres = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table membres ou colonne date_creation manquante pour stats mensuelles.");
                }

                // Nouveaux articles de blog
                $blogs = 0;
                if ($this->checkTableExists($this->tables['blog_posts']) && $this->checkTableColumns($this->tables['blog_posts'], ['created_at'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['blog_posts'] . " WHERE DATE_FORMAT(created_at, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $blogs = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table blog_posts ou colonne created_at manquante pour stats mensuelles.");
                }

                // Nouveaux témoignages
                $temoignages = 0;
                if ($this->checkTableExists($this->tables['temoignage']) && $this->checkTableColumns($this->tables['temoignage'], ['date_tem'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['temoignage'] . " WHERE DATE_FORMAT(date_tem, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $temoignages = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table temoignage ou colonne date_tem manquante pour stats mensuelles.");
                }

                // Nouveaux contact_requests
                $contact_requests = 0;
                if ($this->checkTableExists($this->tables['contact_requests']) && $this->checkTableColumns($this->tables['contact_requests'], ['date_creation'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['contact_requests'] . " WHERE DATE_FORMAT(date_creation, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $contact_requests = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table contact_requests ou colonne date_creation manquante pour stats mensuelles.");
                }

                // Nouvelles notifications
                $notifications = 0;
                if ($this->checkTableExists($this->tables['notifications']) && $this->checkTableColumns($this->tables['notifications'], ['created_at'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['notifications'] . " WHERE DATE_FORMAT(created_at, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $notifications = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table notifications ou colonne created_at manquante pour stats mensuelles.");
                }

                // Nouveaux rendez_vous
                $rendez_vous = 0;
                if ($this->checkTableExists($this->tables['rendez_vous']) && $this->checkTableColumns($this->tables['rendez_vous'], ['date_rdv'])) {
                    $query = "SELECT COUNT(*) as count FROM " . $this->tables['rendez_vous'] . " WHERE DATE_FORMAT(date_rdv, '%Y-%m') = :month";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':month', $month);
                    $stmt->execute();
                    $rendez_vous = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?: 0;
                } else {
                    error_log("Dashboard.php: Table rendez_vous ou colonne date_rdv manquante pour stats mensuelles.");
                }

                $monthlyData[] = [
                    'month' => $monthName,
                    'users' => $users,
                    'contacts' => $contacts,
                    'membres' => $membres,
                    'blogs' => $blogs,
                    'temoignages' => $temoignages,
                    'contact_requests' => $contact_requests,
                    'notifications' => $notifications,
                    'rendez_vous' => $rendez_vous
                ];
            }

            return $monthlyData;
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la récupération des statistiques mensuelles : " . $e->getMessage());
            return array_fill(0, 12, [
                'month' => 'N/A',
                'users' => 0,
                'contacts' => 0,
                'membres' => 0,
                'blogs' => 0,
                'temoignages' => 0,
                'contact_requests' => 0,
                'notifications' => 0,
                'rendez_vous' => 0
            ]);
        }
    }

    public function getTopStats() {
        try {
            $topStats = [];

            // Top 5 des rôles de membres
            if ($this->checkTableExists($this->tables['membres']) && $this->checkTableColumns($this->tables['membres'], ['role'])) {
                $query = "SELECT role, COUNT(*) as count 
                          FROM " . $this->tables['membres'] . " 
                          WHERE role IS NOT NULL AND role != '' 
                          GROUP BY role 
                          ORDER BY count DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['top_roles'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table membres ou colonne role manquante pour top stats.");
                $topStats['top_roles'] = [];
            }

            // Top 5 des sujets de contact
            if ($this->checkTableExists($this->tables['contacte']) && $this->checkTableColumns($this->tables['contacte'], ['subject'])) {
                $query = "SELECT subject, COUNT(*) as count 
                          FROM " . $this->tables['contacte'] . " 
                          WHERE subject IS NOT NULL AND subject != '' 
                          GROUP BY subject 
                          ORDER BY count DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['top_subjects'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table contacte ou colonne subject manquante pour top stats.");
                $topStats['top_subjects'] = [];
            }

            // Répartition des types d'utilisateurs
            if ($this->checkTableExists($this->tables['utilisateurs']) && $this->checkTableColumns($this->tables['utilisateurs'], ['Types'])) {
                $query = "SELECT Types, COUNT(*) as count 
                          FROM " . $this->tables['utilisateurs'] . " 
                          GROUP BY Types 
                          ORDER BY count DESC";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['user_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table utilisateurs ou colonne Types manquante pour top stats.");
                $topStats['user_types'] = [];
            }

            // Top catégories de blog
            if ($this->checkTableExists($this->tables['blog_posts']) && $this->checkTableColumns($this->tables['blog_posts'], ['category'])) {
                $query = "SELECT category, COUNT(*) as count 
                          FROM " . $this->tables['blog_posts'] . " 
                          WHERE category IS NOT NULL AND category != '' 
                          GROUP BY category 
                          ORDER BY count DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['top_blog_categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table blog_posts ou colonne category manquante pour top stats.");
                $topStats['top_blog_categories'] = [];
            }

            // Top rangs des témoignages
            if ($this->checkTableExists($this->tables['temoignage']) && $this->checkTableColumns($this->tables['temoignage'], ['rang'])) {
                $query = "SELECT rang, COUNT(*) as count 
                          FROM " . $this->tables['temoignage'] . " 
                          WHERE rang IS NOT NULL AND rang != '' 
                          GROUP BY rang 
                          ORDER BY count DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['top_temoignage_rangs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table temoignage ou colonne rang manquante pour top stats.");
                $topStats['top_temoignage_rangs'] = [];
            }

            // Top types de contact_requests
            if ($this->checkTableExists($this->tables['contact_requests']) && $this->checkTableColumns($this->tables['contact_requests'], ['request_type'])) {
                $query = "SELECT request_type, COUNT(*) as count 
                          FROM " . $this->tables['contact_requests'] . " 
                          WHERE request_type IS NOT NULL AND request_type != '' 
                          GROUP BY request_type 
                          ORDER BY count DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['top_contact_request_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table contact_requests ou colonne request_type manquante pour top stats.");
                $topStats['top_contact_request_types'] = [];
            }

            // Top types de notifications
            if ($this->checkTableExists($this->tables['notifications']) && $this->checkTableColumns($this->tables['notifications'], ['type'])) {
                $query = "SELECT type, COUNT(*) as count 
                          FROM " . $this->tables['notifications'] . " 
                          WHERE type IS NOT NULL AND type != '' 
                          GROUP BY type 
                          ORDER BY count DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['top_notification_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table notifications ou colonne type manquante pour top stats.");
                $topStats['top_notification_types'] = [];
            }

            // Top statuts de rendez_vous
            if ($this->checkTableExists($this->tables['rendez_vous']) && $this->checkTableColumns($this->tables['rendez_vous'], ['status'])) {
                $query = "SELECT status, COUNT(*) as count 
                          FROM " . $this->tables['rendez_vous'] . " 
                          WHERE status IS NOT NULL AND status != '' 
                          GROUP BY status 
                          ORDER BY count DESC 
                          LIMIT 5";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $topStats['top_rendez_vous_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table rendez_vous ou colonne status manquante pour top stats.");
                $topStats['top_rendez_vous_status'] = [];
            }

            return $topStats;
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la récupération des top statistiques : " . $e->getMessage());
            return [
                'top_roles' => [],
                'top_subjects' => [],
                'user_types' => [],
                'top_blog_categories' => [],
                'top_temoignage_rangs' => [],
                'top_contact_request_types' => [],
                'top_notification_types' => [],
                'top_rendez_vous_status' => []
            ];
        }
    }

    public function getSystemHealth() {
        try {
            $health = [];

            // Taille de la base de données
            $query = "SELECT 
                        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS db_size_mb
                      FROM information_schema.tables 
                      WHERE table_schema = DATABASE()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $health['db_size'] = $stmt->fetch(PDO::FETCH_ASSOC)['db_size_mb'] ?: 0;

            // Nombre de tables
            $query = "SELECT COUNT(*) as table_count 
                      FROM information_schema.tables 
                      WHERE table_schema = DATABASE()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $health['table_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['table_count'] ?: 0;

            // Dernière activité
            $query = "SELECT MAX(date_creation) as last_activity FROM (
                        SELECT date_creation FROM " . $this->tables['utilisateurs'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['utilisateurs'] . "')
                        UNION ALL
                        SELECT date_creation FROM " . $this->tables['contacte'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['contacte'] . "')
                        UNION ALL
                        SELECT date_creation FROM " . $this->tables['membres'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['membres'] . "')
                        UNION ALL
                        SELECT created_at as date_creation FROM " . $this->tables['blog_posts'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['blog_posts'] . "')
                        UNION ALL
                        SELECT date_tem as date_creation FROM " . $this->tables['temoignage'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['temoignage'] . "')
                        UNION ALL
                        SELECT date_creation FROM " . $this->tables['contact_requests'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['contact_requests'] . "')
                        UNION ALL
                        SELECT created_at as date_creation FROM " . $this->tables['notifications'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['notifications'] . "')
                        UNION ALL
                        SELECT date_rdv as date_creation FROM " . $this->tables['rendez_vous'] . " WHERE EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = '" . $this->tables['rendez_vous'] . "')
                      ) as all_activities";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $health['last_activity'] = $stmt->fetch(PDO::FETCH_ASSOC)['last_activity'] ?: null;

            // Uptime
            $query = "SHOW STATUS LIKE 'Uptime'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $uptime = $stmt->fetch(PDO::FETCH_ASSOC);
            $health['uptime_days'] = $uptime ? round($uptime['Value'] / 86400, 1) : 0;

            // Connexions actives
            $query = "SHOW STATUS LIKE 'Threads_connected'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $connections = $stmt->fetch(PDO::FETCH_ASSOC);
            $health['active_connections'] = $connections ? $connections['Value'] : 0;

            return $health;
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la récupération de l'état du système : " . $e->getMessage());
            return [
                'db_size' => 0,
                'table_count' => 0,
                'last_activity' => null,
                'uptime_days' => 0,
                'active_connections' => 0
            ];
        }
    }

    public function getDetailedStats() {
        try {
            $stats = [];

            // Statistiques par statut d'utilisateur
            if ($this->checkTableExists($this->tables['utilisateurs']) && $this->checkTableColumns($this->tables['utilisateurs'], ['status'])) {
                $query = "SELECT 
                            status,
                            COUNT(*) as count,
                            ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM " . $this->tables['utilisateurs'] . "), 1) as percentage
                          FROM " . $this->tables['utilisateurs'] . " 
                          GROUP BY status";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['user_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table utilisateurs ou colonne status manquante pour stats détaillées.");
                $stats['user_status'] = [];
            }

            // Statistiques des réponses aux contacts
            if ($this->checkTableExists($this->tables['contacte']) && $this->checkTableColumns($this->tables['contacte'], ['response'])) {
                $query = "SELECT 
                            COALESCE(response, 'En attente') as response_type,
                            COUNT(*) as count
                          FROM " . $this->tables['contacte'] . " 
                          GROUP BY response";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['contact_responses'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table contacte ou colonne response manquante pour stats détaillées.");
                $stats['contact_responses'] = [];
            }

            // Évolution hebdomadaire des utilisateurs
            if ($this->checkTableExists($this->tables['utilisateurs']) && $this->checkTableColumns($this->tables['utilisateurs'], ['date_creation'])) {
                $query = "SELECT 
                            WEEK(date_creation) as week_num,
                            COUNT(*) as count,
                            'users' as type
                          FROM " . $this->tables['utilisateurs'] . " 
                          WHERE date_creation >= DATE_SUB(NOW(), INTERVAL 8 WEEK)
                          GROUP BY WEEK(date_creation)
                          ORDER BY week_num DESC
                          LIMIT 8";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['weekly_users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table utilisateurs ou colonne date_creation manquante pour stats hebdomadaires.");
                $stats['weekly_users'] = [];
            }

            // Évolution hebdomadaire des témoignages
            if ($this->checkTableExists($this->tables['temoignage']) && $this->checkTableColumns($this->tables['temoignage'], ['date_tem'])) {
                $query = "SELECT 
                            WEEK(date_tem) as week_num,
                            COUNT(*) as count,
                            'temoignages' as type
                          FROM " . $this->tables['temoignage'] . " 
                          WHERE date_tem >= DATE_SUB(NOW(), INTERVAL 8 WEEK)
                          GROUP BY WEEK(date_tem)
                          ORDER BY week_num DESC
                          LIMIT 8";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['weekly_temoignages'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table temoignage ou colonne date_tem manquante pour stats hebdomadaires.");
                $stats['weekly_temoignages'] = [];
            }

            // Statistiques des contact_requests par statut
            if ($this->checkTableExists($this->tables['contact_requests']) && $this->checkTableColumns($this->tables['contact_requests'], ['status'])) {
                $query = "SELECT 
                            COALESCE(status, 'En attente') as status,
                            COUNT(*) as count
                          FROM " . $this->tables['contact_requests'] . " 
                          GROUP BY status";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['contact_request_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table contact_requests ou colonne status manquante pour stats détaillées.");
                $stats['contact_request_status'] = [];
            }

            // Statistiques des notifications par type
            if ($this->checkTableExists($this->tables['notifications']) && $this->checkTableColumns($this->tables['notifications'], ['type'])) {
                $query = "SELECT 
                            type,
                            COUNT(*) as count
                          FROM " . $this->tables['notifications'] . " 
                          GROUP BY type";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['notification_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table notifications ou colonne type manquante pour stats détaillées.");
                $stats['notification_types'] = [];
            }

            // Évolution hebdomadaire des rendez_vous
            if ($this->checkTableExists($this->tables['rendez_vous']) && $this->checkTableColumns($this->tables['rendez_vous'], ['date_rdv'])) {
                $query = "SELECT 
                            WEEK(date_rdv) as week_num,
                            COUNT(*) as count,
                            'rendez_vous' as type
                          FROM " . $this->tables['rendez_vous'] . " 
                          WHERE date_rdv >= DATE_SUB(NOW(), INTERVAL 8 WEEK)
                          GROUP BY WEEK(date_rdv)
                          ORDER BY week_num DESC
                          LIMIT 8";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $stats['weekly_rendez_vous'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                error_log("Dashboard.php: Table rendez_vous ou colonne date_rdv manquante pour stats hebdomadaires.");
                $stats['weekly_rendez_vous'] = [];
            }

            return $stats;
        } catch (PDOException $e) {
            error_log("Dashboard.php: Erreur lors de la récupération des statistiques détaillées : " . $e->getMessage());
            return [
                'user_status' => [],
                'contact_responses' => [],
                'weekly_users' => [],
                'weekly_temoignages' => [],
                'contact_request_status' => [],
                'notification_types' => [],
                'weekly_rendez_vous' => []
            ];
        }
    }
}
?>