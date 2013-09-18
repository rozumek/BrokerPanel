<?php

class Application_Model_Roles extends Core_Model_Db_Abstract implements Core_Acl_Processor_Roles_Interface, Cms_Controller_Processor_Interface
{       
    
    /**
     *
     * @var int
     */
    protected static $_guestRole = 1;

    /**
     *
     * @var int 
     */
    protected static $_superRole = 2;

    /**
     *
     * @var type 
     */
    protected static $_defaultUserRole = 3;
    
    /**
     * 
     */
    public function __construct() {
        $this->_dbTable = new Application_Model_DbTable_Roles();
    }
    
    /**
     * 
     * @param int $id
     * @return Application_Model_DbTable_Row_Role | null
     */
    public function getRole($id)
    {
        return $this->get($id);
    }
    
    /**
     * 
     * @param string $name
     * @return Application_Model_DbTable_Row_Role
     */
    public function getRoleByName($name, $returnEmpty=false)
    {
        return $this->getFirstWhere('role=?', $name, $returnEmpty);
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    public function roleExists($id)
    {
        if((int) $id > 0){
            return $this->exists($id);
        }else if(is_string($id)){
            return (bool) $this->getRoleByName($id);
        }
        
        return false;
    }
    
    public function delete($id) 
    {
        $role = $this->getRole($id);        
        if($role instanceof Application_Model_DbTable_Row_Role){
            if(!$role->isBlocked()){
                return parent::delete($id);
            }else{
                throw new Core_User_IsBlockedException();
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param int $id
     * @return bool
     */
    public function deleteRole($id)
    {
        return $this->delete($id);
    }
    
    /**
     * 
     * @param int $id
     * @param string $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_Db_Table_Rowset_Abstract
     */
    public function getRoleChildren($id, $ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->getRole($id)->getChildrenRoles($ruleKey, $select);
    }
    
    /**
     * 
     * @param int $id
     * @param string $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_DbTable_Row_Role
     */
    public function getRoleParent($id, $ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->getRole($id)->getParent($ruleKey, $select);
    }
    
    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function create($data)
    {
        $role = $this->getEmptyRow();
        
        if($role instanceof Application_Model_DbTable_Row_Role){   
            $data = (array)$data;
            $role->setName($data['name']);
            $role->setParent(Core_Array::get($data, 'parent', self::$_defaultUserRole));
            
            return $this->save($role);            
        }
        
        return false;
    }
    
    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $role = $this->getRole($id);
        
        if($role instanceof Application_Model_DbTable_Row_Role){
            $data = (array)$data;
            $role->setName($data['name']);
            
            if(isset($data['parent'])){
                $role->setParent($data['parent']);
            }
            
            return $this->save($role);
        }
        
        return false;
    }
    
    /**
     * 
     * @return int
     */
    public static function getDefaultUserRole()
    {
        return self::$_defaultUserRole ;
    }
    
    /**
     * 
     * @param int $role
     * @return \Application_Model_Roles
     */
    public static function setDefaultUserRole($role)
    {
        self::$_defaultUserRole = $role;
    }

    /**
     * 
     * @return Application_Model_Db_Table_Rowset_Abstract
     */
    public function getRoles() 
    {
        return $this->getRowset(array(), 'id ASC');
    }

    /**
     * @return array
     */
    public function getRolesHierarchy()
    {
        $ids = $this->getChildrenRolesIdsOf(null, true, 0);        
        return $ids;
    }

    /**
     * 
     * @param int|null $roleId
     * @param bool $withNames
     * @param int|null $indent
     * @return array
     */
    public function getChildrenRolesIdsOf($roleId, $withNames = false, $indent=null)
    {        
        $ids = array();        
        $roles = $this->getRoles()->toArray();
        Core_Hierarchy_Helper::toHierarchy($roles, $roleId, $ids, $withNames, $indent);        
        return $ids;
    }        
   
    /**
     * 
     * @param type $id
     */
    public function changeState($id) {
        throw new Core_Exception('Method "changeState" does nothing in Application_Model_Roles');
    }

    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function createRole($data) 
    {
        return $this->create($data);
    }

    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateRole($data, $id) 
    {
        return $this->update($data, $id);
    }
    
    /**
     * 
     * @param int $id
     */
    public static function setGuestRoleId($id)
    {
        self::$_guestRole = $id;
    }
    
    /**
     * 
     * @return int
     */
    public static function getGuestRoleId()
    {
        return self::$_guestRole;
    }

    /**
     * 
     * @param int $id
     */
    public static function setSuperRoleId($id)
    {
        self::$_superRole = $id;
    }
    
    /**
     * 
     * @return int
     */
    public static function getSuperRoleId()
    {
        return self::$_superRole;
    }

    /**
     * 
     * @return Application_Model_DbTable_Row_Role
     */
    public function getGuestRole()
    {
        return $this->getRole(self::getGuestRoleId());
    }
    
    /**
     * 
     * @return Application_Model_DbTable_Row_Role
     */
    public function getSuperRole()
    {
        return $this->getRole(self::getSuperRoleId());
    }      
    
}
