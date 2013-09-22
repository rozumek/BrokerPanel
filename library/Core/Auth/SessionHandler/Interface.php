<?php

interface Core_Auth_SessionHandler_Interface {

    /**
     *
     * @param int $time
     * @return Core_Auth_SessionHandler_Interface
     */
    public function setSessionDuration($time);

    /**
     * @return int
     */
    public function getSessionDuration();

}
