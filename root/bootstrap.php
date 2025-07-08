<?php
// root/bootstrap.php

// 1. Configuration de l'autoloader
spl_autoload_register(function ($class) {
    $directories = [
        __DIR__.'/../Assets/config/frontoffice/models/',
        __DIR__.'/frontoffice/models/',
        __DIR__.'/frontoffice/controllers/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// 2. Inclusion des fichiers CSS/JS (à faire dans le header, pas ici)
// Cette partie est supprimée car elle doit être dans le template HTML
// et non dans un fichier PHP pur

// 3. Initialisation de la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}