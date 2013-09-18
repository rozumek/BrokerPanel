<?php

abstract class Core_Acl_Abstract extends Zend_Acl{
    
    /**
     *
     * @var Core_Acl 
     */
    protected static $_instance = null;
    
    /**
     *
     * @var Core_Acl_Processor_Resources_Interface
     */
    protected $_resourcesProcesor= null;
    
    /**
     *
     * @var Core_Acl_Processor_Roles_Interface
     */
    protected $_rolesProcesor = null;
    
    /**
     * 
     * @var Core_Acl_Processor_AclRules_Interface
     */
    protected $_aclProcesor = null;
    
    /**
     *
     * @var int 
     */
    protected $_defaultRole = 1;
    
    /**
     *
     * @var string
     */
    protected $_language = 'pl';
    
    /**
     *
     * @var int 
     */
    protected $_superRole = 4;
    
    /**
     *
     */
    abstract public function __construct();
    
    /**
     * 
     * @return Core_Acl_Abstract
     */
    abstract public static function getInstance();
    
    /**
     * 
     * @param Core_Acl_Abstract
     */
    abstract public function setInstance($instance);
    
    /**
     * 
     * @return bool
     */
    public function issetInstance(){
        return self::$_instance !== null;
    }
    
    /**
     * @return string
     */
    abstract public function getDefaultRoleId();
    
    /**
     * @return string
     */
    abstract public function getSuperRoleId();
    
    /**
     * @return \Core_Acl_Abstract
     */
    abstract public function digestRoles();
    
    /**
     * @return \Core_Acl_Abstract
     */
    abstract public function digestResources();
    
    /**
     * @return \Core_Acl_Abstract
     */
    abstract public function digestAclRules();
    
    /**
     * @param int $role
     * @return array
     */
    abstract public function getAclTree($role);
    
    /**
     * @param array $data
     * @param int $role
     * @return bool
     */
    abstract public function saveAclRules($data, $role);
    
    /**
     * 
     * @return \Core_Acl_Abstract
     */
    public function setInitialAclRules(){
        return $this;
    }
    
    /**
     * 
     * @param int $role
     * @return \Core_Acl_Abstract
     */
    public function setDefaultRole($role){
        $this->_defaultRole = $role;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getDefaultRole(){
        return $this->_defaultRole;
    }
    
    
    /**
     *      
     * @param type $role
     * @return \Core_Acl_Abstract
     */
    public function setSuperRole($role){
        $this->_superRole = $role;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getSuperRole(){
        return $this->_superRole;
    }
    
    /**
     * 
     * @param Core_Acl_Processor_Resources_Interface $processor
     * @return \Core_Acl_Abstract
     */
    public function setResourcesProcessor(Core_Acl_Processor_Resources_Interface $processor){
        $this->_resourcesProcesor = $processor;
        return $this;
    }
    
    /**
     * 
     * @return Core_Acl_Processor_Resources_Interface
     */
    public function getResourcesProcessor(){
        return $this->_resourcesProcesor;
    }

    /**
     * 
     * @param Core_Acl_Processor_Roles_Interface $processor
     * @return \Core_Acl_Abstract
     */
    public function setRolesProcessor(Core_Acl_Processor_Roles_Interface $processor){
        $this->_rolesProcesor = $processor;
        return $this;
    }
    
    /**
     * 
     * @return Core_Acl_Processor_Roles_Interface
     */
    public function getRolesProcessor(){
        return $this->_rolesProcesor;
    }
    
    /**
     * 
     * @param Core_Acl_Processor_Roles_Interface $processor
     * @return \Core_Acl_Abstract
     */
    public function setAclRulesProcessor(Core_Acl_Processor_AclRules_Interface $processor){
        $this->_aclProcesor = $processor;
        return $this;
    }
    
    /**
     * 
     * @return Core_Acl_Processor_AclRules_Interface
     */
    public function getAclRulesProcessor(){
        return $this->_aclProcesor;
    }
    
    /**
     * 
     * @param string $lang
     * @return \Core_Acl_Abstract
     */
    public function setLanguage($lang){
        $this->_language = $lang;
        return $this;
    }
    
    /**
     * 
     * @return \Core_Acl_Abstract
     */
    public function digest(){
        $this->digestRoles()
                ->digestResources()
                ->digestAclRules();
        
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getLanguage(){
        return $this->_language;
    }
    
}
