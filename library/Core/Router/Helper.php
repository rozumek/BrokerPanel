<?php

class Core_Router_Helper 
{
    /**
     *
     * @var string
     */
    protected static $_routeGlue = '-';
    
    /**
     * 
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return string
     */
    public static function assembleRoute($module='default', $controller='index', $action='index')
    {
        $partsArray = func_get_args();
        
        return  trim(
            implode(self::$_routeGlue, $partsArray), 
            self::$_routeGlue.' '
        );
    }
}
