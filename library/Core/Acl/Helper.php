<?php

class Core_Acl_Helper 
{

    /**
     * Number of parts for resource
     */
    const RESOURCE_PART_COUNT = 2;
    
    /**
     *
     * @var string 
     */
    protected static $_resourceIdGlue = ':';        
    
    /**
     * 
     * @param string $lang
     * @param string $application
     * @param string $module
     * @param string $controller
     * @return string
     */
    public static function assembleToResourceId()
    {
        $partsArray = func_get_args();
        
        return  trim(
            implode(self::$_resourceIdGlue, $partsArray), 
            self::$_resourceIdGlue.' '
        );
    }
    
    /**
     * 
     * @param string $resourceId
     * @return array
     */
    public static function extractFromResourceId($resourceId){
        $parts = explode(self::$_resourceIdGlue, trim($resourceId));
        if(count($parts) == self::RESOURCE_PART_COUNT){
            return array(
                'module' => $parts[0],
                'controller' => $parts[1]
            );
        }
        
        return array();
    }
    
}
