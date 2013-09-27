<?php

class Core_Application_Env {

    /**
     *
     * @return bool
     */
    public static function isBuildMode() {
        return BUILD_MODE == 'On' || BUILD_MODE === true || BUILD_MODE != 0;
    }

    /**
     *
     * @return bool
     */
    public static function isProductionEnv() {
        return APPLICATION_ENV == 'production';
    }

    /**
     *
     * @return bool
     */
    public static function isDevelopmentEnv() {
        return APPLICATION_ENV == 'delevopment';
    }
}