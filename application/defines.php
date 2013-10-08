<?php

//Define DS
defined('DS')
    || define('DS', DIRECTORY_SEPARATOR);

//Define logs dir
defined('LANG_DIR')
    || define('LANG_DIR', realpath(APPLICATION_PATH.'/../languages'));

//Define logs dir
defined('DATA_DIR')
    || define('DATA_DIR', APPLICATION_PATH.'/../data');

// Define tmp dir
defined('TMP_DIR')
    || define('TMP_DIR', DATA_DIR.'/tmp');

// Define tmp dir
defined('CACHE_DIR')
    || define('CACHE_DIR', DATA_DIR.'/cache');

//Define logs dir
defined('LOGS_DIR')
    || define('LOGS_DIR', DATA_DIR.'/logs');

//Define logs dir
defined('NAVIGATION_XML')
    || define('NAVIGATION_XML', realpath(APPLICATION_PATH.'/configs/navigation.xml'));
