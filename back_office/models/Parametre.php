<?php
require_once dirname(__DIR__, 2) . '/config/db.php';

class Parametre {
    private $conn;
    private $table = 'parametres';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            throw new Exception("Erreur de connexion à la base de données");
        }
        
        // Créer la table si elle n'existe pas
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $query = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            param_key VARCHAR(100) UNIQUE NOT NULL,
            param_value TEXT,
            param_type VARCHAR(50) DEFAULT 'string',
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        try {
            $this->conn->exec($query);
            
            // Insérer les paramètres par défaut
            $this->insertDefaultParams();
        } catch (PDOException $e) {
            error_log("Erreur création table paramètres: " . $e->getMessage());
        }
    }

    private function insertDefaultParams() {
        $defaults = [
            ['site_name', 'Altiris', 'string', 'Nom du site'],
            ['site_description', 'Système de gestion Altiris', 'string', 'Description du site'],
            ['site_email', 'admin@altiris.com', 'string', 'Email de contact du site'],
            ['site_phone', '', 'string', 'Téléphone de contact'],
            ['site_address', '', 'string', 'Adresse du site'],
            ['smtp_host', 'smtp.gmail.com', 'string', 'Serveur SMTP'],
            ['smtp_port', '587', 'integer', 'Port SMTP'],
            ['smtp_username', '', 'string', 'Nom d\'utilisateur SMTP'],
            ['smtp_password', '', 'string', 'Mot de passe SMTP'],
            ['smtp_encryption', 'tls', 'string', 'Type de chiffrement SMTP'],
            ['email_notifications', '1', 'boolean', 'Notifications par email'],
            ['sms_notifications', '0', 'boolean', 'Notifications par SMS'],
            ['push_notifications', '1', 'boolean', 'Notifications push'],
            ['notification_frequency', 'immediate', 'string', 'Fréquence des notifications'],
            ['maintenance_mode', '0', 'boolean', 'Mode maintenance'],
            ['max_login_attempts', '5', 'integer', 'Tentatives de connexion max'],
            ['session_timeout', '3600', 'integer', 'Timeout de session (secondes)']
        ];

        foreach ($defaults as $default) {
            $this->setParam($default[0], $default[1], $default[2], $default[3]);
        }
    }

    public function getParam($key, $defaultValue = null) {
        $query = "SELECT param_value, param_type FROM {$this->table} WHERE param_key = :key";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':key', $key);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $this->castValue($result['param_value'], $result['param_type']);
            }
            
            return $defaultValue;
        } catch (PDOException $e) {
            error_log("Erreur récupération paramètre: " . $e->getMessage());
            return $defaultValue;
        }
    }

    public function setParam($key, $value, $type = 'string', $description = null) {
        $query = "INSERT INTO {$this->table} (param_key, param_value, param_type, description) 
                  VALUES (:key, :value, :type, :description)
                  ON DUPLICATE KEY UPDATE 
                  param_value = :value, param_type = :type, description = COALESCE(:description, description)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':key', $key);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':description', $description);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur définition paramètre: " . $e->getMessage());
            return false;
        }
    }

    private function castValue($value, $type) {
        switch ($type) {
            case 'boolean':
                return (bool)$value;
            case 'integer':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function getSiteInfo() {
        return [
            'site_name' => $this->getParam('site_name'),
            'site_description' => $this->getParam('site_description'),
            'site_email' => $this->getParam('site_email'),
            'site_phone' => $this->getParam('site_phone'),
            'site_address' => $this->getParam('site_address')
        ];
    }

    public function updateSiteInfo($data) {
        $success = true;
        foreach ($data as $key => $value) {
            if (!$this->setParam($key, $value, 'string')) {
                $success = false;
            }
        }
        return $success;
    }

    public function getEmailSettings() {
        return [
            'smtp_host' => $this->getParam('smtp_host'),
            'smtp_port' => $this->getParam('smtp_port'),
            'smtp_username' => $this->getParam('smtp_username'),
            'smtp_password' => $this->getParam('smtp_password'),
            'smtp_encryption' => $this->getParam('smtp_encryption')
        ];
    }

    public function updateEmailSettings($data) {
        $success = true;
        foreach ($data as $key => $value) {
            $type = ($key === 'smtp_port') ? 'integer' : 'string';
            if (!$this->setParam($key, $value, $type)) {
                $success = false;
            }
        }
        return $success;
    }

    public function getNotificationSettings() {
        return [
            'email_notifications' => $this->getParam('email_notifications'),
            'sms_notifications' => $this->getParam('sms_notifications'),
            'push_notifications' => $this->getParam('push_notifications'),
            'notification_frequency' => $this->getParam('notification_frequency')
        ];
    }

    public function updateNotificationSettings($data) {
        $success = true;
        foreach ($data as $key => $value) {
            $type = in_array($key, ['email_notifications', 'sms_notifications', 'push_notifications']) ? 'boolean' : 'string';
            $value = ($type === 'boolean') ? ($value ? '1' : '0') : $value;
            if (!$this->setParam($key, $value, $type)) {
                $success = false;
            }
        }
        return $success;
    }

    // Méthodes pour les statistiques
    public function getTotalUsers() {
        try {
            $query = "SELECT COUNT(*) FROM utilisateurs";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getActiveUsers() {
        try {
            $query = "SELECT COUNT(*) FROM utilisateurs WHERE status = 'Actif'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTotalNotifications() {
        try {
            $query = "SELECT COUNT(*) FROM notifications";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getSystemUptime() {
        // Calculer depuis la date de création du premier utilisateur
        try {
            $query = "SELECT MIN(date_creation) as first_user FROM utilisateurs";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && $result['first_user']) {
                $firstDate = new DateTime($result['first_user']);
                $now = new DateTime();
                $diff = $now->diff($firstDate);
                return $diff->days;
            }
            
            return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function isMaintenanceMode() {
        return $this->getParam('maintenance_mode', false);
    }

    public function setMaintenanceMode($enabled) {
        return $this->setParam('maintenance_mode', $enabled ? '1' : '0', 'boolean');
    }

    public function getAllParams() {
        $query = "SELECT * FROM {$this->table} ORDER BY param_key";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $params = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $params[$row['param_key']] = [
                    'value' => $this->castValue($row['param_value'], $row['param_type']),
                    'type' => $row['param_type'],
                    'description' => $row['description']
                ];
            }
            
            return $params;
        } catch (PDOException $e) {
            error_log("Erreur récupération tous paramètres: " . $e->getMessage());
            return [];
        }
    }
}
?>