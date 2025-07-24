<?php
// altiris/root/bootstrap.php

// Assurez-vous que l'autoloader est correctement configuré
spl_autoload_register(function ($class) {
    $prefix = 'Altiris\\FrontOffice\\';
    $base_dir = __DIR__.'/frontoffice/'; // Base directory for FrontOffice classes
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    
    // Adjust path for models and controllers based on namespace
    if (strpos($relative_class, 'Controllers\\') === 0) {
        $file = $base_dir.'controllers/'.str_replace('\\', '/', substr($relative_class, strlen('Controllers\\'))).'.php';
    } elseif (strpos($relative_class, 'Models\\') === 0) {
        $file = $base_dir.'models/'.str_replace('\\', '/', substr($relative_class, strlen('Models\\'))).'.php';
    } else {
        // Fallback for other classes directly under FrontOffice namespace if any
        $file = $base_dir.str_replace('\\', '/', $relative_class).'.php';
    }

    if (file_exists($file)) {
        require $file;
    }
});