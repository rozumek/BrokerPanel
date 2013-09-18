<?php

class Application_Model_DbTable_Routes extends Core_Model_Db_Table_Abstract{
    /**
     *
     * @var string 
     */
    protected $_name = 'routes';
    
    /**
     *
     * @var string
     */
    protected $_primary = 'id';
    
    /**
     *
     * @var string
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_Route';
    
    /**
     *
     * @var array 
     */
    protected $_dependentTables = array(
        'Application_Model_DbTable_MenuItems',        
    );
    
    /**
     * 
     * @param type $filters
     * @param type $sort
     * @param type $limit
     * @param type $offset
     * @return type
     */
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null) {
        $query = $this->select(true)
                ->setIntegrityCheck(false)                
                ->limit($offset, $limit)
                ; 
         
        if(isset($filters['routename']) && !empty($filters['routename'])){
            $query->where('LOWER(routename) LIKE ?', '%'.strtolower($filters['routename']).'%');
        }
        
        if(isset($filters['route']) && !empty($filters['route'])){
            $query->where('LOWER(route) LIKE ?', '%'.strtolower($$filters['route']).'%');
        }
        

        if(!empty($sort)){
            $query->order($sort);
        }         
        return $query;
    }
}
