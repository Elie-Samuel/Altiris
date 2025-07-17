<?php
// =====================
// CONFIGURATION DES CHEMINS
// =====================

// Chemins absolus
define('BASE_DIR', 'D:/wamp64/www/Altiris');
define('ASSETS_DIR', BASE_DIR . '/Assets');
define('IMAGE_DIR', ASSETS_DIR . '/Images');
define('CONFIG_DIR', BASE_DIR . '/config');

// Chemins web (URL)
define('WEB_ROOT', '');
define('WEB_IMAGE_PATH', '/Assets/Images');

// =====================
// VÉRIFICATIONS
// =====================

if (!is_dir(IMAGE_DIR)) {
    throw new RuntimeException(
        "ERREUR: Le dossier des images est introuvable.\n" .
        "Veuillez créer le dossier: " . IMAGE_DIR
    );
}

// =====================
// CONFIGURATION PHP
// =====================
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', BASE_DIR . '/error_log.txt');
error_reporting(E_ALL);