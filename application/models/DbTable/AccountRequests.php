<?php

class Application_Model_DbTable_AccountRequests extends Core_Model_Db_Table_Abstract
{
    /**
     *
     * @var string 
     */
    protected $_name = 'user_account_requests';
    
    /**
     *
     * @var string 
     */
    protected $_primary = 'id';
    
    /**
     * 
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_AccountRequest';
    
    /**
     *
     * @var array 
     */
    protected $_dependentTables = array(
        
    );
    
    /**
     *
     * @var array 
     */
    protected $_referenceMap = array(
        
    );
    
    /**
     * 
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return Zend_Db_Table_Select
     */
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null) 
    {
        $query = $this->select(true)
                ->setIntegrityCheck(false)
                ->limit($offset, $limit)
                ;                     
               
        if(isset($filters['email']) && !empty($filters['email'])){
            $query->where('LOWER(email) LIKE ?', '%'.strtolower($filters['email']).'%');
        }
        
        $query->where('email NOT IN (SELECT email FROM users)');
        
        if(!empty($sort)){
            $query->order($sort);
        }                
        
        return $query;
    }
    
}

