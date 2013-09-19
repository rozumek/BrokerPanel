<?php

class Application_Model_Users extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface
{
    
    /**
     *
     * @var int 
     */
    protected static $_defaultRole = 3;
    
    /**
     *
     * @var int 
     */
    protected static $_defaultActiveState = 0;
        
    /**
     * 
     */
    public function __construct() 
    {
        $this->_dbTable = new Application_Model_DbTable_Users();
    }
    
    /**
     * 
     * @param int $role     
     */
    public static  function setDefaultRole($role)
    {
        self::$_defaultRole = $role;        
    }
    
    /**
     * 
     * @param int $state     
     */
    public static function setDefaultActiveState($state)
    {
        self::$_defaultActiveState = $state;        
    }
    
    /**
     * 
     * @return int
     */
    public static function getDefaultRole()
    {
        return self::$_defaultRole ;
    }
    
    /**
     * 
     * @return int
     */
    public static function getDefaultActiveState()
    {
        return self::$_defaultActiveState;
    }
    
    /**
     * 
     * @param type $id
     * @return Application_Model_DbTable_Row_User | null
     */
    public function getUser($id)
    {
        return $this->get($id);
    }
    
    /**
     * 
     * @param string $name
     * @return Application_Model_DbTable_Row_User | null
     */
    public function getUserByUsername($name, $returnEmpty=false)
    {
        return $this->getFirstWhere('username=?', $name, $returnEmpty);
    }

    /**
     * 
     * @param int $id
     * @return bool
     */
    public function userExists($id)
    {
        if((int) $id > 0){
            return $this->exists($id);
        }else if(is_string($id)){
            return (bool) $this->getUserByUsername($id);
        }
        
        return false;
    }    
    
    /**
     * 
     * @param int $id
     */
    public function deleteUser($id)
    {
        return $this->delete($id);
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    public function changeState($id)
    {
        $user = $this->getUser($id);
        if($user instanceof Application_Model_DbTable_Row_User){            
            $user->setActive(!$user->getActive());
            return $this->save($user);
        }
        
        return false;
    }
    
    /**
     * 
     * @param bool $state
     * @param int $id
     * @return boolean
     */
    public function setUserState($state, $id)
    {
        $user = $this->getUser($id);
        if($user instanceof Application_Model_DbTable_Row_User){
            $user->setActive((int)$state);
            return $this->save($user);           
        }
        
        return false;
    }
    
    /**
     * 
     * @param array|object $data
     * @return boolean
     */
    public function createUser($data)
    {
        return $this->create($data);
    }
    
    /**
     * 
     * @param array|object $data
     * @return boolean
     */
    public function create($data)
    {
        $user = $this->getEmptyRow();
        
        if($user instanceof Application_Model_DbTable_Row_User){   
            $data = (array)$data;
            $user->setUserName($data['username']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);              
            $user->setRole(Core_Array::get($data, 'role', self::$_defaultRole));
            $user->setActive(Core_Array::get($data, 'active', self::$_defaultActiveState));    
            
            if(!empty($data['parent'])){
                $user->setParent($data['parent']);
            }
            
            return $this->save($user);            
        }
        
        return false;
    }
    
     /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateUser(array $data, $id)
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
        $user = $this->get($id);
        
        if($user instanceof Application_Model_DbTable_Row_User){
            $data = (array)$data;
            $user->setUserName($data['username']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            
            if(!empty($data['password'])){
                $user->setPassword($data['password']);
            }
            if(!empty($data['role'])){
                $user->setRole($data['role']);
            }            
            if(!empty($data['active'])){
                $user->setActive($data['active']);
            }            
            if(!empty($data['parent'])){
                $user->setParent($data['parent']);
            }else if($data['parent'] === null){
                $user->setParent(null);
            }
            
            return $this->save($user);
        }
        
        return false;
    }
    
    /**
     * 
     * @param type $data
     * @return type
     */
    public function generateActivationCode($data){
        if(isset($data['id']) && (int)$data['id'] > 0){
            if($this->userExists((int)$data['id'])){
                $code = base64_encode(serialize(array(
                    'id' => $data['id'],
                    'username' => $data['username'],                
                )));

                return $code;
            }
        }
        
        return false;
    }

    /**
     * 
     * @param string $activationCode
     * @return boolean
     */
    public function activateUser($activationCode)
    {
        $data = unserialize(base64_decode($activationCode));  
        $user = $this->getUser($data['id']);
        
        if($user instanceof Application_Model_DbTable_Row_User){
            if($user->getUserName() == $data['username'] && !$user->isActive()){
                $user->setActive(1);
                return $this->save($user);
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param string $user
     * @param string $password
     * @return boolean
     */
    public function setNewUserPassword($userName, $password)
    {
        $user = $this->getUserByUserName($userName);
        
        if($user instanceof Application_Model_DbTable_Row_User){             
            $user->setPassword($password);

            return $this->save($user);            
        }
        
        return true;
    }

    /**     
     * 
     * @param int $id
     */
    public function changeUserState($id) 
    {
        return $this->changeState($id);
    }
    
}

