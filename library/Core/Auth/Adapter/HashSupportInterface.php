<?php

interface Core_Auth_Adapter_HashSupportInterface extends Core_Auth_IdentitySupportInterface{
        
    /**
     * 
     * @param mixed $params
     * @return string
     */
    public function generateHash($params);
    
}
