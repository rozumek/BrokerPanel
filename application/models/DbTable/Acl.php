<?php

class Application_Model_DbTable_Acl extends Core_Model_Db_Table_Abstract{
    /**
     *
     * @var string 
     */
    protected $_name = 'acl';
    
    /**
     *
     * @var string
     */
    protected $_primary = 'id';
    
    /**
     *
     * @var string 
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_Acl';    
    
    /**
     *
     * @var array 
     */
    protected $_referenceMap = array(
        'Roles' => array(
            'columns'           => array('role'),
            'refTableClass'     => 'Application_Model_DbTable_Roles',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE,
        ),
        'Resources' => array(
            'columns'           => array('resource'),
            'refTableClass'     => 'Application_Model_DbTable_Resources',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,  
            'onUpdate'          => self::CASCADE,
        )
    );
    
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null) 
    {
        $query = $this->select(true)
                ->setIntegrityCheck(false)  
                ->joinInner(
                        'resources', 
                        'resources.id = '.$this->_name.'.resource', 
                        array(
                            $this->_name.'.id as id',
                            'controller',
                            'module'
                        )
                    )
                ->joinLeft(
                        'roles', 
                        'roles.id = '.$this->_name.'.role', 
                        'roles.name as rolename'
                    )
                ->limit($offset, $limit)
                ; 
         
        if(isset($filters['resource']) && !empty($filters['resource'])){
            $query->where('resource = ?', $filters['resource']);
        }                
        if(isset($filters['role']) && !empty($filters['role'])){
            $query->where('role = ?', $filters['role']);
        }                
        if(isset($filters['type']) && !empty($filters['type'])){
            $query->where('type = ?', $filters['type']);
        }                
        if(isset($filters['privilege']) && !empty($filters['privilege'])){
            $query->where('privilege = ?', $filters['privilege']);
        }                

        if(empty($sort)){        
            $sort = $this->_name.'.role';
        }  
        
        $query->order($sort);
        
        return $query;
    }
    
}
