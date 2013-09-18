<?php

class Core_Hierarchy_Helper{
    
    /**
     *
     * @var string
     */
    protected static $_idKey = 'id';
    
    /**
     *
     * @var string
     */
    protected static $_nameKey = 'name';
    
    /**
     *
     * @var string
     */
    protected static $_parentKey = 'parent';

    /**
     *
     * @var string
     */
    protected static $_childrenKey = 'children';

    /**
     * 
     * @param array $roles
     * @param int|null|object|array $parent
     * @param array $ids
     * @param bool $withNames
     * @param int|null $indent     
     */
    public static function toHierarchy(&$items, $parent, &$ids=null, $withNames=false, $indent = null, $toObject = false){
        if($indent !== null){
            $indent++;
        }        
        foreach($items as $item){
            if($ids !== null && $item[self::$_parentKey] == $parent && ($item[self::$_parentKey] !== null || $parent === null)){
                if($withNames === true){
                    $indentString = ($indent == 0)?'':(str_repeat(' -- ', $indent).' ');
                    $ids[(int)$item[self::$_idKey]] = $indentString.$item[self::$_nameKey];                    
                }else if($withNames === false){
                    $ids[] = (int)$item[self::$_idKey];
                }else{
                    $indentString = ($indent == 0)?'':(str_repeat(' -- ', $indent).' ');
                    $item['h'.self::$_nameKey] = $indentString.$item[self::$_nameKey];
                    $ids[] = ($toObject === true)?((object)$item):$item;
                }                
                self::toHierarchy($items, (int)$item[self::$_idKey], $ids, $withNames, $indent, $toObject);
            }else if($ids === null && (is_object($parent) || is_array($parent))){
                if(is_object($parent)){
                    $parent = (array)$parent;
                }
                
                if($parent[self::$_idKey] == $item[self::$_parentKey]){
                    if(!isset($parent[self::$_childrenKey])){
                        $parent[self::$_childrenKey] = array();
                    }
                                                            
                    self::toHierarchy($items, &$item);
                    
                    unset($item['parent']);
                    $parent[self::$_childrenKey][] = $item;
                }
            }
        }          
    }
    
    /**
     * 
     * @return string
     */
    public static function getParentKey(){
        return self::$_parentKey;
    }
    
    /**
     * 
     * @param string $value
     */
    public static function setParentKey($value){
        self::$_parentKey = $value;
    }

    /**
     * 
     * @return string
     */
    public static function getNameKey(){
        return self::$_nameKey;
    }
    
    /**
     * 
     * @param string $value
     */
    public static function setNameKey($value){
        self::$_nameKey = $value;
    }
    
    /**
     * 
     * @return string
     */
    public static function getIdKey(){
        return self::$_idKey;
    }
    
    /**
     * 
     * @param string $value
     */
    public static function setIdKey($value){
        self::$_idKey = $value;
    }
    
    /**
     * 
     * @param string $value
     */
    public static function setChildrenKey($value){
        self::$_childrenKey = $value;
    }
    
    /**
     * 
     * @return string
     */
    public static function  getChildrenKey(){
        return self::$_childrenKey;
    }
    
}