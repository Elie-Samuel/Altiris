<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\MembreModel;
use PDO;

class TeamController {  // Changé de PersonnelController à TeamController
    private $db;
    private $membreModel;
    
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->membreModel = new MembreModel($db);
    }
    
    public function index() {
        try {
            $data = [
                'members' => $this->membreModel->getAllActiveMembers(),
                'meta' => [
                    'title' => 'ALTRIS - Notre Équipe',
                    'description' => 'Découvrez notre équipe de professionnels'
                ]
            ];
            
            $this->renderView('team', $data);  // Changé de 'personnel' à 'team'
        } catch (\Exception $e) {
            error_log("[TeamController] Error: " . $e->getMessage());
            $this->renderView('team', [
                'members' => [],
                'meta' => [
                    'title' => 'ALTRIS - Erreur',
                    'description' => 'Une erreur est survenue'
                ]
            ]);
        }
    }
    
    private function renderView($viewName, $data = []) {
        extract($data);
        
        // Header
        require __DIR__.'/../views/partials/header.php';
        
        // Main content
        require __DIR__.'/../views/'.$viewName.'.php';
        
        // Footer
        require __DIR__.'/../views/partials/footer.php';
    }
}