<?php
class ImageModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllImages() {
        $query = "SELECT image1, image2, image3, image4, image5 FROM image_acceuil LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}