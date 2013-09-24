<?php

class Core_Application_Env {

    /**
     *
     * @return bool
     */
    public static function isBuildMode() {
        return BUILD_MODE == 'On' || BUILD_MODE === true || BUILD_MODE != 0;
    }

}