<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class TestimonialModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        error_log("TestimonialModel: Connexion PDO initialisée");
        try {
            $this->db->query("SELECT 1");
            error_log("TestimonialModel: Connexion à la base de données réussie");
        } catch (PDOException $e) {
            error_log("TestimonialModel: Échec de la connexion à la base de données - " . $e->getMessage());
        }
    }

    public function getActiveTestimonials() {
        try {
            $query = "SELECT 
                        id_tem as id,
                        Nom as nom, 
                        rang as poste, 
                        image, 
                        text_tem as texte, 
                        DATE_FORMAT(date_tem, '%d/%m/%Y') as date_formatee,
                        date_tem as date
                      FROM temoignage
                      ORDER BY date_tem DESC";
                      
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $temoignages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("TestimonialModel: Témoignages récupérés - " . count($temoignages) . " éléments");
            
            foreach ($temoignages as &$temoignage) {
                $temoignage['image_path'] = $this->getImageUrl($temoignage['image']);
            }
            
            return $temoignages;
        } catch (PDOException $e) {
            error_log("Erreur getActiveTestimonials: " . $e->getMessage());
            return [];
        }
    }

    public function getAllAnnouncements() {
        try {
            // Vérifier si la colonne 'slogan' existe
            $stmt = $this->db->query("SHOW COLUMNS FROM annonce LIKE 'slogan'");
            $hasSlogan = $stmt->rowCount() > 0;
            $columns = $hasSlogan 
                ? "id, text, image, titre, titre1, slogan"
                : "id, text, image, titre, titre1";
            
            $query = "SELECT $columns FROM annonce ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("TestimonialModel: Annonces récupérées - " . count($results) . " éléments : " . json_encode($results));
            
            if (empty($results)) {
                error_log("TestimonialModel: Aucune annonce trouvée dans la table annonce");
            }
            
            return array_map([$this, 'processAnnouncement'], $results);
        } catch (PDOException $e) {
            error_log("Erreur getAllAnnouncements: " . $e->getMessage());
            return [];
        }
    }

    private function processAnnouncement($announcement) {
        $processed = [
            'id' => $announcement['id'] ?? null,
            'image' => $this->getImageUrl($announcement['image'] ?? ''),
            'content' => $announcement['text'] ?? '',
            'titre' => $announcement['titre'] ?? 'Annonce sans titre',
            'titre1' => $announcement['titre1'] ?? 'Annonce sans titre principal',
            'slogan' => $announcement['slogan'] ?? '',
            'lines' => $this->processContent($announcement['text'] ?? '')
        ];
        
        error_log("TestimonialModel: Annonce traitée - ID: " . $processed['id']);
        return $processed;
    }

    private function getImageUrl($imageName) {
        if (empty($imageName)) {
            error_log("TestimonialModel: Aucun nom d'image fourni, retour par défaut");
            return '/Altiris/Assets/Images/logo_Altirys.jpg';
        }
        
        $imageName = str_replace('Assets/Images/', '', $imageName);
        
        $possiblePaths = [
            '/Altiris/Assets/Images/' . $imageName,
            '/Altiris/root/frontoffice/uploads/' . $imageName,
            '/Altiris/public/uploads/' . $imageName
        ];
        
        foreach ($possiblePaths as $path) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (file_exists($fullPath)) {
                error_log("TestimonialModel: Image trouvée à - " . $path);
                return $path;
            }
            error_log("TestimonialModel: Image non trouvée à - " . $fullPath);
        }
        
        error_log("TestimonialModel: Aucun chemin valide trouvé pour l'image - " . $imageName);
        return '/Altiris/Assets/Images/logo_Altirys.jpg';
    }

    private function processContent($content) {
        $lines = explode("\n", $content);
        return array_filter(array_map('trim', $lines));
    }
}