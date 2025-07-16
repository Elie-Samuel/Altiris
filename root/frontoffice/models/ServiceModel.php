<?php
namespace Altiris\FrontOffice\Models;

class ServiceModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllServices() {
        try {
            // Récupération des services avec gestion des erreurs
            $query = $this->db->query("SELECT id, image, texte FROM service");
            $services = $query->fetchAll(\PDO::FETCH_ASSOC);
            
            // Ajout des titres extraits du texte
            foreach ($services as &$service) {
                $service['titre'] = $this->extractTitle($service['texte']);
            }
            
            return $services;
        } catch (\PDOException $e) {
            error_log("Erreur ServiceModel: " . $e->getMessage());
            return []; // Retourne un tableau vide en cas d'erreur
        }
    }

    private function extractTitle($text, $maxLength = 30) {
        // Supprime les balises HTML et coupe le texte
        $cleanText = strip_tags($text);
        return mb_substr($cleanText, 0, $maxLength) . 
              (mb_strlen($cleanText) > $maxLength ? '...' : '');
    }
}