<?php

class Application_Model_DbTable_Row_Acl extends Core_Model_Db_Table_Row_Abstract
{
    
    /**
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 
     * @param string $id
     * @return Application_Model_DbTable_Row_Acl
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * 
     * @param type $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return \Application_Model_DbTable_Row_Resource
     */
    public function getResource($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findParentRow('Application_Model_DbTable_Resources', $ruleKey, $select);
    }
    
    /**
     * 
     * @param string $resource
     * @return Application_Model_DbTable_Row_Acl
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }
    
    /**
     * @param type $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_Db_Table_Rowset_Abstract
     */
    public function getRole($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findParentRow('Application_Model_DbTable_Roles', $ruleKey, $select);
    }
    
    /**
     * 
     * @param string $controller
     * @return Application_Model_DbTable_Row_Acl
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }        
    
    /**
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * 
     * @param string $type
     * @return Application_Model_DbTable_Row_Acl
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
        
    /**
     * 
     * @return string
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }
    
    /**
     * 
     * @param string $priviledge
     * @return Application_Model_DbTable_Row_Acl
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;
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
     * @return Application_Model_DbTable_Row_Acl
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
}
