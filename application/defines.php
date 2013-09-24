<?php

//Build mode to enable build page
defined('BUILD_MODE')
    || define('BUILD_MODE', 'On');

//Secured key for access during build
defined("SECURED_KEY")
    || define('SECURED_KEY', 'ZqbMCaymIm2MzcoKK1gn');

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
