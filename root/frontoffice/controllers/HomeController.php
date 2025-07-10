<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\ImageModel;

class HomeController {
    private $db;
    private $imageModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->imageModel = new ImageModel($db);
    }
    
    public function index() {
        // Récupération des images pour le carousel
        $images = $this->imageModel->getAllImages();
        
        // Récupération des actualités
        $actualites = $this->getActualites();
        
        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/home.php';
        require __DIR__.'/../views/partials/footer.php';
    }

    private function getActualites() {
        try {
            $query = "SELECT id, image, texte, DATE_FORMAT(date_creation, '%d %M %Y') as date_formatee 
                      FROM actualiter 
                      ORDER BY date_creation DESC 
                      LIMIT 3";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur actualités: " . $e->getMessage());
            return [];
        }
    }
}