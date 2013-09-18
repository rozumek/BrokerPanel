<?php

class Application_Model_DbTable_Roles extends Core_Model_Db_Table_Abstract
{
    /**
     *
     * @var string 
     */
    protected $_name = 'roles';
    
    /**
     *
     * @var string
     */
    protected $_primary = 'id';
    
    
    /**
     *
     * @var string
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_Role';
    
    /**
     *
     * @var array 
     */
    protected $_dependentTables = array(
        'Application_Model_DbTable_Users',
        'Application_Model_DbTable_Acl'
    );
    
    /**
     *
     * @var array 
     */
    protected $_referenceMap = array(
        'Parent' => array(
            'columns'           => array('parent'),
            'refTableClass'     => 'Application_Model_DbTable_Roles',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,            
        )
    );
    
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null) 
    {
        $query = $this->select(true)
                ->setIntegrityCheck(false)
                ->joinLeft(array('p'=>'roles'), 'p.id = '.$this->_name.'.parent', 'p.name as parentname')
                ->limit($offset, $limit)
                ; 
         
        if(isset($filters['name']) && !empty($filters['name'])){
            $query->where('LOWER('.$this->_name.'.name) LIKE ?', '%'.strtolower($filters['name']).'%');
        }        
        
        if(isset($filters['parent']) && (int)$filters['parent'] > 0){
            $query->where($this->_name.'.parent = ?', $filters['parent']);
        }

        if(!empty($sort)){
            $query->order($sort);
        }                
        
        return $query;
    }
}

