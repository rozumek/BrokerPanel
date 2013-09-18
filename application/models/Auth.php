<?php

class Application_Model_Auth extends Core_Model_Abstract
{
    
    /**
     *
     * @var Zend_Auth_Adapter_DbTable 
     */
    protected $_authAdapter = null;
    
    /**
     *
     * @var Zend_Auth 
     */
    protected $_auth = null;    

    /**
     *
     * @var array 
     */
    protected $_identityColumns = array(
        'id',
        'username', 
        'email',         
        'active',
        'created',
        'role'        
    );
    
    /**
     *
     * @var Core_User_Identity
     */
    protected $_identity = null;
    
    /**
     *
     * @var string
     */
    protected $_identityClass = 'Core_User_Identity';
    
    
    public function __construct(Zend_Auth $adapter) {
        $this->_auth = $adapter;
        $this->_authAdapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table::getDefaultAdapter(),
            'users',
            'username',
            'password',
            'md5(?) AND active = 1'    
        );
    }
    
    /**
     *
     * @param string $username
     * @param string $password
     * @return boolean 
     */
    public function authenticate($username, $password){
        $this->_authAdapter->setIdentity($username);
        $this->_authAdapter->setCredential($password);        
        
        try{
            $result = $this->_authAdapter->authenticate();
            if ($result->isValid()) {            
                $this->_auth->getStorage()->write(
                    $this->_authAdapter->getResultRowObject($this->_identityColumns)
                );
                return true;            
            }else{
               $this->_lastMessage = $result->getMessages();              
            }
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        
        return false;        
    }
    
    /**
     *
     * @return void 
     */
    public function logout(){
        try{
            $this->_auth->clearIdentity();
            $this->_identity = null;
            return true;
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        
        return false;
    }
    
    /**
     *
     * @return bool 
     */
    public function isLoggedIn(){
        return $this->_auth->hasIdentity();
    }
    
    /**
     * 
     * @return Zend_Auth_Adapter_DbTable
     */
    public function getAuthAdapter(){
        return $this->_authAdapter;
    }
    
    /**
     * 
     * @return Zend_Auth
     */
    public function getAuth(){
        return $this->_auth;
    }
    
    /**
     * 
     * @return Core_User_Identity
     */
    public function getIdenity(){
        if($this->isLoggedIn() && $this->_identity === null){
            $authIdenity =  (array)$this->_auth->getIdentity();
            $this->_identity = new $this->_identityClass($authIdenity);
        }
        
        return $this->_identity;
    }
    
    /**
     * 
     * @param array|stdClass $identity
     * @return \Core_Model_Auth
     */
    public function setIdentity($identity){        
        $this->_auth->getStorage()
                ->write((object)$identity);        
        $this->_identity = new $this->_identityClass($identity);
        return $this;
    }

    /**
     * 
     * @param string $className
     * @return \Core_User_Auth
     */
    public function setIdentityClass($className){
        $this->_identityClass = $className;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getIdentityClass(){
        return $this->_identityClass;
    }
    
    /**
     * 
     * @return \Core_Model_Auth
     */
    public function resetIdentity(){
        $this->_identity = null;
        return $this;
    }
}
