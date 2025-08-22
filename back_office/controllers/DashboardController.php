<?php
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__) . '/models/Dashboard.php';

class DashboardController {
    private $dashboardModel;

    public function __construct() {
        try {
            $db = new Database();
            $this->dashboardModel = new Dashboard($db->getConnection());
        } catch (Exception $e) {
            error_log("DashboardController.php: Erreur de connexion à la base de données : " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
    }

    public function index() {
        try {
            $generalStats = $this->dashboardModel->getGeneralStats();
            $recentActivities = $this->dashboardModel->getRecentActivities();
            $monthlyStats = $this->dashboardModel->getMonthlyStats();
            $topStats = $this->dashboardModel->getTopStats();
            $systemHealth = $this->dashboardModel->getSystemHealth();

            return [
                'generalStats' => $generalStats,
                'recentActivities' => $recentActivities,
                'monthlyStats' => $monthlyStats,
                'topStats' => $topStats,
                'systemHealth' => $systemHealth
            ];
        } catch (Exception $e) {
            error_log("DashboardController.php: Erreur dans index : " . $e->getMessage());
            return [
                'generalStats' => [
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
                    'parametres' => ['total_parametres' => 0]
                ],
                'recentActivities' => [],
                'monthlyStats' => array_fill(0, 12, [
                    'month' => 'N/A',
                    'users' => 0,
                    'contacts' => 0,
                    'membres' => 0,
                    'blogs' => 0,
                    'temoignages' => 0,
                    'contact_requests' => 0,
                    'notifications' => 0
                ]),
                'topStats' => [
                    'top_roles' => [],
                    'top_subjects' => [],
                    'user_types' => [],
                    'top_blog_categories' => [],
                    'top_temoignage_rangs' => [],
                    'top_contact_request_types' => [],
                    'top_notification_types' => []
                ],
                'systemHealth' => [
                    'db_size' => 0,
                    'table_count' => 0,
                    'uptime_days' => 0,
                    'active_connections' => 0,
                    'cpu_usage' => 0,
                    'memory_usage' => 0
                ]
            ];
        }
    }

    public function getDetailedStats() {
        try {
            return $this->dashboardModel->getDetailedStats();
        } catch (Exception $e) {
            error_log("DashboardController.php: Erreur dans getDetailedStats : " . $e->getMessage());
            return [
                'user_status' => [],
                'contact_responses' => [],
                'weekly_users' => [],
                'weekly_temoignages' => [],
                'contact_request_status' => [],
                'notification_types' => []
            ];
        }
    }
}
?>