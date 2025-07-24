<?php
namespace Altiris\FrontOffice\Controllers;

use \PDO;
use PDOException;
use Exception;

class ActualiteController {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /**
     * Affiche une actualité spécifique
     */
    public function show() {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                throw new Exception("ID d'actualité invalide");
            }

            $stmt = $this->db->prepare("SELECT id, texte, Date as date, image FROM actualiter WHERE id = ?");
            $stmt->execute([$id]);
            $actualite = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$actualite) {
                throw new Exception("Actualité non trouvée");
            }

            $actualite['date_formatee'] = $actualite['date'] ? date('d/m/Y', strtotime($actualite['date'])) : 'Date non disponible';
            $actualite['titre'] = $this->extractTitle($actualite['texte']);
            $actualite['image_path'] = $this->getImagePath($actualite['image']);

            $this->renderView(['actualite' => $actualite]);
        } catch (Exception $e) {
            error_log("Erreur ActualiteController::show: " . $e->getMessage());
            header("HTTP/1.0 404 Not Found");
            require __DIR__ . '/../views/404.php';
        }
    }

    /**
     * Affiche la liste des actualités
     */
    public function index() {
        try {
            $stmt = $this->db->query("SELECT id, texte, Date as date, image FROM actualiter ORDER BY Date DESC");
            $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($actualites as &$actualite) {
                $actualite['date_formatee'] = $actualite['date'] ? date('d/m/Y', strtotime($actualite['date'])) : 'Date non disponible';
                $actualite['titre'] = $this->extractTitle($actualite['texte']);
                $actualite['image_path'] = $this->getImagePath($actualite['image']);
                $actualite['extrait'] = $this->createExcerpt($actualite['texte']);
            }

            $this->renderView(['actualites' => $actualites], 'actualites');
        } catch (PDOException $e) {
            error_log("Erreur ActualiteController::index: " . $e->getMessage());
            header("HTTP/1.0 500 Internal Server Error");
            require __DIR__ . '/../views/500.php';
        }
    }

    private function extractTitle($text, $maxLength = 50) {
        $text = strip_tags($text);
        return mb_substr($text, 0, $maxLength) . (mb_strlen($text) > $maxLength ? '...' : '');
    }

    private function createExcerpt($text, $maxLength = 150) {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        return mb_substr($text, 0, $maxLength) . (mb_strlen($text) > $maxLength ? '...' : '');
    }

    private function getImagePath($imageName) {
        if (empty($imageName)) return null;

        $possiblePaths = [
            '/Altiris/root/frontoffice/uploads/',
            '/Altiris/root/frontoffice/images/',
            '/uploads/',
            '/images/'
        ];

        foreach ($possiblePaths as $path) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path . $imageName;
            if (file_exists($fullPath)) {
                return $path . $imageName;
            }
        }

        return null;
    }

    private function renderView($data, $view = 'actualite') {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/partials/footer.php';
        echo ob_get_clean();
    }
}