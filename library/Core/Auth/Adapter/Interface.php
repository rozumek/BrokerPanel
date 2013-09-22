<?php

interface Core_Auth_Adapter_Interface extends Zend_Auth_Adapter_Interface {
    
    /**
     * 
     * @return array|object
     */
    public function getIdentityData();
    
}
