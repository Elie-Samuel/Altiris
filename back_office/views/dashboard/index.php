<?php
session_start();
require_once dirname(__DIR__, 2) . '/components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/DashboardController.php';

try {
    $controller = new DashboardController();
    $dashboardData = $controller->index();
    $detailedStats = $controller->getDetailedStats();

    $generalStats = $dashboardData['generalStats'] ?? [];
    $recentActivities = $dashboardData['recentActivities'] ?? [];
    $monthlyStats = $dashboardData['monthlyStats'] ?? [];
    $topStats = $dashboardData['topStats'] ?? [];
    $systemHealth = $dashboardData['systemHealth'] ?? [];

} catch (Exception $e) {
    error_log("Erreur dashboard: " . $e->getMessage());
    $generalStats = [
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
    ];
    $recentActivities = [];
    $monthlyStats = array_fill(0, 12, ['month' => 'N/A', 'users' => 0, 'contacts' => 0, 'membres' => 0, 'blogs' => 0, 'temoignages' => 0, 'contact_requests' => 0, 'notifications' => 0]);
    $topStats = ['top_roles' => [], 'top_subjects' => [], 'user_types' => [], 'top_blog_categories' => [], 'top_temoignage_rangs' => [], 'top_contact_request_types' => [], 'top_notification_types' => []];
    $systemHealth = ['db_size' => 0, 'table_count' => 0, 'uptime_days' => 0, 'active_connections' => 0, 'cpu_usage' => 0, 'memory_usage' => 0];
    $detailedStats = ['user_status' => [], 'contact_responses' => [], 'weekly_users' => [], 'weekly_temoignages' => [], 'notification_types' => []];
}
?>

<style>
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--spacing-xl);
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-2xl);
    padding: var(--spacing-xl) 0;
    border-bottom: 2px solid var(--border-color);
}

.dashboard-header h1 {
    color: var(--text-primary);
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.dashboard-header h1 i {
    color: var(--altiris-orange);
}

.dashboard-actions {
    display: flex;
    gap: var(--spacing-md);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-2xl);
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    transition: all var(--transition-normal);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--altiris-orange) 0%, var(--altiris-blue) 100%);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: var(--spacing-lg);
    background: linear-gradient(135deg, var(--altiris-orange) 0%, var(--altiris-orange-light) 100%);
    color: var(--altiris-white);
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
    background: linear-gradient(135deg, var(--altiris-blue) 0%, var(--altiris-orange) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-content p {
    color: var(--text-secondary);
    font-weight: 500;
    margin-bottom: var(--spacing-sm);
}

.stat-change {
    font-size: 0.85rem;
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--radius-md);
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.stat-change.positive {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.stat-change.negative {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-2xl);
}

.chart-container {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    box-shadow: var(--shadow-md);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border-color);
}

.chart-header h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.chart-header h3 i {
    color: var(--altiris-orange);
}

.chart-canvas {
    position: relative;
    height: 300px;
    width: 100%;
}

.dashboard-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--spacing-xl);
}

.widget {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: all var(--transition-normal);
}

.widget:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.widget-header {
    background: linear-gradient(135deg, var(--altiris-blue) 0%, var(--altiris-blue-light) 100%);
    padding: var(--spacing-lg);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.widget-header h3 {
    margin: 0;
    color: var(--altiris-white);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.widget-header h3 i {
    color: var(--altiris-orange);
}

.widget-content {
    padding: var(--spacing-lg);
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md) 0;
    border-bottom: 1px solid var(--border-color);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: var(--altiris-white);
}

.activity-icon.user {
    background: var(--altiris-blue);
}

.activity-icon.contact {
    background: var(--altiris-orange);
}

.activity-icon.membre {
    background: var(--success);
}

.activity-icon.blog_post {
    background: var(--info);
}

.activity-icon.temoignage {
    background: #8b5cf6;
}

.activity-icon.contact_request {
    background: #8b5cf6;
}

.activity-icon.notification {
    background: #ef4444;
}

.activity-content {
    flex: 1;
}

.activity-content h4 {
    margin: 0 0 var(--spacing-xs) 0;
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 600;
}

.activity-content p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.8rem;
}

.activity-time {
    font-size: 0.75rem;
    color: var(--text-muted);
    white-space: nowrap;
}

