<?php

// include the base class
require_once ROOT_DIR . 'class/Config.php';
require_once ROOT_DIR . 'class/BaseObject.php';
require_once ROOT_DIR . 'class/Answer.php';
require_once ROOT_DIR . 'class/Group.php';
require_once ROOT_DIR . 'class/Survey.php';
require_once ROOT_DIR . 'class/User.php';
require_once ROOT_DIR . 'class/Vote.php';
require_once ROOT_DIR . 'class/Message.php';

// create new config
$config = new Config();

// connect to data_base
try {
    $db = new PDO(  $config['db_driver'] . ':host=' . $config['db_host'] . ';dbname=' . $config['db'], 
                    $config['db_username'], 
                    $config['db_password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("SET NAMES utf8");
} catch (Exception $e) {
    echo $e->getMessage();
    return;
}

// include all object classes
//foreach (glob(ROOT_DIR . 'class/*.php') as $class) {
//    require_once $class;
//}

// include functions file
require_once ROOT_DIR . 'functions/functions.php';

if(isset($_SESSION['user'])) {
    $user = new User();
    $user = unserialize($_SESSION['user']);
} else {
    $user = null;
}
