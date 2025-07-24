<?php
namespace Altiris\FrontOffice\Controllers;

use Altiris\FrontOffice\Models\MembreModel;
use PDO;

class TeamController {
    private $db;
    private $membreModel;
    
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->membreModel = new MembreModel($db);
    }
    
    public function index() {
        // Définir BASE_URL si elle n'existe pas
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/Altiris/');
        }

        try {
            $members = $this->membreModel->getAllActiveMembers();
            
            if (empty($members)) {
                throw new \Exception("Aucun membre d'équipe trouvé.");
            }

            $data = [
                'members' => $members,
                'meta' => [
                    'title' => 'ALTRIS - Notre Équipe',
                    'description' => 'Découvrez notre équipe de professionnels'
                ],
                'scripts' => [
                    BASE_URL . 'assets/root/frontoffice/main.js',
                    BASE_URL . 'assets/gsap.min.js',
                    BASE_URL . 'assets/slick.min.js'
                ],
                'styles' => [
                    BASE_URL . 'root/frontoffice/css/style.css',
                    BASE_URL . 'root/frontoffice/css/team.css',
                    BASE_URL . 'assets/slick.css',
                    BASE_URL . 'assets/slick-theme.css'
                ],
                'base_url' => BASE_URL
            ];
            
            $this->renderView('team', $data);
        } catch (\Exception $e) {
            error_log("[TeamController] Error: " . $e->getMessage());
            $this->renderView('error', [
                'message' => $e->getMessage(),
                'meta' => [
                    'title' => 'ALTRIS - Erreur',
                    'description' => 'Une erreur est survenue'
                ],
                'base_url' => BASE_URL
            ]);
        }
    }
    
    private function renderView($viewName, $data = []) {
        extract($data);
        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/'.$viewName.'.php';
        require __DIR__.'/../views/partials/footer.php';
    }
}
