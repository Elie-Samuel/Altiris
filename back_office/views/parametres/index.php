<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/models/Parametre.php';
require_once dirname(__DIR__, 2) . '/controllers/ParametreController.php';

// Vérifier les permissions
if (!isset($_SESSION['types']) || $_SESSION['types'] !== 'Super admin') {
    $_SESSION['error'] = "Accès non autorisé.";
    header("Location: /Altiris/back_office/views/annonces/index.php");
    exit;
}

$controller = new ParametreController();
$error = null;
$success = null;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_site_info':
            $data = [
                'site_name' => trim($_POST['site_name']),
                'site_description' => trim($_POST['site_description']),
                'site_email' => trim($_POST['site_email']),
                'site_phone' => trim($_POST['site_phone']),
                'site_address' => trim($_POST['site_address'])
            ];
            
            if ($controller->updateSiteInfo($data)) {
                $success = "Informations du site mises à jour avec succès";
            } else {
                $error = "Erreur lors de la mise à jour";
            }
            break;
            
        case 'update_email_settings':
            $data = [
                'smtp_host' => trim($_POST['smtp_host']),
                'smtp_port' => (int)$_POST['smtp_port'],
                'smtp_username' => trim($_POST['smtp_username']),
                'smtp_password' => trim($_POST['smtp_password']),
                'smtp_encryption' => $_POST['smtp_encryption']
            ];
            
            if ($controller->updateEmailSettings($data)) {
                $success = "Paramètres email mis à jour avec succès";
            } else {
                $error = "Erreur lors de la mise à jour";
            }
            break;
            
        case 'update_notifications':
            $data = [
                'email_notifications' => isset($_POST['email_notifications']),
                'sms_notifications' => isset($_POST['sms_notifications']),
                'push_notifications' => isset($_POST['push_notifications']),
                'notification_frequency' => $_POST['notification_frequency']
            ];
            
            if ($controller->updateNotificationSettings($data)) {
                $success = "Paramètres de notification mis à jour avec succès";
            } else {
                $error = "Erreur lors de la mise à jour";
            }
            break;
    }
}

// Récupérer les paramètres actuels
$data = $controller->index();
$siteInfo = $data['siteInfo'];
$emailSettings = $data['emailSettings'];
$notificationSettings = $data['notificationSettings'];
$stats = $data['stats'];
?>

<link rel="stylesheet" href="/Altiris/back_office/css/style.css">
<script src="/Altiris/back_office/js/parametres.js"></script>

<div class="page-header">
    <h2><i class="fas fa-cog"></i> Paramètres du Site</h2>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistiques -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-number" id="total-users"><?php echo $stats['total_users']; ?></div>
        <div class="stat-label">Utilisateurs Total</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="active-users"><?php echo $stats['active_users']; ?></div>
        <div class="stat-label">Utilisateurs Actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="total-notifications"><?php echo $stats['total_notifications']; ?></div>
        <div class="stat-label">Notifications Envoyées</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="system-uptime"><?php echo $stats['system_uptime']; ?></div>
        <div class="stat-label">Jours en Ligne</div>
    </div>
</div>

