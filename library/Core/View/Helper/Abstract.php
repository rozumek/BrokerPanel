<?php

abstract class Core_View_Helper_Abstract extends Zend_View_Helper_Abstract
{
    
    /**
     *
     * @var string
     */
    protected static $_defaultFormElementHelper = 'formText';
    
    /**
     *
     * @var string
     */
    protected $_jsHelperPath = '/js/common/helpers/';
    
    /**
     *
     * @var array
     */
    protected $_forceHelper = array();
    
    /**
     *
     * @var array
     */
    protected $_defaultAttribs = array();
    
    /**
     * 
     * @param string $type
     * @param string $name
     * @param string $value
     * @param string $attribs
     * @param string $options
     * @return string
     */
    protected function  _invokeHelper($type, $name, $value, $attribs=array(), $options=null, $other=null)
    {
        $helper = 'form'.ucfirst($this->_forceHelperType($type));
        
        try{
            $helperObj = $this->getView()->getHelper($helper);
        }catch(Zend_Exception $e){
            $helper = self::$_defaultFormElementHelper;
            $helperObj = $this->getView()->getHelper(self::$_defaultFormElementHelper);
        }
        
        return $helperObj->$helper($name, $value, $attribs, $options, $other);            
    }
    
    /**
     * 
     * @return Zend_View
     */
    public function getView()
    {
        return $this->view;
    }
    
    /**
     * 
     * @param string $helper
     * @return string
     */
    protected function _forceHelperType($helper)
    {
        if(key_exists($helper, $this->_forceHelper)){
            return $this->_forceHelper[$helper];
        }
        
        return $helper;
    }
    
     /**
     * 
     * @param array $attribs
     * @return array
     */
    protected function _mergeAttribs($attribs)
    {
        foreach($this->_defaultAttribs as $name => $value){
            if(isset($attribs[$name])){
                $attribs[$name] = $value.' '.$attribs[$name];
            }else{
                $attribs[$name] = $value;
            }
        }
        
        return $attribs;
    }
    
    /**
     * 
     * @param int $start
     * @param int $stop
     * @return array
     */
    protected function _rangeArray($start, $stop, $first=null)
    {
        $range = array(); 
        
        if($first !== null){
            $range[''] = $first;
        }
        
        foreach(range($start, $stop) as $d){
            $range[$d] = $d;
        }
        
        return $range;
    }
    
}
