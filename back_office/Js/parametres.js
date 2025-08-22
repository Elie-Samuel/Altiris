// Gestion des paramètres Altiris Back Office

class ParametreManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadStats();
    }

    bindEvents() {
        // Test de connexion email
        const testEmailBtn = document.getElementById('testEmailBtn');
        if (testEmailBtn) {
            testEmailBtn.addEventListener('click', () => this.testEmailConnection());
        }

        // Sauvegarde
        const backupBtn = document.getElementById('backupBtn');
        if (backupBtn) {
            backupBtn.addEventListener('click', () => this.createBackup());
        }

        // Optimisation base de données
        const optimizeBtn = document.getElementById('optimizeBtn');
        if (optimizeBtn) {
            optimizeBtn.addEventListener('click', () => this.optimizeDatabase());
        }

        // Nettoyage cache
        const clearCacheBtn = document.getElementById('clearCacheBtn');
        if (clearCacheBtn) {
            clearCacheBtn.addEventListener('click', () => this.clearCache());
        }

        // Test notification
        const testNotificationBtn = document.getElementById('testNotificationBtn');
        if (testNotificationBtn) {
            testNotificationBtn.addEventListener('click', () => this.sendTestNotification());
        }

        // Auto-save des paramètres
        const forms = document.querySelectorAll('form[data-auto-save]');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.autoSaveForm(form);
                });
            });
        });
    }

    async testEmailConnection() {
        const btn = document.getElementById('testEmailBtn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Test en cours...';
        btn.disabled = true;

        try {
            const settings = {
                smtp_host: document.querySelector('input[name="smtp_host"]').value,
                smtp_port: document.querySelector('input[name="smtp_port"]').value,
                smtp_username: document.querySelector('input[name="smtp_username"]').value,
                smtp_password: document.querySelector('input[name="smtp_password"]').value,
                smtp_encryption: document.querySelector('select[name="smtp_encryption"]').value
            };

            const response = await fetch('/Altiris/back_office/api/parametres.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'test_email',
                    settings: settings
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast('Connexion email testée avec succès', 'success');
            } else {
                this.showToast('Erreur de connexion: ' + data.message, 'error');
            }
        } catch (error) {
            this.showToast('Erreur lors du test: ' + error.message, 'error');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async createBackup() {
        if (!confirm('Créer une sauvegarde de la base de données ?')) {
            return;
        }

        const btn = document.getElementById('backupBtn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';
        btn.disabled = true;

        try {
            const response = await fetch('/Altiris/back_office/api/parametres.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'create_backup' })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast('Sauvegarde créée: ' + data.filename, 'success');
            } else {
                this.showToast('Erreur lors de la sauvegarde: ' + data.message, 'error');
            }
        } catch (error) {
            this.showToast('Erreur: ' + error.message, 'error');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async optimizeDatabase() {
        if (!confirm('Optimiser la base de données ?')) {
            return;
        }

        const btn = document.getElementById('optimizeBtn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Optimisation...';
        btn.disabled = true;

        try {
            const response = await fetch('/Altiris/back_office/api/parametres.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'optimize_database' })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast('Base de données optimisée avec succès', 'success');
            } else {
                this.showToast('Erreur lors de l\'optimisation: ' + data.message, 'error');
            }
        } catch (error) {
            this.showToast('Erreur: ' + error.message, 'error');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async clearCache() {
        if (!confirm('Nettoyer le cache système ?')) {
            return;
        }

        const btn = document.getElementById('clearCacheBtn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Nettoyage...';
        btn.disabled = true;

        try {
            const response = await fetch('/Altiris/back_office/api/parametres.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'clear_cache' })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast('Cache nettoyé avec succès', 'success');
            } else {
                this.showToast('Erreur lors du nettoyage: ' + data.message, 'error');
            }
        } catch (error) {
            this.showToast('Erreur: ' + error.message, 'error');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async sendTestNotification() {
        try {
            const response = await fetch('/Altiris/back_office/api/parametres.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action: 'send_test_notification' })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showToast('Notification de test envoyée', 'success');
                // Recharger les notifications
                if (window.notificationSystem) {
                    window.notificationSystem.loadNotifications();
                }
            } else {
                this.showToast('Erreur lors de l\'envoi', 'error');
            }
        } catch (error) {
            this.showToast('Erreur: ' + error.message, 'error');
        }
    }

    async autoSaveForm(form) {
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        try {
            // Sauvegarder automatiquement
            const response = await fetch(form.action || window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                this.showAutoSaveIndicator();
            }
        } catch (error) {
            console.error('Erreur auto-save:', error);
        }
    }

    showAutoSaveIndicator() {
        let indicator = document.getElementById('autoSaveIndicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'autoSaveIndicator';
            indicator.className = 'auto-save-indicator';
            indicator.innerHTML = '<i class="fas fa-check"></i> Sauvegardé';
            document.body.appendChild(indicator);
        }
        
        indicator.style.display = 'block';
        indicator.style.opacity = '1';
        
        setTimeout(() => {
            indicator.style.opacity = '0';
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 300);
        }, 2000);
    }

    loadStats() {
        // Charger les statistiques en temps réel
        this.updateStats();
        
        // Mettre à jour toutes les 30 secondes
        setInterval(() => {
            this.updateStats();
        }, 30000);
    }

    async updateStats() {
        try {
            const response = await fetch('/Altiris/back_office/api/parametres.php');
            const data = await response.json();
            
            if (data.success && data.data.stats) {
                const stats = data.data.stats;
                
                // Mettre à jour les cartes de statistiques
                this.updateStatCard('total-users', stats.total_users);
                this.updateStatCard('active-users', stats.active_users);
                this.updateStatCard('total-notifications', stats.total_notifications);
                this.updateStatCard('system-uptime', stats.system_uptime + ' jours');
            }
        } catch (error) {
            console.error('Erreur chargement stats:', error);
        }
    }

    updateStatCard(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
            
            // Animation de mise à jour
            element.style.transform = 'scale(1.1)';
            element.style.color = 'var(--altiris-orange)';
            
            setTimeout(() => {
                element.style.transform = 'scale(1)';
                element.style.color = '';
            }, 300);
        }
    }

    showToast(message, type = 'info') {
        if (window.notificationSystem) {
            window.notificationSystem.showToast(message, type);
        } else {
            alert(message);
        }
    }

    // Méthodes utilitaires
    static showSuccess(message) {
        if (window.parametreManager) {
            window.parametreManager.showToast(message, 'success');
        }
    }

    static showError(message) {
        if (window.parametreManager) {
            window.parametreManager.showToast(message, 'error');
        }
    }

    static showWarning(message) {
        if (window.parametreManager) {
            window.parametreManager.showToast(message, 'warning');
        }
    }
}

// Initialiser le gestionnaire de paramètres
document.addEventListener('DOMContentLoaded', function() {
    window.parametreManager = new ParametreManager();
});

// Exporter pour utilisation globale
window.ParametreManager = ParametreManager;