<?php

class Core_Resource_Appconfig extends Zend_Application_Resource_ResourceAbstract
{   
    
    /**
     * 
     * @var Zend_Config_Ini   
     */
    protected static $_appConfig = null;
    
    /**
     *
     * @var array 
     */
    protected $_commonConfigOptions = array(
        'allowModifications' => true
    );

    /**
     *
     * @return Zend_Config_Ini  
     */
    public function init() {           
        if(self::$_appConfig === null){ 
            $filename = APPLICATION_PATH.'/configs/application.ini';
            if(file_exists($filename)){
                self::$_appConfig = new Zend_Config_Ini($filename, APPLICATION_ENV, $this->_commonConfigOptions);     
            }
        }
        
        return self::$_appConfig;
    }
    
}

