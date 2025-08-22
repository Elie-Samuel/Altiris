// Système de notifications pour Altiris Back Office

class NotificationSystem {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.init();
        this.loadNotifications();
        this.startPolling();
    }

    init() {
        this.createNotificationHTML();
        this.bindEvents();
    }

    createNotificationHTML() {
        // Créer le conteneur de notifications dans la navbar
        const navbar = document.querySelector('.navbar .d-flex:last-child');
        if (navbar) {
            const notificationHTML = `
                <div class="notification-system">
                    <div class="notification-bell" id="notificationBell">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h3>Notifications</h3>
                            <a href="#" class="mark-all-read" id="markAllRead">Tout marquer comme lu</a>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="empty-notifications">
                                <i class="fas fa-bell-slash"></i>
                                <p>Aucune notification</p>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="#" class="view-all-notifications">Voir toutes les notifications</a>
                        </div>
                    </div>
                </div>
            `;
            
            // Insérer avant l'admin-info
            const adminInfo = navbar.querySelector('.admin-info');
            adminInfo.insertAdjacentHTML('beforebegin', notificationHTML);
        }

        // Créer le conteneur de toast
        if (!document.querySelector('.toast-container')) {
            document.body.insertAdjacentHTML('beforeend', '<div class="toast-container" id="toastContainer"></div>');
        }
    }

    bindEvents() {
        const bell = document.getElementById('notificationBell');
        const dropdown = document.getElementById('notificationDropdown');
        const markAllRead = document.getElementById('markAllRead');

        if (bell && dropdown) {
            bell.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });

            document.addEventListener('click', (e) => {
                if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }

        if (markAllRead) {
            markAllRead.addEventListener('click', (e) => {
                e.preventDefault();
                this.markAllAsRead();
            });
        }
    }

    async loadNotifications() {
        try {
            const response = await fetch('/Altiris/back_office/api/notifications.php');
            const data = await response.json();
            
            if (data.success) {
                this.notifications = data.notifications;
                this.updateNotificationDisplay();
            }
        } catch (error) {
            console.error('Erreur lors du chargement des notifications:', error);
        }
    }

    updateNotificationDisplay() {
        const badge = document.getElementById('notificationBadge');
        const list = document.getElementById('notificationList');
        
        if (!badge || !list) return;

        // Compter les notifications non lues
        this.unreadCount = this.notifications.filter(n => !n.read).length;
        
        // Mettre à jour le badge
        if (this.unreadCount > 0) {
            badge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }

        // Mettre à jour la liste
        if (this.notifications.length === 0) {
            list.innerHTML = `
                <div class="empty-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <p>Aucune notification</p>
                </div>
            `;
        } else {
            list.innerHTML = this.notifications.map(notification => this.createNotificationItem(notification)).join('');
        }
    }

    createNotificationItem(notification) {
        const timeAgo = this.getTimeAgo(notification.created_at);
        const iconClass = this.getIconClass(notification.type);
        
        return `
            <div class="notification-item ${!notification.read ? 'unread' : ''}" data-id="${notification.id}">
                <div style="display: flex; align-items: flex-start;">
                    <div class="notification-icon ${notification.type}">
                        <i class="fas ${iconClass}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-time">${timeAgo}</div>
                    </div>
                </div>
            </div>
        `;
    }

    getIconClass(type) {
        const icons = {
            'info': 'fa-info-circle',
            'success': 'fa-check-circle',
            'warning': 'fa-exclamation-triangle',
            'error': 'fa-times-circle',
            'message': 'fa-envelope',
            'appointment': 'fa-calendar-alt',
            'user': 'fa-user'
        };
        return icons[type] || 'fa-bell';
    }

    getTimeAgo(dateString) {
        const now = new Date();
        const date = new Date(dateString);
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'À l\'instant';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} min`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} h`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} j`;
        return date.toLocaleDateString('fr-FR');
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/Altiris/back_office/api/notifications.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'mark_all_read' })
            });

            const data = await response.json();
            if (data.success) {
                this.notifications.forEach(n => n.read = true);
                this.updateNotificationDisplay();
                this.showToast('Toutes les notifications ont été marquées comme lues', 'success');
            }
        } catch (error) {
            console.error('Erreur lors du marquage des notifications:', error);
            this.showToast('Erreur lors du marquage des notifications', 'error');
        }
    }

    showToast(message, type = 'info', title = null, duration = 5000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toastId = 'toast_' + Date.now();
        const iconClass = this.getIconClass(type);
        
        const toastHTML = `
            <div class="toast ${type}" id="${toastId}">
                <div class="toast-header">
                    <i class="toast-icon fas ${iconClass}"></i>
                    <span class="toast-title">${title || this.getDefaultTitle(type)}</span>
                    <button class="toast-close" onclick="notificationSystem.removeToast('${toastId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="toast-message">${message}</div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', toastHTML);

        // Auto-remove après la durée spécifiée
        setTimeout(() => {
            this.removeToast(toastId);
        }, duration);
    }

    getDefaultTitle(type) {
        const titles = {
            'info': 'Information',
            'success': 'Succès',
            'warning': 'Attention',
            'error': 'Erreur'
        };
        return titles[type] || 'Notification';
    }

    removeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    addNotification(notification) {
        this.notifications.unshift(notification);
        this.updateNotificationDisplay();
        
        // Afficher un toast pour les nouvelles notifications importantes
        if (['error', 'warning', 'message'].includes(notification.type)) {
            this.showToast(notification.message, notification.type, notification.title);
        }
    }

    startPolling() {
        // Vérifier les nouvelles notifications toutes les 30 secondes
        setInterval(() => {
            this.checkForNewNotifications();
        }, 30000);
    }

    async checkForNewNotifications() {
        try {
            const lastCheck = localStorage.getItem('lastNotificationCheck') || '0';
            const response = await fetch(`/Altiris/back_office/api/notifications.php?since=${lastCheck}`);
            const data = await response.json();
            
            if (data.success && data.notifications.length > 0) {
                data.notifications.forEach(notification => {
                    this.addNotification(notification);
                });
                localStorage.setItem('lastNotificationCheck', Date.now().toString());
            }
        } catch (error) {
            console.error('Erreur lors de la vérification des nouvelles notifications:', error);
        }
    }

    // Méthodes utilitaires pour les autres scripts
    static showSuccess(message, title = 'Succès') {
        if (window.notificationSystem) {
            window.notificationSystem.showToast(message, 'success', title);
        }
    }

    static showError(message, title = 'Erreur') {
        if (window.notificationSystem) {
            window.notificationSystem.showToast(message, 'error', title);
        }
    }

    static showWarning(message, title = 'Attention') {
        if (window.notificationSystem) {
            window.notificationSystem.showToast(message, 'warning', title);
        }
    }

    static showInfo(message, title = 'Information') {
        if (window.notificationSystem) {
            window.notificationSystem.showToast(message, 'info', title);
        }
    }
}

// Animation CSS pour slideOutRight
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
`;
document.head.appendChild(style);

// Initialiser le système de notifications quand le DOM est prêt
document.addEventListener('DOMContentLoaded', function() {
    window.notificationSystem = new NotificationSystem();
});

// Exporter pour utilisation globale
window.NotificationSystem = NotificationSystem;