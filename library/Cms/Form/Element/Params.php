<?php

/**
 * Description of Parameters
 *
 * @author Marcinek
 */
class Cms_Form_Element_Params extends Zend_Form_Element{

    /**
     *
     * @var string
     */
    public $helper = 'formParamsMatrix';
    
    /**
     *
     * @var type 
     */
    protected $_dynamic = true;    
    
    /**
     * 
     * @param type $key
     * @return mixed
     */
    public function __get($key) {
        if($key == 'options'){
            return $this->_getViewHelperElementsOptions();
        }else{
            return $this->{$key};
        }
        
    }
    
    /**
     * 
     * @param bool $state
     * @return \Cms_Form_Element_Params
     */
    public function setDynamic($state){
        $this->_dynamic =(bool)$state;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    protected function _getViewHelperElementsOptions(){
        return array(
            'dynamic' => $this->getDynamic()
        );
    }

        /**
     * 
     * @return bool
     */
    public function getDynamic(){
        return (bool)$this->_dynamic;
    }

    /**
     * 
     * @param type $value
     * @return \Cms_Form_Element_Params
     */
    public function setValue($value) {        
        $this->_value = (!is_array($value))?Zend_Json::decode($value):$value;
        return $this;
    }
        
}
