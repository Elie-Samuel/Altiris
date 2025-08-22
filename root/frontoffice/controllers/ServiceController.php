<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\ServiceModel;

class ServicesController {
    private $model;

    public function __construct($db) {
        $this->model = new ServiceModel($db);
    }

    public function index() {
        $services = $this->model->getAllServices();
        
        // Debug (à supprimer en production)
        echo '<pre>'; print_r($services); echo '</pre>';
        
        require __DIR__.'/../views/services.php';
    }
}