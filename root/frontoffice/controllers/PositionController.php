<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\PositionModel;

class HomeController {
    private $db;
    private $positionModel;

    public function __construct(\PDO $db) {
        $this->db = $db;
        $this->positionModel = new PositionModel($db);
    }

    public function index() {
        $positions = $this->positionModel->getAllPositions();
        require __DIR__.'/../views/contact.php';
    }
}