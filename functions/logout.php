<?php
if(!isset($_SESSION)) {
    session_start();
}

// ensures anything dumped out will be caught
ob_start();

// destroy all session vars
session_destroy();

// clear out the output buffer
while (ob_get_status()) {
    ob_end_clean();
}

// redirected to url
header("Location: ".ROOT_DIR);

// end sripting
die();
?>