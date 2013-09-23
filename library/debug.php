<?php

if(isset($_GET['SECURED_MODE']) && $_GET['SECURED_MODE'] == SECURED_KEY) {
    if (!isset($_COOKIE['SECURED_MODE'])) {
        $c = setcookie('SECURED_MODE', SECURED_KEY);
    }
}

// Show Build Mode screen
if(BUILD_MODE == 'On'
    && (!isset($_GET['SECURED_MODE']) || $_GET['SECURED_MODE'] != SECURED_KEY)
    && (!isset($_COOKIE['SECURED_MODE']) || $_COOKIE['SECURED_MODE'] != SECURED_KEY)
) {
    require PUBLIC_DIR . '/build-mode.php';
    exit;
}