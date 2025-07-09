<?php
// D:\wamp64\www\Altiris\root\bootstrap.php

spl_autoload_register(function ($class) {
    $prefix = 'Altiris\\FrontOffice\\';
    $base_dir = __DIR__.'/frontoffice/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir.str_replace('\\', '/', $relative_class).'.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}