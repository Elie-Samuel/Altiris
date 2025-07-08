<?php
// config/db.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'altiris_base');

try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>