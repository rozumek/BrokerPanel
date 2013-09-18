<?php

class Application_Model_DbTable_Row_AccountRequest extends Core_Model_Db_Table_Row_Abstract
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
     * @return Application_Model_DbTable_Row_AccountRequest
     */
    public function setId($id)
    {
        $this->id = (int)$id;
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
     * @return \Application_Model_DbTable_Row_AccountRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @return \Application_Model_DbTable_Row_AccountRequest
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }
       
}
