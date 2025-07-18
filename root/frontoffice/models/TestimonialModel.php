<?php
namespace Altiris\FrontOffice\Models;

use PDO;
use PDOException;

class TestimonialModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllAnnouncements() {
        try {
            $query = "SELECT id, text, image FROM annonce ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return array_map([$this, 'processAnnouncement'], $results);
        } catch (PDOException $e) {
            error_log("Erreur getAllAnnouncements: " . $e->getMessage());
            return [];
        }
    }

    private function processAnnouncement($announcement) {
        return [
            'id' => $announcement['id'],
            'image' => $this->getImageUrl($announcement['image']),
            'content' => $announcement['text'],
            'lines' => $this->processContent($announcement['text'])
        ];
    }

    private function getImageUrl($imageName) {
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

    private function processContent($content) {
        $lines = explode("\n", $content);
        return array_filter(array_map('trim', $lines));
    }
}