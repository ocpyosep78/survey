<?php
    // root dir
    define('ROOT_DIR', './');

    // secure the application
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = strtok($_SERVER["REQUEST_URI"],'?');
    $dns = $host . $request_uri;
    
    if($dns != 'localhost/') {
        // set message cookie
        require_once 'functions/functions.php';
        logout();
    }

    // include the configs
    require_once ROOT_DIR . 'config/config.php';

    // select language
    require_once ROOT_DIR . 'lang/lang.php';
    
    // include the page template
    require_once ROOT_DIR . 'pages/main.php';
?>
