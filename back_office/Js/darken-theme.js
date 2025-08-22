// Darken Theme JavaScript for Altiris Back Office

class DarkenTheme {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeDropdowns();
        this.initializeSearch();
        this.updateActiveNavigation();
        this.initializeNotifications();
        this.initializeResponsive();
    }

    bindEvents() {
        // Sidebar toggle
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        }

        // Sidebar overlay
        const sidebarOverlay = document.querySelector('.sidebar-overlay');
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => this.closeSidebar());
        }

        // Sidebar close button
        const sidebarClose = document.querySelector('.sidebar-close');
        if (sidebarClose) {
            sidebarClose.addEventListener('click', () => this.closeSidebar());
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            this.closeDropdowns(e);
        });

        // Escape key to close dropdowns and sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllDropdowns();
                this.closeSidebar();
            }
        });
    }

    toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.classList.toggle('sidebar-open');
        }
    }

    closeSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    }

    initializeDropdowns() {
        // Notification dropdown
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        if (notificationToggle && notificationDropdown) {
            notificationToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown(notificationDropdown);
            });
        }

        // User profile dropdown
        const userProfileToggle = document.getElementById('userProfileToggle');
        const userDropdown = document.getElementById('userDropdown');
        
        if (userProfileToggle && userDropdown) {
            userProfileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown(userDropdown);
            });
        }

        // Mark all notifications as read
        const markAllRead = document.getElementById('markAllRead');
        if (markAllRead) {
            markAllRead.addEventListener('click', () => this.markAllNotificationsAsRead());
        }
    }

    toggleDropdown(dropdown) {
        // Close other dropdowns first
        document.querySelectorAll('.notification-dropdown, .user-dropdown').forEach(dd => {
            if (dd !== dropdown) {
                dd.classList.remove('show');
            }
        });
        
        dropdown.classList.toggle('show');
    }

    closeDropdowns(e) {
        const dropdowns = document.querySelectorAll('.notification-dropdown, .user-dropdown');
        const toggles = document.querySelectorAll('#notificationToggle, #userProfileToggle');
        
        let clickedOnToggle = false;
        toggles.forEach(toggle => {
            if (toggle.contains(e.target)) {
                clickedOnToggle = true;
            }
        });

        if (!clickedOnToggle) {
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }
    }

    closeAllDropdowns() {
        document.querySelectorAll('.notification-dropdown, .user-dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }

    initializeSearch() {
        const searchInput = document.querySelector('.search-input');
        const searchClear = document.querySelector('.search-clear');
        const searchSuggestions = document.getElementById('searchSuggestions');

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const value = e.target.value.trim();
                
                if (value.length > 0) {
                    searchClear.style.display = 'block';
                    this.showSearchSuggestions(value);
                } else {
                    searchClear.style.display = 'none';
                    this.hideSearchSuggestions();
                }
            });

            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.performSearch(searchInput.value);
                }
            });
        }

        if (searchClear) {
            searchClear.addEventListener('click', () => {
                searchInput.value = '';
                searchClear.style.display = 'none';
                this.hideSearchSuggestions();
                searchInput.focus();
            });
        }
    }

    showSearchSuggestions(query) {
        const suggestions = document.getElementById('searchSuggestions');
        if (!suggestions) return;

        // Simuler des suggestions basées sur la navigation
        const navItems = [
            { text: 'Tableau de Bord', url: '/Altiris/back_office/views/dashboard/index.php', icon: 'fas fa-tachometer-alt' },
            { text: 'Annonces', url: '/Altiris/back_office/views/annonces/index.php', icon: 'fas fa-bullhorn' },
            { text: 'Services', url: '/Altiris/back_office/views/services/index.php', icon: 'fas fa-cogs' },
            { text: 'Membres', url: '/Altiris/back_office/views/membres/index.php', icon: 'fas fa-users' },
            { text: 'Notifications', url: '/Altiris/back_office/views/notifications/index.php', icon: 'fas fa-bell' },
            { text: 'Paramètres', url: '/Altiris/back_office/views/parametres/index.php', icon: 'fas fa-cog' }
        ];

        const filteredItems = navItems.filter(item => 
            item.text.toLowerCase().includes(query.toLowerCase())
        );

        if (filteredItems.length > 0) {
            suggestions.innerHTML = filteredItems.map(item => `
                <a href="${item.url}" class="search-suggestion-item">
                    <i class="${item.icon}"></i>
                    <span>${item.text}</span>
                </a>
            `).join('');
            suggestions.style.display = 'block';
        } else {
            this.hideSearchSuggestions();
        }
    }

    hideSearchSuggestions() {
        const suggestions = document.getElementById('searchSuggestions');
        if (suggestions) {
            suggestions.style.display = 'none';
        }
    }

    performSearch(query) {
        if (query.trim()) {
            // Rediriger vers une page de recherche ou filtrer le contenu actuel
            console.log('Recherche:', query);
            this.hideSearchSuggestions();
        }
    }

    updateActiveNavigation() {
        const currentPath = window.location.pathname;
        const navItems = document.querySelectorAll('.nav-item');
        
        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
            }
        });

        // Update breadcrumb
        this.updateBreadcrumb(currentPath);
    }

    updateBreadcrumb(path) {
        const currentPageElement = document.getElementById('current-page');
        if (!currentPageElement) return;

        const pageNames = {
            '/Altiris/back_office/views/dashboard/index.php': 'Tableau de Bord',
            '/Altiris/back_office/views/annonces/index.php': 'Annonces',
            '/Altiris/back_office/views/services/index.php': 'Services',
            '/Altiris/back_office/views/actualiters/index.php': 'Actualités',
            '/Altiris/back_office/views/membres/index.php': 'Membres',
            '/Altiris/back_office/views/contact_ent/index.php': 'Contact Entreprise',
            '/Altiris/back_office/views/contacte/index.php': 'Rendez-vous',
            '/Altiris/back_office/views/competences/index.php': 'Compétences',
            '/Altiris/back_office/views/temoignages/index.php': 'Témoignages',
            '/Altiris/back_office/views/notifications/index.php': 'Notifications',
            '/Altiris/back_office/views/parametres/index.php': 'Paramètres',
            '/Altiris/back_office/views/utilisateurs/index.php': 'Utilisateurs'
        };

        currentPageElement.textContent = pageNames[path] || 'Page';
    }

    initializeNotifications() {
        this.loadNotifications();
        this.startNotificationPolling();
    }

    async loadNotifications() {
        try {
            const response = await fetch('/Altiris/back_office/api/notifications.php');
            const data = await response.json();
            
            if (data.success) {
                this.updateNotificationUI(data.notifications, data.unread_count);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des notifications:', error);
        }
    }

    updateNotificationUI(notifications, unreadCount) {
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationCount = document.getElementById('notificationCount');
        const notificationList = document.getElementById('notificationList');

        // Update badges
        [notificationBadge, notificationCount].forEach(badge => {
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
        });

        // Update notification list
        if (notificationList) {
            if (notifications.length === 0) {
                notificationList.innerHTML = `
                    <div class="empty-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <p>Aucune notification</p>
                    </div>
                `;
            } else {
                notificationList.innerHTML = notifications.slice(0, 5).map(notification => 
                    this.createNotificationItem(notification)
                ).join('');
            }
        }
    }

    createNotificationItem(notification) {
        const timeAgo = this.getTimeAgo(notification.created_at);
        const iconClass = this.getNotificationIcon(notification.type);
        
        return `
            <div class="notification-item ${!notification.read ? 'unread' : ''}" data-id="${notification.id}">
                <div class="notification-icon ${notification.type}">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">${timeAgo}</div>
                </div>
            </div>
        `;
    }

    getNotificationIcon(type) {
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

    async markAllNotificationsAsRead() {
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
                this.loadNotifications();
                this.showToast('Toutes les notifications ont été marquées comme lues', 'success');
            }
        } catch (error) {
            console.error('Erreur lors du marquage des notifications:', error);
            this.showToast('Erreur lors du marquage des notifications', 'error');
        }
    }

    startNotificationPolling() {
        // Vérifier les nouvelles notifications toutes les 30 secondes
        setInterval(() => {
            this.loadNotifications();
        }, 30000);
    }

    initializeResponsive() {
        // Close sidebar on navigation link click (mobile)
        document.querySelectorAll('.nav-item').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    this.closeSidebar();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                this.closeSidebar();
                this.closeAllDropdowns();
            }
        });

        // Update message count
        this.updateMessageCount();
        setInterval(() => {
            this.updateMessageCount();
        }, 30000);
    }

    async updateMessageCount() {
        try {
            const response = await fetch('/Altiris/back_office/api/new-messages.php');
            const data = await response.json();
            
            if (data.success) {
                const messageCount = document.getElementById('messageCount');
                if (messageCount) {
                    if (data.count > 0) {
                        messageCount.textContent = data.count > 99 ? '99+' : data.count;
                        messageCount.style.display = 'block';
                    } else {
                        messageCount.style.display = 'none';
                    }
                }
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour du compteur de messages:', error);
        }
    }

    showToast(message, type = 'info', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas ${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Add toast styles if not already present
        if (!document.querySelector('.toast-container')) {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const container = document.querySelector('.toast-container');
        container.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);

        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);

        // Manual close
        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        });
    }

    // Utility methods
    static showSuccess(message) {
        if (window.darkenTheme) {
            window.darkenTheme.showToast(message, 'success');
        }
    }

    static showError(message) {
        if (window.darkenTheme) {
            window.darkenTheme.showToast(message, 'error');
        }
    }

    static showWarning(message) {
        if (window.darkenTheme) {
            window.darkenTheme.showToast(message, 'warning');
        }
    }

    static showInfo(message) {
        if (window.darkenTheme) {
            window.darkenTheme.showToast(message, 'info');
        }
    }
}

