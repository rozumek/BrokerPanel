<?php

class Core_Acl extends Core_Acl_Abstract implements Core_Model_Interface
{
    /**
     *
     * @var string 
     */
    protected $_lastMessage = null;
    
    /**
     *
     * @var Zend_Exception 
     */
    protected $_lastException = null;
    
    /**
     * 
     * @param Core_Acl_Processor_Roles_Interface $roles
     * @param Core_Acl_Processor_Resources_Interface $resources
     * @param Core_Acl_Processor_AclRules_Interface $aclRules     
     * @param int $defaultRole
     * @param int $superRole
     */
    public function __construct(Core_Acl_Processor_Roles_Interface $roles=null, Core_Acl_Processor_Resources_Interface $resources=null, Core_Acl_Processor_AclRules_Interface $aclRules=null, $defaultRole=1, $superRole=4) 
    {
        $this->setDefaultRole($defaultRole)
                ->setSuperRole($superRole);
        
        if($resources){
            $this->setResourcesProcessor($resources)
                    ->digestResources();
        }
        if($roles){
            $this->setRolesProcessor($roles)
                    ->digestRoles();
        }
        if($aclRules && $roles && $resources){
            $this->setAclRulesProcessor($aclRules)
                    ->digestAclRules();
        }    
    }
    
    /**
     * 
     * @return \Core_Acl
     */
    public function setInitialAclRules()
    {
        $errorResourceId = Core_Acl_Helper::assembleToResourceId('default', 'error');
        $errorAdminResourceId = Core_Acl_Helper::assembleToResourceId('admin', 'error');
        
        // allow error cotrollers
        $this->add(new Core_Acl_Resource($errorResourceId));
        $this->add(new Core_Acl_Resource($errorAdminResourceId));
        $this->allow(null, $errorResourceId);
        $this->allow(null, $errorAdminResourceId);
        
        // allow everyting to super user
        $this->allow($this->getSuperRoleId(), null);
        
        return $this;
    }

    public function digestAclRules() 
    {
        if($this->_rolesProcesor == null){
            throw new Core_Acl_Processor_AclRulesNotSetException;
        }
        
        $this->setInitialAclRules();
        $aclRules = $this->_aclProcesor->getAclRules();
        
        if($aclRules->count() > 0){
            foreach($aclRules as $rule){
                if($rule instanceof Application_Model_DbTable_Row_Acl){
                    $typeAction = $rule->getType();
                    if(!$this->_aclProcesor->isAllowedType($typeAction)){
                        $typeAction = $this->_aclProcesor->getDefaultPriviledgeType();
                    }
                    $role = ($rule->getRole()!== null)?$rule->getRole()->getName():null;
                    $resource = ($rule->getResource()!== null)?$rule->getResource()->toAclResource():null;
                    $privilege = $rule->getPrivilege();
                    $this->$typeAction($role, $resource, $privilege);
                }
            }
        }
        return $this;
    }

    public function digestResources() 
    {
        if($this->_rolesProcesor == null){
            throw new Core_Acl_Processor_ResourcesNotSetException;
        }
        
        $resources = $this->_resourcesProcesor->getResources();
        
        if($resources->count() > 0){
            foreach($resources as $resource){   
                if($resource instanceof Application_Model_DbTable_Row_Resource){
                    $this->add($resource->toAclResource());
                }
            }
        }
        
        return $this;
    }

    public function digestRoles() 
    {
        if($this->_rolesProcesor == null){
            throw new Core_Acl_Processor_RolesNotSetException;
        }
        
        $roles = $this->_rolesProcesor->getRoles();    
        
        if($roles->count() > 0){
            foreach($roles as $role){
                if($role instanceof Application_Model_DbTable_Row_Role){
                    $parent = ($role->getParent() !== null)?$role->getParent()->getName():null;
                    $this->addRole($role->toAclRole(), $parent);
                }
            }
        }
        
        return $this;
    }

    public function getAclTree($role) 
    {
        //@todo implement
    }

    /**
     * 
     * @return string|null
     */
    public function getDefaultRoleId() 
    {
        $role = $this->getRolesProcessor()
                ->getRole($this->getDefaultRole());
            
        if($role instanceof Application_Model_DbTable_Row_Role){
            return $role->getName();
        }
        
        return null;
    }

