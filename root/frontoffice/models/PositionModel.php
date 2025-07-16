<?php
namespace Altiris\FrontOffice\Models;

class PositionModel {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllPositions() {
        $query = $this->db->query("SELECT * FROM position_act");
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}