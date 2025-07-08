<?php
// root/frontoffice/controllers/HomeController.php

require_once __DIR__.'/../models/ImageModel.php';

class HomeController {
    private $db;
    private $imageModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->imageModel = new ImageModel($db);
    }
    
    public function index() {
        // Récupérer les images depuis la base de données
        $images = $this->imageModel->getAllImages();
        
        // Charger la vue
        require_once __DIR__.'/../views/partials/header.php';
        require_once __DIR__.'/../views/home.php';
        require_once __DIR__.'/../views/partials/footer.php';
    }
}