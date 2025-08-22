<?php
namespace Altiris\FrontOffice\Controllers;

use PDO;
use PDOException;
use DateTime;
use DateTimeZone;

class AproposController {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function index() {
        try {
            $data = [
                'aproposHistory' => $this->getAproposHistory(),
                'aproposDesc' => $this->getAproposDescription(),
                'aproposMission' => $this->getAproposMission(),
                'aproposValues' => $this->getAproposValues(),
                'altirysInfo' => $this->getAltirysInfo(),
                'yearsExperience' => $this->getYearsExperience(),
                'publishedPostsCount' => $this->getPublishedPostsCount(),
                'testimonialsCount' => $this->getTestimonialsCount(),
                'socialLinks' => $this->getSocialLinks()
            ];

            $this->renderView($data);
        } catch (PDOException $e) {
            error_log("Erreur AproposController: " . $e->getMessage());
            $this->renderView([
                'aproposHistory' => [],
                'aproposDesc' => [],
                'aproposMission' => [],
                'aproposValues' => [],
                'altirysInfo' => [],
                'yearsExperience' => 0,
                'publishedPostsCount' => 0,
                'testimonialsCount' => 0,
                'socialLinks' => []
            ]);
        }
    }

    private function getAproposHistory() {
        try {
            $stmt = $this->db->query("SELECT titre, text, date FROM apropos_hist ORDER BY date ASC");
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($history as &$item) {
                $item['year'] = date('Y', strtotime($item['date']));
            }
            return $history;
        } catch (PDOException $e) {
            error_log("Erreur getAproposHistory: " . $e->getMessage());
            return [];
        }
    }

    private function getAproposDescription() {
        try {
            $stmt = $this->db->query("SELECT titre, sous_titre, text FROM apropos_desc LIMIT 1");
            $aproposDesc = $stmt->fetch(PDO::FETCH_ASSOC);
            return $aproposDesc ?: [];
        } catch (PDOException $e) {
            error_log("Erreur getAproposDescription: " . $e->getMessage());
            return [];
        }
    }

    private function getAproposMission() {
        try {
            $stmt = $this->db->query("SELECT icon_bootstrap, titre, text FROM apropos_mission ORDER BY id ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAproposMission: " . $e->getMessage());
            return [];
        }
    }

    private function getAproposValues() {
        try {
            $stmt = $this->db->query("SELECT icon_bootstrap, titre, text FROM apropos_valeur ORDER BY id ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getAproposValues: " . $e->getMessage());
            return [];
        }
    }

    private function getAltirysInfo() {
        try {
            $stmt = $this->db->query("SELECT mission, vente_boost, analyse, image FROM altirys_info LIMIT 1");
            $altirysInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($altirysInfo) {
                $altirysInfo['image_path'] = $this->getImagePath($altirysInfo['image']);
                return $altirysInfo;
            }
            return [];
        } catch (PDOException $e) {
            error_log("Erreur getAltirysInfo: " . $e->getMessage());
            return [];
        }
    }

    private function getYearsExperience() {
        try {
            $stmt = $this->db->query("SELECT MIN(date) as earliest_date FROM apropos_hist");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['earliest_date']) {
                $earliestDate = new DateTime($result['earliest_date']);
                $currentDate = new DateTime('now', new DateTimeZone('Indian/Antananarivo'));
                $interval = $earliestDate->diff($currentDate);
                return $interval->y;
            }
            return 0;
        } catch (PDOException $e) {
            error_log("Erreur getYearsExperience: " . $e->getMessage());
            return 0;
        }
    }

    private function getPublishedPostsCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM blog_posts WHERE published = 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erreur getPublishedPostsCount: " . $e->getMessage());
            return 0;
        }
    }

    private function getTestimonialsCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM temoignage");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erreur getTestimonialsCount: " . $e->getMessage());
            return 0;
        }
    }

    private function getSocialLinks() {
        try {
            $stmt = $this->db->query("SELECT platform, url, icon_class FROM social_links WHERE is_active = 1 ORDER BY order_position ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getSocialLinks: " . $e->getMessage());
            return [];
        }
    }

    private function getImagePath($imageName) {
        if (empty($imageName)) {
            return '/Altiris/Assets/Images/logo_Altirys.jpg';
        }

        $imageName = str_replace('Assets/Images/', '', $imageName);

        $paths = [
            '/Altiris/root/frontoffice/uploads/' . $imageName,
            '/Altiris/Assets/Images/' . $imageName,
            '/Altiris/assets/images/' . $imageName
        ];

        foreach ($paths as $path) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (file_exists($fullPath)) {
                return $path;
            }
        }

        return '/Altiris/Assets/Images/logo_Altirys.jpg';
    }

    private function renderView($data) {
        extract($data);
        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/apropos.php';
        require __DIR__.'/../views/partials/footer.php';
    }
}