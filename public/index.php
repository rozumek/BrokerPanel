<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

//Define DS
defined('DS')
    || define('DS', DIRECTORY_SEPARATOR);

//Define logs dir
defined('LANG_DIR')
    || define('LANG_DIR', realpath(APPLICATION_PATH.'/../languages'));

//Define logs dir
defined('DATA_DIR')
    || define('DATA_DIR', realpath(APPLICATION_PATH.'/../data'));

// Define tmp dir
defined('TMP_DIR')
    || define('TMP_DIR', realpath(DATA_DIR.'/tmp'));

//Define logs dir
defined('LOGS_DIR')
    || define('LOGS_DIR', realpath(DATA_DIR.'/logs'));

//Define logs dir
defined('NAVIGATION_XML')
    || define('NAVIGATION_XML', realpath(APPLICATION_PATH.'/configs/navigation.xml'));

// Define path to public directory
defined('PUBLIC_DIR')
    || define('PUBLIC_DIR', realpath(dirname(__FILE__)));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once APPLICATION_PATH.'/../library/functions.php';

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();