<!-- Onglets -->
<div class="settings-tabs">
    <nav class="nav nav-tabs" id="settingsTab" role="tablist">
        <button class="nav-link active" id="site-tab" data-bs-toggle="tab" data-bs-target="#site" type="button" role="tab">
            <i class="fas fa-globe"></i> Informations du Site
        </button>
        <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
            <i class="fas fa-envelope"></i> Configuration Email
        </button>
        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
            <i class="fas fa-bell"></i> Notifications
        </button>
        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
            <i class="fas fa-shield-alt"></i> Sécurité
        </button>
        <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance" type="button" role="tab">
            <i class="fas fa-tools"></i> Maintenance
        </button>
    </nav>

    <div class="tab-content" id="settingsTabContent">
        <!-- Informations du Site -->
        <div class="tab-pane fade show active" id="site" role="tabpanel">
            <!-- Theme Settings -->
            <div class="form-container mb-4">
                <h4><i class="fas fa-palette"></i> Paramètres d'Affichage</h4>
                <div class="theme-settings">
                    <div class="theme-option">
                        <label class="theme-label">
                            <input type="radio" name="theme" value="light" class="theme-radio">
                            <div class="theme-preview light">
                                <div class="theme-preview-header"></div>
                                <div class="theme-preview-sidebar"></div>
                                <div class="theme-preview-content"></div>
                            </div>
                            <span class="theme-name">Mode Clair</span>
                        </label>
                    </div>
                    <div class="theme-option">
                        <label class="theme-label">
                            <input type="radio" name="theme" value="dark" class="theme-radio">
                            <div class="theme-preview dark">
                                <div class="theme-preview-header"></div>
                                <div class="theme-preview-sidebar"></div>
                                <div class="theme-preview-content"></div>
                            </div>
                            <span class="theme-name">Mode Sombre</span>
                        </label>
                    </div>
                    <div class="theme-option">
                        <label class="theme-label">
                            <input type="radio" name="theme" value="auto" class="theme-radio">
                            <div class="theme-preview auto">
                                <div class="theme-preview-header"></div>
                                <div class="theme-preview-sidebar"></div>
                                <div class="theme-preview-content"></div>
                            </div>
                            <span class="theme-name">Automatique</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-container">
                <form method="POST">
                    <input type="hidden" name="action" value="update_site_info">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tag"></i> Nom du Site
                                </label>
                                <input type="text" class="form-control" name="site_name" 
                                       value="<?php echo htmlspecialchars($siteInfo['site_name'] ?? 'Altiris'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i> Email de Contact
                                </label>
                                <input type="email" class="form-control" name="site_email" 
                                       value="<?php echo htmlspecialchars($siteInfo['site_email'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-info-circle"></i> Description du Site
                        </label>
                        <textarea class="form-control" name="site_description" rows="3"><?php echo htmlspecialchars($siteInfo['site_description'] ?? ''); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i> Téléphone
                                </label>
                                <input type="tel" class="form-control" name="site_phone" 
                                       value="<?php echo htmlspecialchars($siteInfo['site_phone'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Adresse
                                </label>
                                <input type="text" class="form-control" name="site_address" 
                                       value="<?php echo htmlspecialchars($siteInfo['site_address'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Configuration Email -->
        <div class="tab-pane fade" id="email" role="tabpanel">
            <div class="form-container">
                <form method="POST">
                    <input type="hidden" name="action" value="update_email_settings">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-server"></i> Serveur SMTP
                                </label>
                                <input type="text" class="form-control" name="smtp_host" 
                                       value="<?php echo htmlspecialchars($emailSettings['smtp_host'] ?? 'smtp.gmail.com'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-plug"></i> Port SMTP
                                </label>
                                <input type="number" class="form-control" name="smtp_port" 
                                       value="<?php echo htmlspecialchars($emailSettings['smtp_port'] ?? '587'); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i> Nom d'utilisateur SMTP
                                </label>
                                <input type="text" class="form-control" name="smtp_username" 
                                       value="<?php echo htmlspecialchars($emailSettings['smtp_username'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Mot de passe SMTP
                                </label>
                                <input type="password" class="form-control" name="smtp_password" 
                                       placeholder="Laisser vide pour ne pas modifier">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-shield-alt"></i> Chiffrement
                        </label>
                        <select class="form-control" name="smtp_encryption">
                            <option value="tls" <?php echo ($emailSettings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : ''; ?>>TLS</option>
                            <option value="ssl" <?php echo ($emailSettings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                            <option value="none" <?php echo ($emailSettings['smtp_encryption'] ?? '') === 'none' ? 'selected' : ''; ?>>Aucun</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <button type="button" class="btn btn-secondary" id="testEmailBtn">
                            <i class="fas fa-paper-plane"></i> Tester la Connexion
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notifications -->
        <div class="tab-pane fade" id="notifications" role="tabpanel">
            <div class="form-container">
                <form method="POST">
                    <input type="hidden" name="action" value="update_notifications">
                    
                    <div class="form-group">
                        <label class="form-label">Types de Notifications</label>
                        <div class="checkbox-group">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="email_notifications" 
                                       <?php echo ($notificationSettings['email_notifications'] ?? true) ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                                <i class="fas fa-envelope"></i> Notifications par Email
                            </label>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="sms_notifications" 
                                       <?php echo ($notificationSettings['sms_notifications'] ?? false) ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                                <i class="fas fa-sms"></i> Notifications par SMS
                            </label>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="push_notifications" 
                                       <?php echo ($notificationSettings['push_notifications'] ?? true) ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                                <i class="fas fa-bell"></i> Notifications Push
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-clock"></i> Fréquence des Notifications
                        </label>
                        <select class="form-control" name="notification_frequency">
                            <option value="immediate" <?php echo ($notificationSettings['notification_frequency'] ?? 'immediate') === 'immediate' ? 'selected' : ''; ?>>Immédiate</option>
                            <option value="hourly" <?php echo ($notificationSettings['notification_frequency'] ?? '') === 'hourly' ? 'selected' : ''; ?>>Toutes les heures</option>
                            <option value="daily" <?php echo ($notificationSettings['notification_frequency'] ?? '') === 'daily' ? 'selected' : ''; ?>>Quotidienne</option>
                            <option value="weekly" <?php echo ($notificationSettings['notification_frequency'] ?? '') === 'weekly' ? 'selected' : ''; ?>>Hebdomadaire</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <button type="button" class="btn btn-secondary" id="testNotificationBtn">
                            <i class="fas fa-paper-plane"></i> Envoyer Test
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sécurité -->
        <div class="tab-pane fade" id="security" role="tabpanel">
            <div class="form-container">
                <div class="security-section">
                    <h4><i class="fas fa-shield-alt"></i> Paramètres de Sécurité</h4>
                    
                    <div class="security-item">
                        <div class="security-info">
                            <strong>Authentification à deux facteurs</strong>
                            <p>Ajouter une couche de sécurité supplémentaire</p>
                        </div>
                        <button class="btn btn-warning">
                            <i class="fas fa-mobile-alt"></i> Configurer
                        </button>
                    </div>

                    <div class="security-item">
                        <div class="security-info">
                            <strong>Sessions actives</strong>
                            <p>Gérer les sessions utilisateur actives</p>
                        </div>
                        <button class="btn btn-info" id="viewSessionsBtn">
                            <i class="fas fa-users"></i> Voir les Sessions
                        </button>
                    </div>

                    <div class="security-item">
                        <div class="security-info">
                            <strong>Logs de sécurité</strong>
                            <p>Consulter les journaux d'activité</p>
                        </div>
                        <button class="btn btn-secondary" id="viewLogsBtn">
                            <i class="fas fa-file-alt"></i> Voir les Logs
                        </button>
                    </div>

                    <div class="security-item">
                        <div class="security-info">
                            <strong>Sauvegarde de la base de données</strong>
                            <p>Créer une sauvegarde complète</p>
                        </div>
                        <button class="btn btn-success" id="backupBtn">
                            <i class="fas fa-download"></i> Créer Sauvegarde
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="tab-pane fade" id="maintenance" role="tabpanel">
            <div class="form-container">
                <div class="maintenance-section">
                    <h4><i class="fas fa-tools"></i> Outils de Maintenance</h4>
                    
                    <div class="maintenance-item">
                        <div class="maintenance-info">
                            <strong>Nettoyer le cache</strong>
                            <p>Supprimer les fichiers temporaires et le cache</p>
                        </div>
                        <button class="btn btn-warning" onclick="clearCache()">
                            <i class="fas fa-broom"></i> Nettoyer
                        </button>
                    </div>

                    <div class="maintenance-item">
                        <div class="maintenance-info">
                            <strong>Optimiser la base de données</strong>
                            <p>Optimiser les tables de la base de données</p>
                        </div>
                        <button class="btn btn-info" id="optimizeBtn">
                            <i class="fas fa-database"></i> Optimiser
                        </button>
                    </div>

                    <div class="maintenance-item">
                        <div class="maintenance-info">
                            <strong>Vérifier les mises à jour</strong>
                            <p>Rechercher les mises à jour disponibles</p>
                        </div>
                        <button class="btn btn-primary" id="checkUpdatesBtn">
                            <i class="fas fa-sync-alt"></i> Vérifier
                        </button>
                    </div>

                    <div class="maintenance-item">
                        <div class="maintenance-info">
                            <strong>Mode maintenance</strong>
                            <p>Activer/désactiver le mode maintenance</p>
                        </div>
                        <button class="btn btn-danger" id="maintenanceBtn">
                            <i class="fas fa-exclamation-triangle"></i> Basculer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Theme Settings Styles */
.theme-settings {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.theme-option {
    position: relative;
}

.theme-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
    cursor: pointer;
    transition: all var(--transition-normal);
}

.theme-radio {
    display: none;
}

.theme-preview {
    width: 120px;
    height: 80px;
    border-radius: var(--radius-lg);
    border: 2px solid var(--border-color);
    overflow: hidden;
    position: relative;
    transition: all var(--transition-normal);
}

.theme-radio:checked + .theme-preview {
    border-color: var(--altiris-orange);
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
}

.theme-preview.light {
    background: #ffffff;
}

.theme-preview.dark {
    background: #0f172a;
}

.theme-preview.auto {
    background: linear-gradient(45deg, #ffffff 50%, #0f172a 50%);
}

.theme-preview-header {
    height: 15px;
    background: #3b82f6;
}

.theme-preview-sidebar {
    position: absolute;
    left: 0;
    top: 15px;
    bottom: 0;
    width: 30px;
    background: #1e293b;
}

.theme-preview-content {
    position: absolute;
    left: 30px;
    top: 15px;
    right: 0;
    bottom: 0;
}

.theme-preview.light .theme-preview-content {
    background: #f8fafc;
}

.theme-preview.dark .theme-preview-content {
    background: #1e293b;
}

.theme-name {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.theme-toggle-group {
    display: flex;
    gap: var(--spacing-xs);
    margin-left: auto;
}

.theme-btn {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: var(--radius-md);
    background: var(--bg-secondary);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.theme-btn:hover,
.theme-btn.active {
    background: var(--altiris-orange);
    color: var(--altiris-white);
}

.settings-tabs {
    margin-top: 20px;
}

.nav-tabs {
    border-bottom: 2px solid rgba(249, 115, 22, 0.2);
    margin-bottom: 30px;
}

.nav-tabs .nav-link {
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px 12px 0 0;
    margin-right: 5px;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    background: rgba(249, 115, 22, 0.1);
    color: var(--altiris-white);
    border-color: rgba(249, 115, 22, 0.3);
}

.nav-tabs .nav-link.active {
    background: var(--altiris-orange);
    color: var(--altiris-white);
    border-color: var(--altiris-orange);
}

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.checkbox-wrapper {
    display: flex;
    align-items: center;
    cursor: pointer;
    color: var(--altiris-white);
    font-weight: 500;
    padding: 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.checkbox-wrapper:hover {
    background: rgba(249, 115, 22, 0.1);
}

.checkbox-wrapper input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 4px;
    margin-right: 12px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-wrapper input[type="checkbox"]:checked + .checkmark {
    background: var(--altiris-orange);
    border-color: var(--altiris-orange);
}

.checkbox-wrapper input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 14px;
    font-weight: bold;
}

.security-section,
.maintenance-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.security-item,
.maintenance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(249, 115, 22, 0.2);
}

.security-info,
.maintenance-info {
    flex: 1;
}

.security-info strong,
.maintenance-info strong {
    color: var(--altiris-white);
    font-size: 16px;
    display: block;
    margin-bottom: 5px;
}

.security-info p,
.maintenance-info p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    font-size: 14px;
}

@media (max-width: 768px) {
    .nav-tabs {
        flex-wrap: wrap;
    }
    
    .nav-tabs .nav-link {
        font-size: 12px;
        padding: 8px 12px;
        margin-bottom: 5px;
    }
    
    .security-item,
    .maintenance-item {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .checkbox-group {
        gap: 10px;
    }
}
</style>

<style>
.auto-save-indicator {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--altiris-success);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    z-index: 10000;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}
</style>

<script>
// Theme management
document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme radio buttons
    const currentTheme = localStorage.getItem('theme') || 'light';
    const themeRadio = document.querySelector(`input[name="theme"][value="${currentTheme}"]`);
    if (themeRadio) {
        themeRadio.checked = true;
    }
    
    // Theme radio change handlers
    document.querySelectorAll('input[name="theme"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                if (this.value === 'auto') {
                    window.themeManager.setAutoTheme();
                } else {
                    window.themeManager.setTheme(this.value);
                }
            }
        });
    });
    
    // Sidebar theme buttons
    document.querySelectorAll('.theme-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const theme = this.dataset.theme;
            window.themeManager.setTheme(theme);
            
            // Update radio button
            const radio = document.querySelector(`input[name="theme"][value="${theme}"]`);
            if (radio) {
                radio.checked = true;
            }
            
            // Update active state
            document.querySelectorAll('.theme-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Set initial active state
    const activeThemeBtn = document.querySelector(`.theme-btn[data-theme="${currentTheme}"]`);
    if (activeThemeBtn) {
        activeThemeBtn.classList.add('active');
    }
});

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