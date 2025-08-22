<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/NotificationController.php';

$controller = new NotificationController();
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

$notifications = $controller->getUserNotifications($_SESSION['user_id'], $perPage, $offset);
$unreadCount = $controller->getUnreadCount($_SESSION['user_id']);

// Actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'mark_all_read':
            $controller->markAllAsRead($_SESSION['user_id']);
            header("Location: index.php");
            exit;
        case 'clean_old':
            $controller->cleanOldNotifications();
            $_SESSION['success'] = "Anciennes notifications supprimées";
            header("Location: index.php");
            exit;
    }
}
?>

<link rel="stylesheet" href="/Altiris/back_office/css/style.css">

<div class="page-header">
    <h2><i class="fas fa-bell"></i> Centre de Notifications</h2>
    <div class="header-actions">
        <?php if ($unreadCount > 0): ?>
        <a href="?action=mark_all_read" class="btn btn-primary">
            <i class="fas fa-check-double"></i> Marquer tout comme lu (<?php echo $unreadCount; ?>)
        </a>
        <?php endif; ?>
        <a href="?action=clean_old" class="btn btn-secondary" onclick="return confirm('Supprimer les notifications de plus de 30 jours ?')">
            <i class="fas fa-broom"></i> Nettoyer
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- Statistiques des notifications -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-number"><?php echo count($notifications); ?></div>
        <div class="stat-label">Notifications Récentes</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo $unreadCount; ?></div>
        <div class="stat-label">Non Lues</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?php echo count($notifications) - $unreadCount; ?></div>
        <div class="stat-label">Lues</div>
    </div>
</div>

<div class="notifications-container">
    <?php if (empty($notifications)): ?>
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <h3>Aucune notification</h3>
            <p>Vous n'avez aucune notification pour le moment</p>
        </div>
    <?php else: ?>
        <div class="notifications-list">
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-card <?php echo !$notification['is_read'] ? 'unread' : ''; ?>">
                    <div class="notification-icon <?php echo $notification['type']; ?>">
                        <i class="fas <?php echo getNotificationIcon($notification['type']); ?>"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-header">
                            <h4 class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></h4>
                            <span class="notification-time"><?php echo timeAgo($notification['created_at']); ?></span>
                        </div>
                        <p class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></p>
                        <?php if ($notification['sender_name']): ?>
                            <div class="notification-sender">
                                <i class="fas fa-user"></i> Par <?php echo htmlspecialchars($notification['sender_name']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($notification['data']): ?>
                            <div class="notification-data">
                                <?php 
                                $data = json_decode($notification['data'], true);
                                if ($data && isset($data['contact_name'])): ?>
                                    <div class="data-item">
                                        <strong>Contact:</strong> <?php echo htmlspecialchars($data['contact_name']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!$notification['is_read']): ?>
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-primary" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                                <i class="fas fa-check"></i> Marquer comme lu
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.notifications-container {
    margin-top: 20px;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.notification-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(249, 115, 22, 0.2);
    display: flex;
    align-items: flex-start;
    gap: 15px;
    transition: all 0.3s ease;
}

.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(249, 115, 22, 0.2);
}

.notification-card.unread {
    background: rgba(30, 64, 175, 0.1);
    border-left: 4px solid var(--altiris-orange);
}

.notification-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.notification-icon.info {
    background: rgba(14, 165, 233, 0.2);
    color: #0ea5e9;
}

.notification-icon.success {
    background: rgba(16, 185, 129, 0.2);
    color: var(--altiris-success);
}

.notification-icon.warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.notification-icon.error {
    background: rgba(239, 68, 68, 0.2);
    color: var(--altiris-danger);
}

.notification-icon.message,
.notification-icon.appointment {
    background: rgba(249, 115, 22, 0.2);
    color: var(--altiris-orange);
}

.notification-icon.user {
    background: rgba(30, 64, 175, 0.2);
    color: var(--altiris-blue);
}

.notification-content {
    flex: 1;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.notification-title {
    color: var(--altiris-white);
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.notification-time {
    color: rgba(255, 255, 255, 0.6);
    font-size: 12px;
    white-space: nowrap;
}

.notification-message {
    color: rgba(255, 255, 255, 0.8);
    margin: 0 0 10px 0;
    line-height: 1.5;
}

.notification-sender {
    color: rgba(255, 255, 255, 0.6);
    font-size: 12px;
    margin-bottom: 5px;
}

.notification-data {
    background: rgba(255, 255, 255, 0.05);
    padding: 10px;
    border-radius: 8px;
    margin-top: 10px;
}

.data-item {
    color: rgba(255, 255, 255, 0.8);
    font-size: 13px;
    margin-bottom: 5px;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

@media (max-width: 768px) {
    .notification-card {
        flex-direction: column;
        text-align: center;
    }
    
    .notification-header {
        flex-direction: column;
        gap: 5px;
        text-align: center;
    }
    
    .notification-actions {
        flex-direction: row;
        justify-content: center;
    }
}
</style>

<script>
async function markAsRead(notificationId) {
    try {
        const response = await fetch('/Altiris/back_office/api/notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'mark_read',
                notification_id: notificationId
            })
        });

        const data = await response.json();
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// Auto-dismiss alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.querySelector('.btn-close')) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }
    });
}, 5000);
</script>

<?php
function getNotificationIcon($type) {
    $icons = [
        'info' => 'fa-info-circle',
        'success' => 'fa-check-circle',
        'warning' => 'fa-exclamation-triangle',
        'error' => 'fa-times-circle',
        'message' => 'fa-envelope',
        'appointment' => 'fa-calendar-alt',
        'user' => 'fa-user'
    ];
    return $icons[$type] ?? 'fa-bell';
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'À l\'instant';
    if ($time < 3600) return floor($time/60) . ' min';
    if ($time < 86400) return floor($time/3600) . ' h';
    if ($time < 2592000) return floor($time/86400) . ' j';
    
    return date('d/m/Y', strtotime($datetime));
}
?>