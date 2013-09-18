<?php

class Core_Uri_Helper {
    
    /**
     * 
     * @return string
     */
    public static function getCurrentProtocol(){
        return trim((isset($_SERVER['SERVER_PROTOCOL']) && stripos($_SERVER['SERVER_PROTOCOL'], 'https'))?
                'https'
                :'http');
    }
    
    /**
     * 
     * @return string
     * @throws JNET_Uri_Exception_UndeterminableUri
     */
    public static function getBaseUrl(){
        if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
            return trim($_SERVER['HTTP_HOST']);
        }else if(isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME'])){
            return trim($_SERVER['HTTP_HOST']);
        }else{
            throw new JNET_Uri_Exception_UndeterminableUri();
        }
    }
    
    /**
     * 
     * @return string
     */
    public static function getRequestUri(){        
        if(isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI']);
        }
    }
    
    /**
     * 
     * @return string
     */
    public static function getUrl(){
        return self::getCurrentProtocol().'://'.self::getBaseUrl().self::getRequestUri();
    }
    
    
}
