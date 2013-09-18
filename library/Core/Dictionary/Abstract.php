<?php

abstract class Core_Dictionary_Abstract 
{    
    
    /**
     *
     * @var array
     */
    protected $_dictionary = array();
    
    /**
     *
     * @var Core_Dictionary_Abstract 
     */
    protected static $_instance = array();
    
    /**
     * 
     * @return Core_Dictionary_Abstract
     */
    public static function getInstance()
    {
        $className = get_called_class();
        
        if(!isset(self::$_instance[$className])){
            self::$_instance[$className] = new static();
        }
        
        return self::$_instance[$className];
    }
    
    /**
     * 
     * @param string $code
     * @return string
     */
    public function getByCode($code) 
    {
        if(isset($this->_dictionary[$code])){
            return $this->_dictionary[$code];
        }
        
        return '';
    }

    /**
     * 
     * @return array
     */
    public function getList() 
    {
        return $this->_dictionary;
    }
            
}
