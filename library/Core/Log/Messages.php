<?php

class Core_Log_Messages {
    
    /**
     *
     * @var array
     */
    protected static $_typesToNameMap = array(
        //red colour messages
        Core_Log::EMERG => 'emergency',
        Core_Log::ALERT => 'alert',
        Core_Log::CRIT => 'critical',  
        Core_Log::ERR => 'error',  
        
        //orange colour messages
        Core_Log::WARN => 'warning',  
        Core_Log::NOTICE => 'notice',
        
        //blue colour messages
        Core_Log::INFO => 'info',         
        Core_Log::DEBUG => 'debug',
        Core_Log::ACCESS => 'access',
        Core_Log::TRANSACT => 'transact',
        
        //green colour messages
        Core_Log::SUCCESS => 'success'
        
    );


    /**
     * 
     * @param int $type
     * @return string
     */
    public static function resolve($type){
        if(isset(self::$_typesToNameMap[$type])){
            return self::$_typesToNameMap[$type];
        }
        
        return 'other';
    }
    
}
