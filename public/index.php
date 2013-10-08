<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define path to public directory
defined('PUBLIC_DIR')
    || define('PUBLIC_DIR', realpath(dirname(__FILE__)));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once APPLICATION_PATH . '/configs/build-mode.php';
require_once APPLICATION_PATH . '/defines.php';
require_once APPLICATION_PATH . '/../library/functions.php';

// Check if secure mode cookie should be created
if(isset($_GET['SECURED_MODE']) && $_GET['SECURED_MODE'] == SECURED_KEY) {
    if (!isset($_COOKIE['SECURED_MODE'])) {
        setcookie('SECURED_MODE', SECURED_KEY);
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

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
