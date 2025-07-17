<?php
namespace Altiris\FrontOffice\Models;

class ServiceModel {
    private $db;
    private $imageDir;
    private $webImagePath;

    public function __construct($db) {
        $this->db = $db;
        // Charger les constantes de configuration
        $configPath = 'D:/wamp64/www/Altiris/config/paths.php';
        if (file_exists($configPath)) {
            require_once $configPath;
            $this->imageDir = IMAGE_DIR;
            $this->webImagePath = WEB_IMAGE_PATH;
        } else {
            throw new \RuntimeException("ERREUR: Fichier de configuration paths.php manquant à : $configPath");
        }
    }

    public function getAllServices() {
        try {
            $query = $this->db->query("SELECT id, image, texte FROM service");
            $services = $query->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($services as &$service) {
                $service['image'] = $this->verifyImagePath($service['image']);
                $service['titre'] = $this->extractTitle($service['texte']);
            }
            
            return $services;
        } catch (\PDOException $e) {
            error_log("Erreur ServiceModel: " . $e->getMessage());
            return [];
        }
    }

    private function verifyImagePath($imagePath) {
        if (empty($imagePath)) {
            return null;
        }
        
        $safePath = basename(preg_replace('/[^a-zA-Z0-9._-]/', '', $imagePath));
        $fullPath = $this->imageDir . '/' . $safePath;
        
        error_log("Vérification image: $fullPath"); // Débogage
        return file_exists($fullPath) ? $this->webImagePath . '/' . $safePath : null;
    }

    private function extractTitle($text, $maxLength = 30) {
        $cleanText = strip_tags($text ?? '');
        return mb_strlen($cleanText) > $maxLength 
            ? mb_substr($cleanText, 0, $maxLength) . '...' 
            : $cleanText;
    }
}