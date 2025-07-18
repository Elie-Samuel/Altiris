<?php
namespace Altiris\FrontOffice\Models;

class ServiceModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllServices() {
        try {
            $query = $this->db->query("SELECT id, image, texte, titre FROM service");
            $services = $query->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($services as &$service) {
                if (empty($service['titre'])) {
                    $service['titre'] = $this->extractTitle($service['texte']);
                }
            }
            
            return $services;
        } catch (\PDOException $e) {
            error_log("Erreur ServiceModel: " . $e->getMessage());
            return [];
        }
    }

    private function extractTitle($text, $maxLength = 30) {
        $cleanText = strip_tags($text);
        return mb_substr($cleanText, 0, $maxLength) . 
              (mb_strlen($cleanText) > $maxLength ? '...' : '');
    }
}