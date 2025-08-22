<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\TestimonialModel;
use Altiris\FrontOffice\Models\CompetenceModel;
use Altiris\FrontOffice\Models\ContactFooterModel;
use Altiris\FrontOffice\Controllers\BlogController;
use PDO;
use PDOException;

class HomeController {
    private $db;
    private $testimonialModel;
    private $competenceModel;
    private $contactFooterModel;
    private $blogController;
    private $pageTitle;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->testimonialModel = new TestimonialModel($db);
        $this->competenceModel = new CompetenceModel($db);
        $this->contactFooterModel = new ContactFooterModel($db);
        $this->blogController = new BlogController($db);
        error_log("HomeController: Initialisé avec connexion PDO");
    }

    public function setPageTitle($title) {
        $this->pageTitle = $title;
    }

    public function index() {
        error_log("HomeController: index() appelé");
        try {
            $announcements = $this->testimonialModel->getAllAnnouncements();
            error_log("HomeController: Annonces récupérées - " . count($announcements) . " éléments");

            $data = [
                'pageTitle' => $this->pageTitle ?? 'ALTIRYS - Votre partenaire digital de confiance',
                'announcement' => $announcements,
                'posts' => $this->blogController->getAllPosts(),
                'actualites' => $this->getActualites(),
                'services' => $this->getServices(),
                'competences' => $this->getCompetencesWithFallback(),
                'temoignages' => $this->testimonialModel->getActiveTestimonials(),
                'altirysInfo' => $this->getAltirysInfo(),
                'agenceInfo' => $this->getAgenceInfo(),
                'partenaires' => $this->getPartenaires(),
                'caracteristiques' => $this->getCaracteristiques()
            ];
            
            error_log("HomeController: Données préparées pour la vue - " . json_encode(array_keys($data)));
            $this->renderView($data);
        } catch (PDOException $e) {
            error_log("HomeController: Erreur dans index - " . $e->getMessage());
            $this->renderView([
                'pageTitle' => $this->pageTitle ?? 'ALTIRYS - Votre partenaire digital de confiance',
                'announcement' => [],
                'posts' => [],
                'actualites' => [],
                'services' => [],
                'competences' => [],
                'temoignages' => [],
                'altirysInfo' => [],
                'agenceInfo' => [],
                'partenaires' => [],
                'caracteristiques' => []
            ]);
        }
    }

    private function getPartenaires() {
        try {
            $query = $this->db->prepare("SELECT id, logo_partenaire FROM partenaire");
            $query->execute();
            $partenaires = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($partenaires as &$partenaire) {
                $partenaire['image_path'] = $this->getImagePath($partenaire['logo_partenaire'] ?? '');
            }
            error_log("HomeController: Partenaires récupérés - " . count($partenaires) . " éléments");
            return $partenaires;
        } catch (PDOException $e) {
            error_log("HomeController: Erreur getPartenaires - " . $e->getMessage());
            return [
                ['id' => 1, 'logo_partenaire' => '/Altiris/Assets/Images/logo_Altirys.jpg', 'image_path' => '/Altiris/Assets/Images/logo_Altirys.jpg']
            ];
        }
    }

    private function getCaracteristiques() {
        try {
            $query = $this->db->prepare("SELECT id_caract, icon_bootstrap, description FROM caractéristiques");
            $query->execute();
            $caracteristiques = $query->fetchAll(PDO::FETCH_ASSOC);
            error_log("HomeController: Caractéristiques récupérées - " . count($caracteristiques) . " éléments");
            return $caracteristiques;
        } catch (PDOException $e) {
            error_log("HomeController: Erreur getCaracteristiques - " . $e->getMessage());
            return [
                ['id_caract' => 1, 'icon_bootstrap' => 'fa-user', 'description' => 'Description par défaut']
            ];
        }
    }

    private function getActualites() {
        try {
            $query = $this->db->prepare("
                SELECT 
                    id,
                    Titre AS title,
                    image,
                    texte AS excerpt,
                    Date AS created_at
                FROM actualiter
                ORDER BY Date DESC
                LIMIT 3
            ");
            $query->execute();
            $actualites = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($actualites as &$actualite) {
                $actualite['image_path'] = $this->getImagePath($actualite['image'] ?? '');
                $actualite['category'] = 'technologie';
                if (strlen($actualite['excerpt']) > 120) {
                    $actualite['excerpt'] = substr($actualite['excerpt'], 0, 120) . '...';
                }
            }
            error_log("HomeController: Actualités récupérées - " . count($actualites) . " éléments");
            return $actualites;
        } catch (PDOException $e) {
            error_log("HomeController: Erreur getActualites - " . $e->getMessage());
            return [];
        }
    }

    private function getCompetencesWithFallback() {
        try {
            $competences = $this->competenceModel->getAllCompetences();
            if (empty($competences)) {
                error_log("HomeController: Aucune compétence trouvée, utilisation du fallback");
                return [
                    ['nom' => 'HTML5', 'image_path' => 'html5'],
                    ['nom' => 'CSS3', 'image_path' => 'css3'],
                    ['nom' => 'JavaScript', 'image_path' => 'javascript'],
                    ['nom' => 'PHP', 'image_path' => 'phpunit'],
                    ['nom' => 'MySQL', 'image_path' => 'mysql']
                ];
            }
            return $competences;
        } catch (PDOException $e) {
            error_log("HomeController: Erreur getCompetences - " . $e->getMessage());
            return [
                ['nom' => 'HTML5', 'image_path' => 'html5'],
                ['nom' => 'CSS3', 'image_path' => 'css3'],
                ['nom' => 'JavaScript', 'image_path' => 'javascript'],
                ['nom' => 'PHP', 'image_path' => 'phpunit'],
                ['nom' => 'MySQL', 'image_path' => 'mysql']
            ];
        }
    }

    private function getServices() {
        try {
            $query = $this->db->prepare("SELECT id, titre, texte, image FROM service LIMIT 6");
            $query->execute();
            $services = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($services as &$service) {
                $service['image_path'] = $this->getImagePath($service['image'] ?? '');
                if (strlen($service['texte']) > 120) {
                    $service['texte'] = substr($service['texte'], 0, 120) . '...';
                }
            }
            error_log("HomeController: Services récupérés - " . count($services) . " éléments");
            return $services;
        } catch (PDOException $e) {
            error_log("HomeController: Erreur getServices - " . $e->getMessage());
            return [];
        }
    }

    private function getAltirysInfo() {
        try {
            $stmt = $this->db->query("SELECT mission, vente_boost, analyse, image FROM altirys_info LIMIT 1");
            $altirysInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($altirysInfo) {
                $altirysInfo['image_path'] = $this->getImagePath($altirysInfo['image'] ?? '');
                error_log("HomeController: AltirysInfo récupéré");
                return $altirysInfo;
            }
            error_log("HomeController: Aucun AltirysInfo trouvé");
            return [];
        } catch (PDOException $e) {
            error_log("HomeController: Erreur getAltirysInfo - " . $e->getMessage());
            return [];
        }
    }

    private function getAgenceInfo() {
        try {
            $stmt = $this->db->query("SELECT vision, clients_nombre, projets_completes, heures_travail, recompenses_nombre FROM agence_info LIMIT 1");
            $agenceInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("HomeController: AgenceInfo récupéré");
            return $agenceInfo ?: [];
        } catch (PDOException $e) {
            error_log("HomeController: Erreur getAgenceInfo - " . $e->getMessage());
            return [];
        }
    }

    private function getImagePath($imageName) {
        if (empty($imageName)) {
            error_log("HomeController: Aucun nom d'image fourni, retour par défaut");
            return '/Altiris/Assets/Images/logo_Altirys.jpg';
        }

        $imageName = ltrim($imageName, '/\\');
        
        if (stripos($imageName, 'Assets/Images/') === 0) {
            $imageName = 'Altiris/' . $imageName;
            error_log("HomeController: Correction du chemin - " . $imageName);
        }

        $possiblePaths = [
            '/Altiris/' . $imageName,
            '/Altiris/Assets/Images/' . basename($imageName),
            '/Altiris/assets/images/' . basename($imageName),
            '/Altiris/root/frontoffice/uploads/' . basename($imageName),
            $imageName
        ];

        foreach ($possiblePaths as $path) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (file_exists($fullPath)) {
                error_log("HomeController: Image trouvée à - " . $path);
                return $path;
            }
            error_log("HomeController: Image non trouvée à - " . $fullPath);
        }

        error_log("HomeController: Aucun chemin valide trouvé pour l'image - " . $imageName);
        return '/Altiris/Assets/Images/logo_Altirys.jpg';
    }

    private function renderView($data) {
        extract($data);
        ob_start();
        $headerPath = __DIR__ . '/../views/partials/header.php';
        $homePath = __DIR__ . '/../views/home.php';
        if (!file_exists($headerPath)) {
            error_log("HomeController: Fichier header.php introuvable à - " . $headerPath);
        }
        if (!file_exists($homePath)) {
            error_log("HomeController: Fichier home.php introuvable à - " . $homePath);
        }
        require $headerPath;
        require $homePath;
        $output = ob_get_clean();
        echo $output;
    }
}