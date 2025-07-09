<?php
namespace Altiris\FrontOffice\Models;

class ImageModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllImages() {
        try {
            $query = "SELECT image1, image2, image3, image4, image5 FROM image_acceuil LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Retourne un tableau vide si aucun résultat
            return $result ?: [];
        } catch (\PDOException $e) {
            // Log l'erreur et retourne un tableau vide
            error_log("Erreur ImageModel: " . $e->getMessage());
            return [];
        }
    }
}