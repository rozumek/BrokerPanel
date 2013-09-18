<?php

abstract class Core_User_Identity_Abstract extends Core_Mapper
{
 
    /**
     *
     * @var \Core_User_Identity_Abstract 
     */
    protected $_backupIdentity = null;
    
    /**
     * 
     * @param \Core_User_Identity_Abstract $identity
     * @return \Core_User_Identity_Abstract
     */
    public function setBackUpIdentity($identity){
        $this->_backupIdentity = $identity;
        return $this;
    }

    /**
     * 
     * @return \Core_User_Identity_Abstract
     */
    public function backUpIdentity(){
        $this->setBackUpIdentity($this);
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Identity_Abstract
     */
    public function getBackUpIdentity(){
        return $this->_backupIdentity;
    }
    
    /**
     * 
     * @return \Core_User_Identity_Abstract
     */
    public function clearBackUpIdentity(){
        $this->_backupIdentity = null;
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function isIdentityBackedUp(){
        return $this->_backupIdentity instanceof Core_User_Identity_Abstract;
    }
    
}
