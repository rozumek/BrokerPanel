<?php

interface Core_Object_Messages_Interface {
    
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
