<?php

class Application_Model_DbTable_Row_Resource extends Core_Model_Db_Table_Row_Abstract
{
    
    /**
     * 
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }
    
    /**
     * 
     * @param int $id
     * @return \Application_Model_DbTable_Row_Role
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }
    
    /**
     * 
     * @param string $module
     * @return \Application_Model_DbTable_Row_Resources
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }
    
    /**
     * 
     * @param string $controller
     * @return \Application_Model_DbTable_Row_Resources\
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }
        
    /**
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * 
     * @param string $description
     * @return \Application_Model_DbTable_Row_Resources
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @return Application_Acl_Resource
     */
    public function toAclResource()
    {        
        return new Core_Acl_Resource($this->getAclResourceId());        
    }
    
    /**
     * 
     * @return string
     */
    public function getAclResourceId()
    {       
        $resourceId = Core_Acl_Helper::assembleToResourceId(
                $this->getModule(), 
                $this->getController()
            );
        
        if(strlen($resourceId) == 0){
            throw new Core_Acl_AssembleResourceIdException;
        }
        
        return $resourceId;
    }
    
    /**
     * 
     * @param type $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_Db_Table_Rowset_Abstract
     */
    public function getAcls($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findDependentRowset('Application_Model_DbTable_Acl', $ruleKey, $select);
    }
    
}
