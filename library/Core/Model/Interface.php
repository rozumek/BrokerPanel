<?php

/**
 * Description of Interface
 *
 * @author Marcinek
 */
interface Core_Model_Interface {
    
    /**
     * 
     * @return string
     */
    public function getLastMessage();
    
    /**
     * 
     * @return Exception
     */
    public function getLastException();
    
}
