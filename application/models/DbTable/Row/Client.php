<?php

class Application_Model_DbTable_Row_User extends Core_Model_Db_Table_Row_Abstract
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
     * @return Application_Model_DbTable_Row_User
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }
    
    /**
     *
     * @param string $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_DbTable_Row_Role
     */
    public function getRole($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findParentRow('Application_Model_DbTable_Roles', $ruleKey, $select);
    }
    
    /**
     * 
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * 
     * @param type $username
     * @return \Application_Model_DbTable_Row_User
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
     * @param type $username
     * @return \Application_Model_DbTable_Row_User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * 
     * @param type $email
     * @return \Application_Model_DbTable_Row_User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * 
     * @return bool
     */
    public function isActive()
    {
        return (int)$this->active === 1;
    }
    
    /**
     * 
     * @return type
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * 
     * @param type $active
     * @return \Application_Model_DbTable_Row_User
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    /**
     * 
     * @param type $created
     * @return \Application_Model_DbTable_Row_User
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }
    
    /**
     * 
     * @param string $password
     * @return \Application_Model_DbTable_Row_User
     */
    public function setPassword($password)
    {
        $this->password = md5($password);
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function toArray() 
    {
        $array = parent::toArray();
        unset($array['password']);
        
        return $array;
    }
    
    /**
     * 
     * @param string $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return \Application_Model_DbTable_Row_User
     */
    public function getParent($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findParentRow('Application_Model_DbTable_Users', $ruleKey, $select);
    }
    
    /**
     * 
     * @param int $parent
     * @return \Application_Model_DbTable_Row_User
     */
    public function setParent($parent)
    {
        $this->parent = (int)$parent;
        return $this;
    }
    
    /**
     * 
     * @param type $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Core_Model_Db_Table_Rowset_Abstract
     */
    public function getChildren($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findDependentRowset('Application_Model_DbTable_Users', $ruleKey, $select);
    }
    
    /**
     * 
     * @return array
     */
    public function getChildrenIds()
    {
        $childrenIds = array();
        
        foreach($this->getChildren() as $user){
            if($user instanceof \Application_Model_DbTable_Row_User){
                $childrenIds[] = $user->getId();
            }
        }
        
        return $childrenIds;
    }
}
