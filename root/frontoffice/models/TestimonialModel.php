<?php
namespace Altiris\FrontOffice\Models;

class TestimonialModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllAnnouncements() {
        try {
            $query = "SELECT id, image, text FROM annonce ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return array_map([$this, 'processAnnouncement'], $results);
            
        } catch (\PDOException $e) {
            error_log("Erreur getAllAnnouncements: " . $e->getMessage());
            return [];
        }
    }

    private function processAnnouncement($announcement) {
        return [
            'id' => $announcement['id'],
            'image' => $this->processImage($announcement['image']),
            'content' => $announcement['text'],
            'lines' => $this->processContent($announcement['text'])
        ];
    }

    private function processImage($imageData) {
        if (empty($imageData)) return null;
        
        // Si c'est un BLOB
        return 'data:image/jpeg;base64,' . base64_encode($imageData);
    }

    private function processContent($content) {
        $lines = explode("\n", $content);
        return array_filter(array_map('trim', $lines));
    }
}