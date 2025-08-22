<?php
require_once dirname(__DIR__) . '/models/Parametre.php';
require_once dirname(__DIR__) . '/controllers/NotificationController.php';

class ParametreController {
    private $parametreModel;
    private $notificationController;

    public function __construct() {
        $this->parametreModel = new Parametre();
        $this->notificationController = new NotificationController();
    }

    public function index() {
        $data = [
            'siteInfo' => $this->parametreModel->getSiteInfo(),
            'emailSettings' => $this->parametreModel->getEmailSettings(),
            'notificationSettings' => $this->parametreModel->getNotificationSettings(),
            'stats' => $this->getSystemStats()
        ];
        
        return $data;
    }

    public function updateSiteInfo($data) {
        $result = $this->parametreModel->updateSiteInfo($data);
        
        if ($result) {
            $this->notificationController->createSystemNotification(
                "Paramètres mis à jour",
                "Les informations du site ont été mises à jour",
                "success"
            );
        }
        
        return $result;
    }

    public function updateEmailSettings($data) {
        $result = $this->parametreModel->updateEmailSettings($data);
        
        if ($result) {
            $this->notificationController->createSystemNotification(
                "Configuration email",
                "Les paramètres email ont été mis à jour",
                "success"
            );
        }
        
        return $result;
    }

    public function updateNotificationSettings($data) {
        $result = $this->parametreModel->updateNotificationSettings($data);
        
        if ($result) {
            $this->notificationController->createSystemNotification(
                "Paramètres notifications",
                "Les paramètres de notification ont été mis à jour",
                "success"
            );
        }
        
        return $result;
    }

    public function testEmailConnection($settings) {
        require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $settings['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $settings['smtp_username'];
            $mail->Password = $settings['smtp_password'];
            $mail->SMTPSecure = $settings['smtp_encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $settings['smtp_port'];
            $mail->Timeout = 10;
            
            // Test de connexion sans envoyer d'email
            $mail->smtpConnect();
            $mail->smtpClose();
            
            return ['success' => true, 'message' => 'Connexion SMTP réussie'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur SMTP: ' . $e->getMessage()];
        }
    }

    public function getSystemStats() {
        return [
            'total_users' => $this->parametreModel->getTotalUsers(),
            'active_users' => $this->parametreModel->getActiveUsers(),
            'total_notifications' => $this->parametreModel->getTotalNotifications(),
            'system_uptime' => $this->parametreModel->getSystemUptime()
        ];
    }

    public function createBackup() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $backupDir = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/backups/';
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . $filename;
            
            // Obtenir la liste des tables
            $tables = [];
            $result = $conn->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            $backup = "-- Sauvegarde Altiris - " . date('Y-m-d H:i:s') . "\n\n";
            
            foreach ($tables as $table) {
                $backup .= "-- Structure de la table $table\n";
                $result = $conn->query("SHOW CREATE TABLE $table");
                $row = $result->fetch(PDO::FETCH_NUM);
                $backup .= $row[1] . ";\n\n";
                
                $backup .= "-- Données de la table $table\n";
                $result = $conn->query("SELECT * FROM $table");
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $backup .= "INSERT INTO $table VALUES (";
                    $backup .= "'" . implode("','", array_map('addslashes', $row)) . "'";
                    $backup .= ");\n";
                }
                $backup .= "\n";
            }
            
            file_put_contents($filepath, $backup);
            
            $this->notificationController->createSystemNotification(
                "Sauvegarde créée",
                "Sauvegarde de la base de données créée: $filename",
                "success"
            );
            
            return ['success' => true, 'filename' => $filename];
        } catch (Exception $e) {
            error_log("Erreur sauvegarde: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function optimizeDatabase() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $tables = [];
            $result = $conn->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            
            foreach ($tables as $table) {
                $conn->exec("OPTIMIZE TABLE $table");
            }
            
            $this->notificationController->createSystemNotification(
                "Base de données optimisée",
                "Toutes les tables ont été optimisées",
                "success"
            );
            
            return ['success' => true];
        } catch (Exception $e) {
            error_log("Erreur optimisation: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function clearCache() {
        try {
            $cacheDir = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/cache/';
            if (file_exists($cacheDir)) {
                $files = glob($cacheDir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
            
            $this->notificationController->createSystemNotification(
                "Cache nettoyé",
                "Le cache système a été vidé",
                "success"
            );
            
            return ['success' => true];
        } catch (Exception $e) {
            error_log("Erreur nettoyage cache: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>