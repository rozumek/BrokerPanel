<?php

class Core_User_Identity extends Core_User_Identity_Abstract{
    
    /**
     *
     * @var int 
     */
    protected $id =  null;

    /**
     *
     * @var string 
     */
    protected $username = null;
    
    /**
     *
     * @var string 
     */
    protected $name = null;
    
    /**
     *
     * @var string 
     */
    protected $email =  null;
    
    /**
     *
     * @var int 
     */
    protected $role = null;
    
    /**
     *
     * @var int 
     */
    protected $active = null;
    
    /**
     *
     * @var string 
     */
    protected $created = null;
    
    /**
     *
     * @var string 
     */
    protected $rolename = null;
    
    /**
     * 
     * @return int
     */
    public function getId(){
        return (int)$this->id;
    }
    
    /**
     * 
     * @return string
     */
    public function getUsername(){
        return $this->username;
    }
    
    /**
     * 
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * 
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }
    
    /**
     * 
     * @return string
     */
    public function getRole(){
        return $this->role;
    }
    
    /**
     * 
     * @return string
     */
    public function getCreatedDate(){
        return $this->created;
    }

    /**
     * 
     * @return bool
     */
    public function isActive(){
        return (bool)$this->active;
    }
    
    /**
     * 
     * @return string
     */
    public function getRoleName(){
        return $this->rolename;
    }
    
    /**
     * 
     * @return Core_User_Identity
     */
    public static function getLogged() {
        if(Zend_Auth::getInstance()->hasIdentity()){
            $identityData = Zend_Auth::getInstance()
                    ->getIdentity();
            
            return new self((array)$identityData);
        }
        
        return new self();
    }
    
}
