<?php

/**
 * Instalation notes:
 *
 * This file should be coppied to and it's name changed to build-mode.php,
 * also SECURED_KEY should be filled
 */

//Build mode to enable build page
defined('BUILD_MODE')
    || define('BUILD_MODE', 'Off');

//Secured key for access during build
defined("SECURED_KEY")
    || define('SECURED_KEY', '*****'); // to be filled
