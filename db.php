<?php
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
            // Test de requête (optionnel, pour débogage)
            $test = $this->conn->query("SELECT 1 FROM actualiter LIMIT 1");
            if ($test === false) {
                error_log("La table actualiter n'existe pas ou est inaccessible");
            }
        } catch(PDOException $exception) {
            error_log("Erreur de connexion : " . $exception->getMessage());
        }
        
        return $this->conn;
    }
}
?>
