<?php

abstract class Core_Model_Db_Abstract extends Core_Model_Abstract implements Core_Model_Db_PaginatorList_Interface{
    
    /**
     *
     * @var Core_Model_Db_Table_Abstract 
     */
    protected $_dbTable = null;
    
    /**
     *
     * @var Zend_Paginator 
     */
    protected $_paginator = null;
        
    /**
     *
     * @var Core_Model_Db_Table_Row_Abstract
     */
    protected $_lastInsertedRow = null;
    
    /**
     * 
     * @param int $id
     * @return Core_Model_Db_Table_Row_Abstract|null
     */
    public function get($id){
        try{
            return $this->_dbTable->find($id)->current();
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        return null;
    }
    
    /**
     * 
     * @return Core_Model_Db_Table_Row_Abstract
     */
    public function getEmptyRow(){
        return $this->_dbTable->createRow();
    }
    
    /**
     * 
     * @param type $id
     * @return Core_Model_Db_Table_Row_Abstract
     */
    public function getOrEmpty($id){
        $item = $this->get($id);
        
        if(!$item){
            return $this->_dbTable->createRow();
        }
        
        return $item;
    }
    
    /**
     * 
     * @param int $id
     * @return bool
     */
    public function exists($id){
        return (bool)$item = $this->get($id);
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id){
        $item = $this->get($id);
        
        if($item instanceof Core_Model_Db_Table_Row_Abstract){
            if($item->delete()){
                return true;
            }else{
                $this->_lastMessage = $item->getLastMessage();
                $this->_lastException = $item->getLastException();
            }
        }else{
            $this->_lastMessage = 'RowID_'.$id.'_DoesNotExists';
        }
        
        return false;
    }
    
    /**
     * 
     * @param Core_Model_Db_Table_Row_Abstract $item
     */
    public function save(Core_Model_Db_Table_Row_Abstract &$item){                
        if($item->save()){
            $this->_lastInsertedRow = $item;
            return true;
        }else{
            $this->_lastMessage = $item->getLastMessage();
            $this->_lastException = $item->getLastException();
        }        
        
        return false;
    }
    
    /**
     * 
     * @param string $where
     * @param mixed $value
     * @param bool $returnEmpty
     * @return Core_Model_Db_Table_Row_Abstract | null
     */
    public function getFirstWhere($where, $values, $returnEmpty=false){
        try{
            $item = $this->getWhere($where, $values)
                    ->current();
            
            if(!($item instanceof Core_Model_Db_Table_Row_Abstract)){
                if($returnEmpty === true){
                    $item = $this->getEmptyRow();
                } else {
                    return null;
                }
            }
            
            return $item;
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        
        return null;
    }
    
    /**
     * 
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return \Core_Model_Db_Table_Rowset
     */
    public function getRowset($filters=array(), $sort=null, $limit=null, $offset=0){
        try{
            $rowset = $this->_dbTable->fetchItems($filters, $sort, $limit, $offset);
            return $rowset;
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        
        return new Core_Model_Db_Table_Rowset();
    }
    
    /**
     * 
     * @param string|array $where
     * @param string|array $values
     * @param type $order
     * @param type $limit
     * @param type $offset
     * @return \Core_Model_Db_Table_Rowset
     */
    public function getWhere($where=null, $values=null, $order=null, $limit=null, $offset=0){
        try{
            $query = $this->_dbTable->select()->order($order); 
            if($where !== null && !is_array($where)){
                $where = array($where);
            }        
            if($values !== null && !is_array($values)){
                $values = array($values);
            }  
            if(is_array($where)){
                foreach($where as $key => $cond){
                    if(key_exists($key, $values) && $values[$key] !== null){
                        $query->where($cond, $values[$key]);
                    }else if(key_exists($key, $values) && $values[$key] === null && stristr($cond, '=')){                        
                        $explode = explode('=', $cond);
                        if($explode > 1){
                            $cond = trim($explode[0]).' IS NULL';
                        }
                    }else{
                        $query->where($cond);
                    }
                }
            }
            
            $rowset = $this->_dbTable->fetchAll($query, $order, $limit, $offset);        
            return $rowset;                
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        
        return new Core_Model_Db_Table_Rowset();
    }
    
    /**
     * 
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return Zend_Paginator
     */
    public function getPaginatorList($filters = array(), $sort=null, $limit=null, $offset=0){
        try{
            $select = $this->_dbTable->getQuery($filters, $sort, $limit, $offset);
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);        
            $this->_paginator = new Zend_Paginator($adapter);
        }catch(Zend_Exception $e){
            $this->_paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Null());
            $this->_lastException = $e;
            $this->_lastMessage = $e->getMessage();
        }
        
        return $this->_paginator;
    }
    
    /**
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDbAdapter(){
        return $this->_dbTable->getAdapter();
    }
    
    /**
     * 
     * @return Core_Model_Db_Table_Abstract
     */
    public function getDbTable(){
        return $this->_dbTable;
    }

    /**
     * 
     * @return Core_Model_Db_Table_Row_Abstract|null
     */
    public function getLastInsertedRow(){
        return $this->_lastInsertedRow;
    }
}
