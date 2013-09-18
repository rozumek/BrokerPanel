<?php


class Core_Array extends ArrayIterator{
    
    /**
     * 
     * @param array $array
     * @param key $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default=null){
        if(isset($array)){
            if(key_exists($key, $array) && $array[$key]!== null && $array[$key] !== ''){
                return $array[$key];
            }
        }
        
        return $default;
    }
}
