<?php

class Application_Model_DbTable_Row_Role extends Core_Model_Db_Table_Row_Abstract
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
     * @return type
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * 
     * @param type $name
     * @return \Application_Model_DbTable_Row_Role
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * 
     * @param type $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_Db_Table_Rowset_Abstract
     */
    public function getParent($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findParentRow('Application_Model_DbTable_Roles', $ruleKey, $select);
    }
    
    /**
     * 
     * @param type $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_Db_Table_Rowset_Abstract
     */
    public function getChildrenRoles($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findDependentRowset('Application_Model_DbTable_Roles', $ruleKey, $select);
    }
    
    /**
     * 
     * @return array
     */
    public function getChildrenRolesIds()
    {        
        $children = $this->getChildrenRoles();       
        
        foreach($children as $row){
            if($row instanceof Application_Model_DbTable_Row_Role){
                $ids[] = $row->getId();
            }
        }
        
        return $ids;
    }

        /**
     * 
     * @param type $parent
     * @return \Application_Model_DbTable_Row_Role
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * 
     * @param type $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return type
     */
    public function getUsers($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findDependentRowset('Application_Model_DbTable_Users', $ruleKey, $select);
    }
    
    /**
     * 
     * @return \Application_Acl_Role
     */
    public function toAclRole()
    {
        return new Core_Acl_Role($this->getName());
    }
    
    /**
     * 
     * @return bool
     */
    public function isBlocked()
    {
        return (int)$this->blocked === 1;
    }
}
