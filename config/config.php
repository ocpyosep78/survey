<?php

// start new session
if (!isset($_SESSION)) {
    session_start();
}

// include the defaults
require_once ROOT_DIR . 'config/default.php';

// include the general function
require_once ROOT_DIR . 'config/general.php';

// include the access file
//require_once ROOT_DIR . 'config/access.php';

// get the URL query string
$query_str = $_SERVER['QUERY_STRING'];

// parse the URL query string to array
$query = array();
parse_str($query_str, $query);

// set print age
if (isset($query['print'])) {
    $print = $query['print'];
    require_once ROOT_DIR . 'functions/print/' . $print . '.php';
}

// set side page
if (isset($query['page'])) {
    $page = $query['page'];
}

// set side language
if (isset($query['lang'])) {
    $lang = $query['lang'];
}

// set function
if (isset($query['funct'])) {
    // get funct from uri request
    $funct = $query['funct'];
    
    // init array
    $arr = array();
    foreach ($query as $key => $value) {
        if($key != 'page' and $key != 'funct')
        array_push($arr, $value);
    }
    
    // call the user function
    call_user_func_array("$funct", $arr);
}

// set a user
if (isset($_SESSION['user'])) {
    $user = new User();
    $user = unserialize($_SESSION['user']);
}
?>