    /**
     * 
     * @return string|null
     */
    public function getSuperRoleId() 
    {
        $role = $this->getRolesProcessor()
                ->getRole($this->getSuperRole());
        
        if($role instanceof Application_Model_DbTable_Row_Role){
            return $role->getName();
        }
        
        return null;
    }

    /**
     * 
     * @param array $data
     * @param int $role
     */
    public function saveAclRules($data, $role) 
    {
        //@todo implement
    }

    /**
     * 
     * @param Core_Acl $instance
     */
    public function setInstance($instance) 
    {
        if($instance instanceof Core_Acl){
            self::$_instance = $instance;
        }else{        
            throw new Core_Exception('Argument is not instance of Core_Acl');
        }
    }

    /**
     * 
     * @return Core_Acl
     */
    public static function getInstance(Core_Acl_Processor_Roles_Interface $roles=null, Core_Acl_Processor_Resources_Interface $resources=null, Core_Acl_Processor_AclRules_Interface $aclRules=null, $defaultRole=1, $superRole=4) 
    {
        if (null === self::$_instance) {             
            self::$_instance = new self($roles, $resources, $aclRules, $defaultRole, $superRole);
        }

        return self::$_instance;
    }

    /**
     * 
     * @return string
     */
    public function getLastMessage()
    {
        return $this->_lastMessage;
    }
    
    /**
     * 
     * @return Zend_Exception
     */
    public function getLastException()
    {
        return $this->_lastException;
    }
    
    /**
     * 
     * @param string $resource
     * @param string $privilege
     * @return type
     */
    public static function isUserAllowed($resource = null, $privilege = null) 
    {
        $acl = Core_Acl::getInstance();
        $role = $acl->getDefaultRoleId();
        
        if(Zend_Auth::getInstance()->hasIdentity()){
            $role = Zend_Auth::getInstance()->getIdentity()->rolename;
        }
       
        return $acl->isAllowed($role, $resource, $privilege);        
    }
    
    /**
     * 
     * @param string $resource
     * @return bool
     */
    public static function canUserView($resource)
    {
        return self::isUserAllowed($resource, 'view')
                 &&  self::isUserAllowed($resource, 'view-all')
            ;                               
    }
    
    /**
     * 
     * @param string $resource
     * @param int $userId
     * @return bool
     */
    public static function canUserViewOwn($resource, $userId=null)
    {
        return self::isUserAllowed($resource, 'view')  
                && self::isUserAllowed($resource, 'view-own')
                && self::isOwn($userId);
    }
    
    /**
     * 
     * @param string $resource
     * @return bool
     */
    public static function canUserDelete($resource)
    {
        return self::isUserAllowed($resource, 'delete')
                && self::isUserAllowed($resource, 'delete-all')
            ;
    }
    
    /**
     * 
     * @param string $resource
     * @param int $userId
     * @return bool
     */
    public static function canUserDeleteOwn($resource, $userId=null)
    {
        return self::isUserAllowed($resource, 'delete')
                &&  self::isUserAllowed($resource, 'delete-own') 
                && self::isOwn($userId)
            ;
    }
    
    /**
     * 
     * @param string $resource
     * @return bool
     */
    public static function canUserEdit($resource)
    {
        return self::isUserAllowed($resource, 'edit') 
                && self::isUserAllowed($resource, 'edit-all')
            ;
    }
    
    /**
     * 
     * @param string $resource
     * @param int $userId
     * @return bool
     */
    public static function canUserEditOwn($resource, $userId=null)
    {
        return self::isUserAllowed($resource, 'edit') 
                && self::isUserAllowed($resource, 'edit-own') 
                && self::isOwn($userId)
            ;
    }
    
    /**
     * 
     * @param string $resource
     * @return bool
     */
    public static function canUserChangeState($resource)
    {
        return self::isUserAllowed($resource, 'changestate') 
                && self::isUserAllowed($resource, 'changestate-all')
            ;
    }
    
    /**
     * 
     * @param string $resource
     * @param int $userId
     * @return bool
     */
    public static function canUserChangeStateOwn($resource, $userId=null)
    {
        return self::isUserAllowed($resource, 'changestate') 
                && self::isUserAllowed($resource, 'changestate-own') 
                && self::isOwn($userId)
                ;
    }
    
    /**
     * 
     * @param int $userId
     * @return boolean
     */
    public static function isOwn($userId=null)
    {
        if($userId === null){
            return true;
        }
        
        return (int)Zend_Auth::getInstance()->getIdentity()->id === (int)$userId;
    }
}
