<?php
namespace Altiris\FrontOffice\Models;

class AboutModel {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAboutData(): array {
        $stmt = $this->db->prepare("SELECT * FROM about_page WHERE id = 1");
        $stmt->execute();
        return $stmt->fetch() ?: [];
    }

    public function getTeamMembers(): array {
        $stmt = $this->db->prepare("SELECT * FROM team_members WHERE active = 1 ORDER BY position_order");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCompanyStats(): array {
        $stmt = $this->db->prepare("SELECT * FROM company_stats");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}