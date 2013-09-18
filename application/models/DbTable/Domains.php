<?php

class Application_Model_DbTable_Domains extends Core_Model_Db_Table_Abstract{
    /**
     *
     * @var string 
     */
    protected $_name = 'language_domains';
    
    /**
     *
     * @var string
     */
    protected $_primary = 'id';
    
    /**
     *
     * @var string 
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_Domain';    
        
    /**
     *
     * @var array 
     */
    protected $_dependentTables = array(
        
    );
    
    /**
     * 
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return Zend_Db_Table_Select
     */
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null) {        
        $query = $this->select(true)
                ->setIntegrityCheck(false)
                ->limit($offset, $limit)
                ;       
        
        if(isset($filters['domain']) && !empty($filters['domain'])){
            $query->where('domain LIKE ?', $filters['domain'].'%');
        }
        
        if(isset($filters['layout']) && !empty($filters['layout'])){
            $query->where('layout LIKE ?', $filters['layout'].'%');
        }
        
        if(isset($filters['lang']) && !empty($filters['lang'])){
            $query->where('lang = ?', $filters['lang']);
        }
        
        if(isset($filters['active']) && ((int)$filters['active'] == 1 || (int)$filters['active'] == 0)){
            $query->where('active = ?', $filters['active']);
        }
        
        if(!empty($sort)){
           $query->order($sort);
        }
        
        return $query;
    }
    
}
