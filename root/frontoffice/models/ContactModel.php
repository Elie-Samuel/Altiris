<?php
require_once __DIR__ . '/../../config/db.php';

class ContactModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getContactInfo() {
        $query = "SELECT * FROM contact_ent LIMIT 1";
        $this->db->query($query);
        return $this->db->single();
    }
}