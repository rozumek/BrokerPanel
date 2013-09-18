<?php

class Application_Model_DbTable_Row_Language extends Core_Model_Db_Table_Row_Abstract{
    
    /**
     * 
     * @return int
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * 
     * @param string $id
     * @return Application_Model_DbTable_Row_Language
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getLanguage(){
        return $this->language;
    }
    
    /**
     * 
     * @param string $language
     * @return Application_Model_DbTable_Row_Language
     */
    public function setLanguage($language){
        $this->language = $language;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getShortCode(){
       return $this->shortcode;
    }
   
    /**
     * 
     * @param string $shortcode
     * @return \Application_Model_DbTable_Row_Language
     */
    public function setShortCode($shortcode){
       $this->shortcode = $shortcode;
       return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getCode(){
        return $this->code;
    }
    
    /**
     * 
     * @param string $code
     * @return \Application_Model_DbTable_Row_Language
     */
    public function setCode($code){
        $this->code = $code;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getDescription(){
        return $this->description;
    }
    
    /**
     * 
     * @param string $description
     * @return Application_Model_DbTable_Row_Language
     */
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }
    
     /**
     * 
     * @return type
     */
    public function getActive(){
        return $this->active;
    }
    
    /**
     * 
     * @param type $active
     * @return \Application_Model_DbTable_Row_Language
     */
    public function setActive($active){
        $this->active = $active;
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function getDefault(){
        return (bool)$this->default;
    }
    
    /**
     * 
     * @param bool $default
     * @return \Application_Model_DbTable_Row_Language
     */
    public function setDefault($default){
        $this->default  = (bool)$default;
        return $this;
    }
    
}
