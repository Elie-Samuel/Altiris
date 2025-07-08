<?php
class ServiceModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllServices() {
        $query = "SELECT image1, image2, image3, image4, text1, text2, text3, text4 FROM service_acceuill LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}