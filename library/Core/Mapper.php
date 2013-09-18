<?php

abstract class Core_Mapper {
    
    /**
     * 
     * @param array $colums
     * @param array $values
     */
    public function __construct($values=array()) {
        foreach($values as $column => $value){
            if(property_exists($this, $column) && isset($value)){
                $this->{$column} = $values[$column];
            }
        }
    }
    
    /**
     * 
     * @return type
     */
    public function toArray(){
        return get_object_vars($this);
    }
}
