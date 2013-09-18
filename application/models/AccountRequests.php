<?php

class Application_Model_AccountRequests extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface
{
      
    /**
     * 
     */
    public function __construct() 
    {
        $this->_dbTable = new Application_Model_DbTable_AccountRequests();
    }
       
    /**
     * 
     * @param type $id
     * @return Application_Model_DbTable_Row_AccountRequest | null
     */
    public function getAccountRequest($id)
    {
        return $this->get($id);
    }
       
    /**
     * 
     * @param int $id
     * @return bool
     */
    public function accountRequestExists($id)
    {
        if((int) $id > 0){
            return $this->exists($id);
        }
        
        return false;
    }    
    
    /**
     * 
     * @param int $id
     */
    public function deleteAccountRequest($id)
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
        throw new Exception('Unsupported action');
    }
      
    /**
     * 
     * @param array|object $data
     * @return boolean
     */
    public function createAccountRequest($data)
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
        $accountReq = $this->getEmptyRow();
        
        if($accountReq instanceof Application_Model_DbTable_Row_AccountRequest){   
            $data = (array)$data;
                        
            $accountReq->setEmail($data['email']);

            return $this->save($accountReq);            
        }
        
        return false;
    }
    
     /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateAccountRequest(array $data, $id)
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
        $accountReq = $this->get($id);
        
        if($accountReq instanceof Application_Model_DbTable_Row_AccountRequest){
            $data = (array)$data;
            
            $accountReq->setEmail($data['email']);                       
            
            return $this->save($accountReq);
        }
        
        return false;
    }
        
}

