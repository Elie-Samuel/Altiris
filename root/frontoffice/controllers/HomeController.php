<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\TestimonialModel;
use PDO;
use PDOException;

class HomeController {
    private $db;
    private $testimonialModel;
    
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->testimonialModel = new TestimonialModel($db);
    }
    
    public function index() {
        try {
            $data = [
                'announcements' => $this->testimonialModel->getAllAnnouncements(),
                'actualites' => $this->getActualites(),
                'services' => $this->getServices()
            ];
            $this->renderView($data);
        } catch (PDOException $e) {
            error_log("Erreur HomeController: " . $e->getMessage());
            $this->renderView([
                'announcements' => [],
                'actualites' => [],
                'services' => []
            ]);
        }
    }
    
    private function getActualites() {
        try {
            $stmt = $this->db->query("SELECT id, texte, Date as date, image FROM actualiter ORDER BY date DESC LIMIT 3");
            $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($actualites as &$actu) {
                $actu['date_formatee'] = $actu['date'] ? date('d/m/Y', strtotime($actu['date'])) : 'Date non disponible';
                $actu['titre'] = $this->extractTitle($actu['texte']);
                $actu['image_path'] = $this->getImagePath($actu['image']);
            }

            return $actualites;
        } catch (PDOException $e) {
            error_log("Erreur getActualites: " . $e->getMessage());
            return [];
        }
    }
    
    private function getServices() {
        try {
            $query = $this->db->prepare("SELECT id, titre, texte, image FROM service");
            $query->execute();
            $services = $query->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($services as &$service) {
                $service['image_path'] = $this->getImagePath($service['image']);
            }
            
            return $services;
        } catch (PDOException $e) {
            error_log("Erreur getServices: " . $e->getMessage());
            return [];
        }
    }
    
    private function getImagePath($imageName) {
        if (empty($imageName)) {
            return null;
        }
        
        // Supprimer "Assets/Images/" du début si présent
        $imageName = str_replace('Assets/Images/', '', $imageName);
        
        $basePath = '/Altiris/root/frontoffice/uploads/';
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $basePath . $imageName;
        
        if (file_exists($fullPath)) {
            return $basePath . $imageName;
        }
        
        // Alternative si l'image est dans un autre dossier
        $altPath = '/Altiris/Assets/Images/' . $imageName;
        $altFullPath = $_SERVER['DOCUMENT_ROOT'] . $altPath;
        
        if (file_exists($altFullPath)) {
            return $altPath;
        }
        
        return null;
    }
    
    private function extractTitle($text, $maxLength = 50) {
        $text = strip_tags($text);
        return mb_substr($text, 0, $maxLength) . (mb_strlen($text) > $maxLength ? '...' : '');
    }
    
    private function renderView($data) {
        extract($data);
        ob_start();
        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/home.php';
        require __DIR__.'/../views/partials/footer.php';
        echo ob_get_clean();
    }
}