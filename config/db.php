<?php
// D:\wamp64\www\Altiris\config\db.php

class Database {
    private $host = "localhost";
    private $db_name = "altiris_base";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            error_log("Erreur de connexion : " . $exception->getMessage());
            throw $exception; // Propage l'exception pour une meilleure gestion
        }
        
        return $this->conn;
    }
}

// Création de l'instance unique
$database = new Database();
$db = $database->getConnection();