<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\ImageModel;
use Altiris\FrontOffice\Models\TestimonialModel;
use PDO;
use PDOException;

class HomeController {
    private $db;
    private $imageModel;
    private $testimonialModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->imageModel = new ImageModel($db);
        $this->testimonialModel = new TestimonialModel($db);
    }
    
    public function index() {
        try {
            $data = [
                'announcements' => $this->testimonialModel->getAllAnnouncements(),
                'carouselImages' => $this->imageModel->getAllImages(),
                'actualites' => $this->getActualites(),
                'services' => $this->getServices()
            ];
            
            $this->renderView($data);
        } catch (PDOException $e) {
            error_log("Erreur HomeController: " . $e->getMessage());
            
            // Version de secours avec données minimales
            $this->renderView([
                'announcements' => [],
                'carouselImages' => [],
                'actualites' => [],
                'services' => []
            ]);
        }
    }
    
    private function getActualites() {
        $stmt = $this->db->query("SELECT id, image, texte, Date as date FROM actualiter ORDER BY date DESC LIMIT 3");
        $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($actualites as &$actu) {
            $actu['date_formatee'] = $actu['date'] ? date('d/m/Y', strtotime($actu['date'])) : 'Date non disponible';
            $actu['titre'] = $this->extractTitle($actu['texte']);
        }

        return $actualites;
    }
    
    private function extractTitle($text, $maxLength = 50) {
        $text = strip_tags($text);
        return mb_substr($text, 0, $maxLength) . (mb_strlen($text) > $maxLength ? '...' : '');
    }
    
    private function getServices() {
        try {
            $query = $this->db->prepare("SELECT id, image, texte, titre FROM service");
            $query->execute();
            $services = $query->fetchAll(\PDO::FETCH_ASSOC);
            
            // Débogage
            error_log("Services récupérés : " . print_r($services, true));
            
            return $services;
        } catch (\PDOException $e) {
            error_log("Erreur getServices: " . $e->getMessage());
            return []; // Retourne un tableau vide en cas d'erreur
        }
    }
    
    private function renderView($data) {
        extract($data);
        ob_start();
        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/home.php';
        require __DIR__.'/../views/partials/footer.php';
        echo ob_get_clean();
    }
    
    // Méthode de debug
    public function debugActualites() {
        $actualites = $this->getActualites();
        echo '<pre>';
        print_r($actualites);
        echo '</pre>';
        exit;
    }
}