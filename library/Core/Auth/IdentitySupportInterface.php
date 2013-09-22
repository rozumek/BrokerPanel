<?php

interface Core_Auth_IdentitySupportInterface {

    /**
     *
     * @return Core_User_Identity
     */
    public function getIdentity($key=null);

    /**
     *
     * @param string $key
     * @param array $data
     */
    public function setIdentity($key=null, $data=null);

    /**
     *
     * @return bool
     */
    public function hasIdentity($key=null);

    /**
     *
     * @return bool
     */
    public function clearIdentity($key=null);

}
