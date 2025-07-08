<?php
class ServicesController {
    private $db;
    private $serviceModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->serviceModel = new ServiceModel($db);
    }
    
    public function index() {
        // Récupérer les services depuis la base de données
        $services = $this->serviceModel->getAllServices();
        
        // Charger la vue
        require_once __DIR__.'/../views/partials/header.php';
        require_once __DIR__.'/../views/services.php';
        require_once __DIR__.'/../views/partials/footer.php';
    }
}