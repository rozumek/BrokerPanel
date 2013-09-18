<?php

class Application_Model_Acl extends Core_Model_Db_Abstract implements Core_Acl_Processor_AclRules_Interface, Cms_Controller_Processor_Interface
{
    
    /**
     *
     * @var string
     */
    protected static $_defaultPriviledgeType = 'allow';
    
    /**
     *
     * @var array
     */
    protected static $_allowedPriviledgeType = array(
        'allow',
        'deny'
    );
    
    /**
     * 
     */
    public function __construct() 
    {
        $this->_dbTable = new Application_Model_DbTable_Acl();
    }
    
    /**
     * 
     * @param string $type
     * @return \Application_Model_Acl
     */
    public static function setDefaultPriviledgeType($type)
    {
        self::$_defaultPriviledgeType = $type;        
    }
    
    /**
     * 
     * @return string
     */
    public static function getDefaultPriviledgeType()
    {
        return self::$_defaultPriviledgeType;
    }
    
    /**
     * 
     * @param sting $type
     * @return bool
     */
    public static function isAllowedType($type)
    {
        return isset($type) && in_array($type, self::$_allowedPriviledgeType);
    }


    /**
     * 
     * @param int $id
     * @return Application_Model_DbTable_Row_Resource
     */
    public function getAclRule($id)
    {
        return $this->get($id);
    }
    
    /**
     * 
     * @param int $resource
     * @param string $role
     * @return Application_Model_Db_Table_Rowset
     */
    public function getAclRulesBy($resource, $role)
    {
        return $this->getWhere(array(
            'resource=?',
            'role=?',            
        ), array(
            $resource, 
            $role,             
        ));
    }


    /**
     * 
     * @return Application_Model_Db_Table_Rowset_Abstract
     */
    public function getAclRules()
    {
        return $this->getRowset();
    }
    
    /**
     * 
     * @param int $id
     * @return bool
     */
    public function deleteAclRule($id)
    {
        return  $this->delete($id);
    }     
    
    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function createAclRule(array $data)
    {
        return $this->create($data);
    }
    
    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function create($data)
    {
        $rule = $this->getEmptyRow();
        
        if($rule instanceof Application_Model_DbTable_Row_Acl){   
            $data = (array)$data;
            $type = self::isAllowedType($data['type'])?$data['type']:self::getDefaultPriviledgeType();
            
            $rule->setPrivilege($data['privilege'])
                    ->setType($type);  
            
            if(isset($data['description'])){
                $rule->setDescription($data['description']);
            }
            if(isset($data['resource'])){
                $rule->setResource($data['resource']);
            }
            if(isset($data['role'])){
                $rule->setRole($data['role']);
            }
            
            return $this->save($rule);            
        }
        
        return false;
    }
    
    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateAclRule(array $data, $id)
    {
        return $this->update($data, $id);
    }
    
    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $rule = $this->getAclRule($id);
        
        if($rule instanceof Application_Model_DbTable_Row_Acl){
            $data = (array)$data;            
            $type = self::isAllowedType($data['type'])?$data['type']:self::getDefaultPriviledgeType();
            
            $rule->setPrivilege($data['privilege'])
                    ->setType($type);  
            
            if(isset($data['description'])){
                $rule->setDescription($data['description']);
            }
            if(isset($data['resource'])){
                $rule->setResource($data['resource']);
            }
            if(isset($data['role'])){
                $rule->setRole($data['role']);
            }
            
            return $this->save($rule);
        }
        
        return false;
    }

    /**
     * Does nothing
     * 
     * @param type $id
     */
    public function changeState($id) 
    {
        throw new Core_Exception('Method "changeState" does nothing in Application_Model_Acl');
    }
    
}

