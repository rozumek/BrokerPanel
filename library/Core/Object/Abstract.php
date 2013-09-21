<?php

abstract class Core_Object_Abstract implements Core_Object_Messages_Interface {
    
    /**
     *
     * @var bool
     */
    protected static $_singleInstance = false;

    /**
     *
     * @var Core_Object_Abstract 
     */
    protected static $_instance = null;
    
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
     */
    public function __construct() {
        
    }
        
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
    
    /**
     * 
     * @return Core_Object_Abstract
     */
    public static function getInstance() {
        return static::$_instance;
    }
    
    /**
     * 
     * @param Core_Object_Abstract $instance
     */
    public static function setInstance(Core_Object_Abstract $instance) {
        static::$_instance = $instance;
    }
    
    /**
     * 
     * @param bool $flag
     */
    public static function setSingleInstance($flag=true) {
        static::$_singleInstance = (bool)$flag;
    }
    
    /**
     * 
     * @return bool
     */
    public static function isSingleInstance() {
        return static::$_singleInstance === true;
    }
    
    /**
     * 
     * @return bool
     */
    public static function hasInstance() {
        return static::$_instance instanceof Core_Object_Abstract;
    }
    
    /**
     * 
     * @param Core_Object_Abstract $instance
     * @throws Core_ClassInstanceAlreadyInitialized
     */
    public static function initSingleInstance(Core_Object_Abstract $instance) {
        if(self::hasInstance()) {
            throw new Core_ClassInstanceAlreadyInitialized;
        }
        
        static::setInstance($instance);
    }
}