// Global functions for backward compatibility
function toggleSidebar() {
    if (window.darkenTheme) {
        window.darkenTheme.toggleSidebar();
    }
}

function closeSidebar() {
    if (window.darkenTheme) {
        window.darkenTheme.closeSidebar();
    }
}

function confirmLogout() {
    if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
        window.location.href = "/Altiris/back_office/logout.php";
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.darkenTheme = new DarkenTheme();
});

// CSS for toasts (injected dynamically)
const toastStyles = `
.toast-container {
    position: fixed;
    top: calc(var(--navbar-height) + 1rem);
    right: 1rem;
    z-index: var(--z-tooltip);
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    max-width: 400px;
}

.toast {
    background: var(--altiris-dark);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    transform: translateX(100%);
    transition: var(--transition-normal);
    box-shadow: var(--shadow-lg);
}

.toast.show {
    transform: translateX(0);
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--altiris-white);
}

.toast-success {
    border-left: 4px solid var(--success);
}

.toast-error {
    border-left: 4px solid var(--danger);
}

.toast-warning {
    border-left: 4px solid var(--warning);
}

.toast-info {
    border-left: 4px solid var(--info);
}

.toast-close {
    background: none;
    border: none;
    color: var(--altiris-gray);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 50%;
    transition: var(--transition-fast);
}

.toast-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--altiris-white);
}

.search-suggestion-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: var(--altiris-gray-light);
    text-decoration: none;
    transition: var(--transition-fast);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.search-suggestion-item:hover {
    background: rgba(59, 130, 246, 0.1);
    color: var(--altiris-white);
}

.search-suggestion-item:last-child {
    border-bottom: none;
}

.search-suggestion-item i {
    color: var(--altiris-secondary);
    width: 16px;
    text-align: center;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: var(--transition-fast);
    cursor: pointer;
}

.notification-item:hover {
    background: rgba(59, 130, 246, 0.05);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background: rgba(30, 58, 138, 0.1);
    border-left: 3px solid var(--altiris-secondary);
}

.notification-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.notification-icon.info {
    background: rgba(6, 182, 212, 0.2);
    color: var(--info);
}

.notification-icon.success {
    background: rgba(16, 185, 129, 0.2);
    color: var(--success);
}

.notification-icon.warning {
    background: rgba(245, 158, 11, 0.2);
    color: var(--warning);
}

.notification-icon.error {
    background: rgba(239, 68, 68, 0.2);
    color: var(--danger);
}

.notification-icon.message,
.notification-icon.appointment {
    background: rgba(249, 115, 22, 0.2);
    color: var(--altiris-secondary);
}

.notification-icon.user {
    background: rgba(59, 130, 246, 0.2);
    color: var(--altiris-primary-light);
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    color: var(--altiris-white);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.notification-message {
    color: var(--altiris-gray-light);
    font-size: 0.8rem;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.notification-time {
    color: var(--altiris-gray);
    font-size: 0.75rem;
}
`;

// Inject toast styles
if (!document.querySelector('#toast-styles')) {
    const style = document.createElement('style');
    style.id = 'toast-styles';
    style.textContent = toastStyles;
    document.head.appendChild(style);
}