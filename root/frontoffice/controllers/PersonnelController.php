<?php
class PersonnelController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function index() {
        // Charger la vue
        require_once __DIR__.'/../views/partials/header.php';
        require_once __DIR__.'/../views/personnel.php';
        require_once __DIR__.'/../views/partials/footer.php';
    }
}