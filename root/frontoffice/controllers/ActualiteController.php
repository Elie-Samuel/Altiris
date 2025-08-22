<?php
namespace Altiris\FrontOffice\Controllers;

use PDO;
use PDOException;

class ActualiteController {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function index() {
        try {
            $query = $this->db->prepare("
                SELECT 
                    id,
                    Titre AS title,
                    image AS image_path,
                    texte AS excerpt,
                    Date AS created_at
                FROM actualiter
                ORDER BY Date DESC
            ");
            $query->execute();
            $actualites = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($actualites as &$actualite) {
                $actualite['image_path'] = $this->getImagePath($actualite['image_path']);
                if (strlen($actualite['excerpt']) > 120) {
                    $actualite['excerpt'] = substr($actualite['excerpt'], 0, 120) . '...';
                }
            }
            error_log("ActualiteController: Actualités récupérées - " . count($actualites) . " éléments");

            require __DIR__ . '/../views/actualites.php';
        } catch (PDOException $e) {
            error_log("ActualiteController: Erreur index - " . $e->getMessage());
            require __DIR__ . '/../views/errors/500.php';
        }
    }

    public function show($id) {
        try {
            $query = $this->db->prepare("
                SELECT 
                    id,
                    Titre AS title,
                    image AS image_path,
                    texte,
                    Date AS created_at
                FROM actualiter
                WHERE id = ?
            ");
            $query->execute([$id]);
            $actualite = $query->fetch(PDO::FETCH_ASSOC);

            if ($actualite) {
                $actualite['image_path'] = $this->getImagePath($actualite['image_path']);
                require __DIR__ . '/../views/actualite.php';
            } else {
                header("HTTP/1.0 404 Not Found");
                require __DIR__ . '/../views/errors/404.php';
            }
        } catch (PDOException $e) {
            error_log("ActualiteController: Erreur show - " . $e->getMessage());
            header("HTTP/1.0 500 Internal Server Error");
            require __DIR__ . '/../views/errors/500.php';
        }
    }

    private function getImagePath($imageName) {
        if (empty($imageName)) {
            error_log("ActualiteController: Aucun nom d'image fourni, retour par défaut");
            return '/Altiris/Assets/Images/logo_Altirys.jpg';
        }

        $imageName = ltrim($imageName, '/\\');
        if (stripos($imageName, 'Assets/Images/') === 0) {
            $imageName = 'Altiris/' . $imageName;
        }

        $possiblePaths = [
            '/Altiris/' . $imageName,
            '/Altiris/Assets/Images/' . basename($imageName),
            '/Altiris/assets/images/' . basename($imageName),
            '/Altiris/root/frontoffice/uploads/' . basename($imageName),
            $imageName
        ];

        foreach ($possiblePaths as $path) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (file_exists($fullPath)) {
                error_log("ActualiteController: Image trouvée à - " . $path);
                return $path;
            }
        }

        error_log("ActualiteController: Aucun chemin valide trouvé pour l'image - " . $imageName);
        return '/Altiris/Assets/Images/logo_Altirys.jpg';
    }
}