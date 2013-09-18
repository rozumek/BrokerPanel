<?php

abstract class Core_Model_Abstract implements Core_Model_Interface{
    
    /**
     *
     * @var string 
     */
    protected $_lastMessage = null;
    
    /**
     *
     * @var Zend_Exception 
     */
    protected $_lastException = null;

    /**
     * 
     * @return string
     */
    public function getLastMessage(){
        return $this->_lastMessage;
    }
    
    /**
     * 
     * @return Zend_Exception
     */
    public function getLastException(){
        return $this->_lastException;
    }
}
