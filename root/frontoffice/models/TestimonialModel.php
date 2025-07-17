<?php
namespace Altiris\FrontOffice\Models;

class TestimonialModel {
    private $db;
    private $imageBasePath;

    public function __construct($db, $imageBasePath) {
        $this->db = $db;
        $this->imageBasePath = rtrim(str_replace('\\', '/', $imageBasePath), '/');
        
        // Vérification immédiate du dossier
        if (!is_dir($this->imageBasePath)) {
            throw new RuntimeException(
                "Dossier images introuvable: ".$this->imageBasePath."\n".
                "Solution: Créez le dossier ou vérifiez les permissions"
            );
        }
    }

    public function getAllAnnouncements() {
        try {
            $stmt = $this->db->prepare("SELECT id, image, text FROM annonce ORDER BY id DESC");
            $stmt->execute();
            
            return array_map(
                [$this, 'processAnnouncement'],
                $stmt->fetchAll(\PDO::FETCH_ASSOC)
            );
        } catch (\PDOException $e) {
            $this->logError("getAllAnnouncements", $e);
            return [];
        }
    }

    private function processAnnouncement($announcement) {
        return [
            'id' => $announcement['id'] ?? null,
            'image' => $this->processImage($announcement['image'] ?? null),
            'content' => $announcement['text'] ?? '',
            'lines' => $this->processContent($announcement['text'] ?? '')
        ];
    }

    private function processImage($imageName) {
        if (empty($imageName)) return null;

        // Nettoyage strict du nom de fichier
        $safeName = basename(preg_replace('/[^a-zA-Z0-9._-]/', '', $imageName));
        $fullPath = $this->imageBasePath.'/'.$safeName;

        if (file_exists($fullPath)) {
            return WEB_IMAGE_PATH.'/'.$safeName;
        }

        error_log("Image manquante: ".$safeName);
        return null;
    }

    private function processContent($content) {
        $lines = array_filter(
            array_map('trim', explode("\n", $content)),
            fn($line) => !empty($line)
        );
        return array_values($lines);
    }

    private function logError($context, $exception) {
        error_log(sprintf(
            "[%s] TestimonialModel/%s: %s\nStack trace:\n%s",
            date('Y-m-d H:i:s'),
            $context,
            $exception->getMessage(),
            $exception->getTraceAsString()
        ));
    }
}