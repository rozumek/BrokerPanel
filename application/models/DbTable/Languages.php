<?php

class Application_Model_DbTable_Languages extends Core_Model_Db_Table_Abstract{
    /**
     *
     * @var string 
     */
    protected $_name = 'languages';
    
    /**
     *
     * @var string
     */
    protected $_primary = 'id';
    
    /**
     *
     * @var string 
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_Language';    
        
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
        
        if(isset($filters['language']) && !empty($filters['language'])){
            $query->where('LOWER(language) LIKE ?', strtolower($filters['language']).'%');
        }
        
        if(isset($filters['shortcode']) && !empty($filters['shortcode'])){
            $query->where('LOWER(shortcode) LIKE ?', strtolower($filters['shortcode']).'%');
        }
        
        if(isset($filters['code']) && !empty($filters['code'])){
            $query->where('LOWER(code) = ?', strtolower($filters['code']).'%');
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
