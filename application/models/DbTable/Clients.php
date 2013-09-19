<?php

class Application_Model_DbTable_Users extends Core_Model_Db_Table_Abstract
{
    /**
     *
     * @var string 
     */
    protected $_name = 'users';
    
    /**
     *
     * @var string 
     */
    protected $_primary = 'id';
    
    /**
     * 
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_User';
    
    /**
     *
     * @var array 
     */
    protected $_dependentTables = array(
        'Application_Model_DbTable_Users'
    );
    
    /**
     *
     * @var array 
     */
    protected $_referenceMap = array(
        'Roles' => array(
            'columns'           => array('role'),
            'refTableClass'     => 'Application_Model_DbTable_Roles',
            'refColumns'        => array('id'),
            'onDelete'          => self::RESTRICT,            
        ),
        'Parent' => array(
            'columns'           => array('parent'),
            'refTableClass'     => 'Application_Model_DbTable_Users',
            'refColumns'        => array('id'),
            'onDelete'          => self::RESTRICT,            
        )
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
                ->joinInner('roles', 'roles.id = '.$this->_name.'.role', 'roles.name as rolename')
                ->limit($offset, $limit)
                ;        
        
        if(isset($filters['id']) && !empty($filters['id'])){
            if(is_array($filters['id'])){
                $query->where($this->_name.'.id IN ('. implode(',', $filters['id']).')');
            }else{
                $query->where($this->_name.'.id = ?', $filters['id']);
            }
        }
        
        if(isset($filters['username']) && !empty($filters['username'])){
            $query->where('LOWER(username) LIKE ?', '%'.strtolower($filters['username']).'%');
        }
        
        if(isset($filters['name']) && !empty($filters['name'])){
            $query->where('LOWER(name) LIKE ?', '%'.strtolower($filters['name']).'%');
        }
        
        if(isset($filters['email']) && !empty($filters['email'])){
            $query->where('LOWER(email) LIKE ?', '%'.strtolower($filters['email']).'%');
        }
        
        if(isset($filters['active']) && ($filters['active'] == '1' || $filters['active'] == '0')){
            $query->where('active = ?', $filters['active']);
        }
        
        if(isset($filters['role']) && !empty($filters['role'])){
            $query->where('role = ?', $filters['role']);
        }
        
        if(isset($filters['not_role']) && !empty($filters['not_role'])){
            $query->where('role != ?', $filters['not_role']);
        }   
        
        if(isset($filters['parent']) && !empty($filters['parent'])){
            $query->where($this->_name.'.parent = ?', $filters['parent']);
        }                
        
        if(!empty($sort)){
            $query->order($sort);
        }                
        
        return $query;
    }
    
}

