<?php

abstract class Core_Model_Db_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract implements Core_Model_Interface{
    
    /**
     *
     * @var string 
     */
    protected $_lastMessage = null;
    
    /**
     *
     * @var Zend_Exception 
     */
    protected $_lastException = null;                
    
    /**
     * 
     * @return string
     */
    public function getLastMessage(){
        return $this->_lastMessage;
    }
    
    /**
     * 
     * @return Zend_Exception
     */
    public function getLastException(){
        return $this->_lastException;
    }
    
    /**
     * 
     * @return mixed
     */
    public function save() {
        try{
            return parent::save();
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        
        return false;
    }
    
    public function delete() {
        try{
            return parent::delete();
        }catch(Zend_Exception $e){
            $this->_lastMessage = $e->getMessage();
            $this->_lastException = $e;
        }
        
        return false;
    }
    
    /**
     * 
     * @return array
     */
    public function toArray() {
        $array = array();        
        foreach($this->_data as $key => $value){
            try{
                if(($decodedValue = Zend_Json::decode($value))!== null){
                   $value = $decodedValue;
                }   
            }catch(Zend_Exception $e){/* does nothing */}
            
            $array[$key] = $value;
        }
        
        return $array;
    }
    
    /**
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDbAdapter(){
        return $this->getTable()
                ->getAdapter();
    }
    
}
