<?php
namespace Altiris\FrontOffice\Controllers;

class ActualiteController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function show() {
        if (!isset($_GET['id'])) {
            header("Location: /Altiris/root/frontoffice/actualites");
            exit;
        }

        $id = (int)$_GET['id'];
        $actualite = $this->getActualite($id);

        if (!$actualite) {
            header("HTTP/1.0 404 Not Found");
            require __DIR__.'/../views/404.php';
            exit;
        }

        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/actualite.php';
        require __DIR__.'/../views/partials/footer.php';
    }

    private function getActualite($id) {
        try {
            $query = "SELECT image, texte, titre, DATE_FORMAT(date_creation, '%d %M %Y') as date_formatee 
                      FROM actualiter 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de l'actualité: " . $e->getMessage());
            return false;
        }
    }
}