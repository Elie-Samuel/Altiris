<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\TestimonialModel;
use PDO;
use PDOException;
use RuntimeException;

class HomeController {
    private $db;
    private $testimonialModel;
    
    public function __construct($db) {
        $configPath = 'D:/wamp64/www/Altiris/config/paths.php';
        
        if (!file_exists($configPath)) {
            throw new RuntimeException(
                "ERREUR: Fichier de configuration manquant.\n" .
                "Emplacement attendu: $configPath\n" .
                "Solution: Vérifiez que le fichier paths.php existe bien dans le dossier config/"
            );
        }
        require_once $configPath;

        if (file_exists('D:/wamp64/www/Altiris/root/frontoffice/models/TestimonialModel.php')) {
            require_once 'D:/wamp64/www/Altiris/root/frontoffice/models/TestimonialModel.php';
        } else {
            error_log("Fichier TestimonialModel.php introuvable à D:/wamp64/www/Altiris/root/frontoffice/models/");
            throw new RuntimeException("Fichier TestimonialModel.php manquant.");
        }

        $this->db = $db;
        $this->testimonialModel = new TestimonialModel($db, IMAGE_DIR);
    }
    
    public function index() {
        try {
            $data = [
                'announcements' => $this->getSafeAnnouncements(),
                'actualites' => $this->getActualites(),
                'services' => $this->getServices()
            ];
            $this->renderView($data);
        } catch (\Exception $e) {
            error_log("ERREUR HomeController: " . $e->getMessage());
            $this->renderError(500, "Une erreur est survenue");
        }
    }
    
    private function getSafeAnnouncements() {
        try {
            return $this->testimonialModel->getAllAnnouncements();
        } catch (\Exception $e) {
            error_log("Erreur getSafeAnnouncements: " . $e->getMessage());
            return [];
        }
    }
    
    private function getActualites() {
        try {
            $stmt = $this->db->prepare("
                SELECT id, image, texte, Date as date 
                FROM actualiter 
                ORDER BY date DESC 
                LIMIT 3
            ");
            $stmt->execute();
            return array_map([$this, 'formatActualite'], $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log("Erreur getActualites: " . $e->getMessage());
            return [];
        }
    }
    
    private function formatActualite($actu) {
        $dateFormatee = $this->formatDate($actu['date'] ?? null);
        $imagePath = $this->verifyImagePath($actu['image'] ?? null);
        if ($imagePath === null) {
            error_log("Image non trouvée pour actualité ID: " . ($actu['id'] ?? 'inconnu'));
        }
        return [
            'id' => $actu['id'] ?? null,
            'image' => $imagePath,
            'texte' => htmlspecialchars($actu['texte'] ?? '', ENT_QUOTES, 'UTF-8'),
            'date' => $actu['date'] ?? null,
            'date_formatee' => $dateFormatee,
            'titre' => $this->extractTitle($actu['texte'] ?? '')
        ];
    }
    
    private function verifyImagePath($imagePath) {
        if (empty($imagePath)) {
            error_log("verifyImagePath: Chemin image vide");
            return null;
        }
        
        $safePath = basename(preg_replace('/[^a-zA-Z0-9._-]/', '', $imagePath));
        $fullPath = IMAGE_DIR . '/' . $safePath;
        
        error_log("verifyImagePath: Vérification de $fullPath");
        if (!file_exists($fullPath)) {
            error_log("verifyImagePath: Fichier non trouvé: $fullPath");
            return null;
        }
        $webPath = WEB_IMAGE_PATH . '/' . $safePath;
        error_log("verifyImagePath: Chemin web retourné: $webPath");
        return $webPath;
    }
    
    private function formatDate($dateString) {
        if (empty($dateString)) {
            return 'N/A';
        }
        
        try {
            $date = new \DateTime($dateString);
            return $date->format('d/m/Y');
        } catch (\Exception $e) {
            error_log("Erreur formatage date: " . $e->getMessage());
            return 'Date invalide';
        }
    }
    
    private function extractTitle($text, $maxLength = 50) {
        $cleanText = trim(strip_tags($text));
        return mb_strlen($cleanText) <= $maxLength 
            ? $cleanText 
            : mb_substr($cleanText, 0, $maxLength) . '...';
    }
    
    private function getServices() {
        try {
            $stmt = $this->db->prepare("SELECT id, image, texte, titre FROM service");
            $stmt->execute();
            
            return array_map(function($service) {
                $imagePath = $this->verifyImagePath($service['image']);
                if ($imagePath === null) {
                    error_log("Image non trouvée pour service ID: " . $service['id']);
                }
                return [
                    'id' => $service['id'],
                    'image' => $imagePath,
                    'titre' => htmlspecialchars($service['titre'], ENT_QUOTES, 'UTF-8'),
                    'texte' => htmlspecialchars($service['texte'], ENT_QUOTES, 'UTF-8')
                ];
            }, $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            error_log("Erreur getServices: " . $e->getMessage());
            return [];
        }
    }
    
    private function renderView($data) {
        try {
            extract($data);
            ob_start();
            
            require __DIR__ . '/../views/partials/header.php';
            require __DIR__ . '/../views/home.php';
            require __DIR__ . '/../views/partials/footer.php';
            
            echo ob_get_clean();
        } catch (\Throwable $e) {
            error_log("Erreur renderView: " . $e->getMessage());
            $this->renderError(500, "Erreur d'affichage");
        }
    }
    
    private function renderError($code, $message) {
        http_response_code($code);
        require __DIR__ . '/../views/error.php';
        exit;
    }
    
    public function debugPaths() {
        echo '<pre>';
        echo '=== DEBUG CONFIGURATION ===' . "\n";
        echo 'Config path: D:/wamp64/www/Altiris/config/paths.php' . "\n";
        echo 'Exists: ' . (file_exists('D:/wamp64/www/Altiris/config/paths.php') ? 'YES' : 'NO') . "\n";
        echo 'Images dir: ' . IMAGE_DIR . "\n";
        echo 'Exists: ' . (is_dir(IMAGE_DIR) ? 'YES' : 'NO') . "\n";
        exit;
    }
}