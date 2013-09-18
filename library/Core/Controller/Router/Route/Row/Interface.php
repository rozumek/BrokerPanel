<?php

interface Core_Controller_Router_Route_Row_Interface {
    
    /**
     * 
     * @return Zend_Controller_Router_Route_Abstract
     */
    public function toRouterRoute();
    
    
    /**
     * 
     * @return string
     */
    public function getRouteName();
}
