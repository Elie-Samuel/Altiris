<?php
// back_office/config/config.php
session_start();
require_once(__DIR__ . '/../../config/db.php');

// Configuration générale
define('BASE_URL', '/Altiris/back_office/');

// Autoloader pour les classes
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../models/' . $class . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});
?>