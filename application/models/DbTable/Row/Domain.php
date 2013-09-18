<?php

class Application_Model_DbTable_Row_Domain extends Core_Model_Db_Table_Row_Abstract{
    
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
     * @return Application_Model_DbTable_Row_Domain
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getDomain(){
        return $this->domain;
    }
    
    /**
     * 
     * @param string $domain
     * @return \Application_Model_DbTable_Row_Domain
     */
    public function setDomain($domain){
        $this->domain = $domain;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getLang(){
        return $this->lang;
    }
    
    /**
     * 
     * @param string $lang
     * @return Core_Model_DbTable_Row_Acl
     */
    public function setLang($lang){
        $this->lang = $lang;
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
     * @return Application_Model_DbTable_Row_Domain
     */
    public function setDescription($description){
        $this->description = $description;
        return $this;
    }        
    
    /**
     * 
     * @return int
     */
    public function getActive(){
        return (int)$this->active;
    }
    
    /**
     * 
     * @return bool
     */
    public function isActive(){
        return (int)$this->active === 1;
    }
    
    /**
     * 
     * @param type $active
     * @return Application_Model_DbTable_Row_Domain
     */
    public function setActive($active){
        $this->active = (int)$active;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getLayout(){
        return $this->layout;
    }
    
    /**
     * 
     * @param string $layout
     * @return \Application_Model_DbTable_Row_Domain
     */
    public function setLayout($layout){
        $this->layout = $layout;
        return $this;
    }
}
