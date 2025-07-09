<?php
// D:\wamp64\www\Altiris\root\frontoffice\models\TestimonialModel.php

namespace Altiris\FrontOffice\Models;

class TestimonialModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllTestimonials() {
        $testimonials = [];
        
        // Récupération depuis les 5 tables
        for ($i = 1; $i <= 5; $i++) {
            $table = "image_acceuille_$i";
            try {
                $query = "SELECT image, text FROM $table LIMIT 1";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                
                if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $testimonials[] = [
                        'image' => $row['image'],
                        'text' => $row['text']
                    ];
                }
            } catch (\PDOException $e) {
                error_log("Erreur témoignage table $table: " . $e->getMessage());
            }
        }
        
        // Si moins de 2 témoignages, ajoute des valeurs par défaut
        while (count($testimonials) < 2) {
            $testimonials[] = [
                'image' => null,
                'text' => 'Témoignage par défaut - Excellent service client!'
            ];
        }
        
        return $testimonials;
    }
}