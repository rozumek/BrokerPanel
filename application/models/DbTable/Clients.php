<?php

class Application_Model_DbTable_Clients extends Core_Model_Db_Table_Abstract
{
    /**
     *
     * @var string 
     */
    protected $_name = 'clients';
    
    /**
     *
     * @var string 
     */
    protected $_primary = 'id';
    
    /**
     * 
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_Client';
    
    /**
     *
     * @var array 
     */
    protected $_dependentTables = array(
        'Application_Model_DbTable_Clients'
    );
    
    /**
     *
     * @var array 
     */
    protected $_referenceMap = array(
        'Broker' => array(
            'columns'           => array('role'),
            'refTableClass'     => 'Application_Model_DbTable_Users',
            'refColumns'        => array('id'),
            'onDelete'          => self::RESTRICT,            
        ),
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
                ->joinInner('users', 'users.id = '.$this->_name.'.broker', 'users.name as broker_name')
                ->limit($offset, $limit)
                ;        
        
        if(isset($filters['id']) && !empty($filters['id'])){
            if(is_array($filters['id'])){
                $query->where($this->_name.'.id IN ('. implode(',', $filters['id']).')');
            }else{
                $query->where($this->_name.'.id = ?', $filters['id']);
            }
        }
        
        if(isset($filters['name']) && !empty($filters['name'])){
            $query->where('LOWER(clients.name) LIKE ?', '%'.strtolower($filters['name']).'%');
        }
        
        if(isset($filters['email']) && !empty($filters['email'])){
            $query->where('LOWER(email) LIKE ?', '%'.strtolower($filters['email']).'%');
        }              
        
        if(isset($filters['broker']) && !empty($filters['broker'])){
            $query->where('broker = ?', $filters['broker']);
        }                            
        
        if(!empty($sort)){
            $query->order($sort);
        }                
        
        return $query;
    }
    
}