@media (max-width: 1200px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: var(--spacing-lg);
        text-align: center;
    }
    
    .dashboard-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-widgets {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h1>
        <div class="dashboard-actions">
            <button class="btn btn-primary" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i> Actualiser
            </button>
            <button class="btn btn-secondary" onclick="exportStats()">
                <i class="fas fa-download"></i> Exporter
            </button>
        </div>
    </div>

    <!-- Main Statistics Grid -->
    <div class="stats-grid">
        <!-- Users Stats -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo htmlspecialchars($generalStats['users']['total_users'] ?? 0); ?></h3>
                <p>Utilisateurs Total</p>
                <span class="stat-change positive">
                    +<?php echo htmlspecialchars($generalStats['users']['new_users_month'] ?? 0); ?> ce mois
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo htmlspecialchars($generalStats['users']['active_users'] ?? 0); ?></h3>
                <p>Utilisateurs Actifs</p>
                <span class="stat-change">
                    <?php 
                    $total = $generalStats['users']['total_users'] ?? 1;
                    $active = $generalStats['users']['active_users'] ?? 0;
                    echo htmlspecialchars(round(($active / max(1, $total)) * 100, 1)); 
                    ?>% du total
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo htmlspecialchars($generalStats['contacts']['new_contacts'] ?? 0); ?></h3>
                <p>Nouveaux Messages</p>
                <span class="stat-change">
                    <?php echo htmlspecialchars($generalStats['contacts']['total_contacts'] ?? 0); ?> total
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo htmlspecialchars($generalStats['membres']['total_membres'] ?? 0); ?></h3>
                <p>Membres</p>
                <span class="stat-change positive">
                    +<?php echo htmlspecialchars($generalStats['membres']['new_membres_month'] ?? 0); ?> ce mois
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo htmlspecialchars($generalStats['annonces']['total_annonces'] ?? 0); ?></h3>
                <p>Annonces</p>
                <span class="stat-change">Publiées</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-cogs"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo htmlspecialchars($generalStats['services']['total_services'] ?? 0); ?></h3>
                <p>Services</p>
                <span class="stat-change">Disponibles</span>
            </div>
        </div>

        <!-- Notifications Stats -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo htmlspecialchars($generalStats['notifications']['total_notifications'] ?? 0); ?></h3>
                <p>Notifications</p>
                <span class="stat-change">
                    <?php echo htmlspecialchars($generalStats['notifications']['unread_notifications'] ?? 0); ?> non lues
                </span>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Monthly Evolution Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Évolution Mensuelle</h3>
            </div>
            <div class="chart-canvas">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- User Types Distribution -->
        <div class="chart-container">
            <div class="chart-header">
                <h3><i class="fas fa-chart-pie"></i> Répartition des Types d'Utilisateurs</h3>
            </div>
            <div class="chart-canvas">
                <canvas id="userTypesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Dashboard Widgets -->
    <div class="dashboard-widgets">
        <!-- Recent Activities -->
        <div class="widget">
            <div class="widget-header">
                <h3><i class="fas fa-stream"></i> Activités Récentes</h3>
            </div>
            <div class="widget-content">
                <div class="activity-list">
                    <?php foreach ($recentActivities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon <?php echo htmlspecialchars($activity['type']); ?>">
                                <i class="fas fa-<?php 
                                    switch($activity['type']) {
                                        case 'user': echo 'user'; break;
                                        case 'contact': echo 'envelope'; break;
                                        case 'membre': echo 'user-friends'; break;
                                        case 'blog_post': echo 'blog'; break;
                                        case 'temoignage': echo 'comment'; break;
                                        case 'contact_request': echo 'handshake'; break;
                                        case 'notification': echo 'bell'; break;
                                        default: echo 'circle';
                                    }
                                ?>"></i>
                            </div>
                            <div class="activity-content">
                                <h4><?php echo htmlspecialchars($activity['name'] ?? 'Inconnu'); ?></h4>
                                <p><?php echo htmlspecialchars($activity['email'] ?? 'N/A'); ?></p>
                                <?php if (isset($activity['subject'])): ?>
                                    <p><?php echo htmlspecialchars($activity['subject']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="activity-time">
                                <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($activity['date_creation']))); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="widget">
            <div class="widget-header">
                <h3><i class="fas fa-heartbeat"></i> État du Système</h3>
            </div>
            <div class="widget-content">
                <p><strong>Taille de la Base de Données :</strong> <?php echo htmlspecialchars($systemHealth['db_size'] ?? 0); ?> MB</p>
                <p><strong>Nombre de Tables :</strong> <?php echo htmlspecialchars($systemHealth['table_count'] ?? 0); ?></p>
                <p><strong>Temps d'Activité :</strong> <?php echo htmlspecialchars($systemHealth['uptime_days'] ?? 0); ?> jours</p>
                <p><strong>Connexions Actives :</strong> <?php echo htmlspecialchars($systemHealth['active_connections'] ?? 0); ?></p>
                <p><strong>Utilisation CPU :</strong> <?php echo htmlspecialchars($systemHealth['cpu_usage'] ?? 0); ?>%</p>
                <p><strong>Utilisation Mémoire :</strong> <?php echo htmlspecialchars($systemHealth['memory_usage'] ?? 0); ?>%</p>
                <?php if (isset($systemHealth['last_activity'])): ?>
                    <p><strong>Dernière Activité :</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($systemHealth['last_activity']))); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top Stats -->
        <div class="widget">
            <div class="widget-header">
                <h3><i class="fas fa-star"></i> Statistiques Clés</h3>
            </div>
            <div class="widget-content">
                <h4>Top Rôles des Membres</h4>
                <ul>
                    <?php foreach ($topStats['top_roles'] as $role): ?>
                        <li><?php echo htmlspecialchars($role['role'] ?? 'N/A'); ?>: <?php echo htmlspecialchars($role['count'] ?? 0); ?></li>
                    <?php endforeach; ?>
                </ul>
                <h4>Top Sujets de Contact</h4>
                <ul>
                    <?php foreach ($topStats['top_subjects'] as $subject): ?>
                        <li><?php echo htmlspecialchars($subject['subject'] ?? 'N/A'); ?>: <?php echo htmlspecialchars($subject['count'] ?? 0); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Evolution Chart
    const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($monthlyStats, 'month')); ?>,
            datasets: [
                {
                    label: 'Utilisateurs',
                    data: <?php echo json_encode(array_column($monthlyStats, 'users')); ?>,
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                },
                {
                    label: 'Contacts',
                    data: <?php echo json_encode(array_column($monthlyStats, 'contacts')); ?>,
                    borderColor: 'rgb(249, 115, 22)',
                    tension: 0.1
                },
                {
                    label: 'Membres',
                    data: <?php echo json_encode(array_column($monthlyStats, 'membres')); ?>,
                    borderColor: 'rgb(16, 185, 129)',
                    tension: 0.1
                },
                {
                    label: 'Articles de Blog',
                    data: <?php echo json_encode(array_column($monthlyStats, 'blogs')); ?>,
                    borderColor: 'rgb(139, 92, 246)',
                    tension: 0.1
                },
                {
                    label: 'Témoignages',
                    data: <?php echo json_encode(array_column($monthlyStats, 'temoignages')); ?>,
                    borderColor: 'rgb(236, 72, 153)',
                    tension: 0.1
                },
                {
                    label: 'Demandes de Contact',
                    data: <?php echo json_encode(array_column($monthlyStats, 'contact_requests')); ?>,
                    borderColor: 'rgb(251, 191, 36)',
                    tension: 0.1
                },
                {
                    label: 'Notifications',
                    data: <?php echo json_encode(array_column($monthlyStats, 'notifications')); ?>,
                    borderColor: 'rgb(239, 68, 68)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // User Types Distribution Chart
    const userTypesChart = new Chart(document.getElementById('userTypesChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($topStats['user_types'], 'Types')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($topStats['user_types'], 'count')); ?>,
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(249, 115, 22)',
                    'rgb(16, 185, 129)',
                    'rgb(139, 92, 246)',
                    'rgb(236, 72, 153)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    function refreshDashboard() {
        window.location.reload();
    }

    function exportStats() {
        const data = {
            generalStats: <?php echo json_encode($generalStats); ?>,
            monthlyStats: <?php echo json_encode($monthlyStats); ?>,
            topStats: <?php echo json_encode($topStats); ?>,
            systemHealth: <?php echo json_encode($systemHealth); ?>
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'dashboard_stats.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>