<?php

interface Core_Auth_Interface extends Core_Auth_IdentitySupportInterface{

    /**
     *
     * @param string $login
     * @param string $password
     */
    public function authenticate($login, $password);

}
