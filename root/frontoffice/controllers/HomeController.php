<?php
// D:\wamp64\www\Altiris\root\frontoffice\controllers\HomeController.php

namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\ImageModel;
use Altiris\FrontOffice\Models\TestimonialModel;

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
        // Récupère les images pour le carousel
        $images = $this->imageModel->getAllImages();
        
        // Récupère les témoignages clients
        $testimonials = $this->testimonialModel->getAllTestimonials();
        
        // Passe les données à la vue
        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/home.php';
        require __DIR__.'/../views/partials/footer.php';
    }
}