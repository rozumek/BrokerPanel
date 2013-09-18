<?php

class Application_Model_DbTable_Resources extends Core_Model_Db_Table_Abstract{
    /**
     *
     * @var string 
     */
    protected $_name = 'resources';
    
    /**
     *
     * @var string
     */
    protected $_primary = 'id';
    
    /**
     *
     * @var string 
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_Resource';
    
    /**
     *
     * @var array 
     */
    protected $_dependentTables = array(
        'Application_Model_DbTable_Acl',
        'Application_Model_DbTable_MenuItems'
    );
    
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null) 
    {
        $query = $this->select(true)
                ->setIntegrityCheck(false)                
                ->limit($offset, $limit)
                ; 
                       
        if(isset($filters['module']) && !empty($filters['module'])){
            $query->where('module = ?', $filters['module']);
        }                
        if(isset($filters['controller']) && !empty($filters['controller'])){
            $query->where('controller = ?', $filters['controller']);
        }                

        if(!empty($sort)){
           $query->order($sort);
        }  
        
        return $query;
    }
    
}
