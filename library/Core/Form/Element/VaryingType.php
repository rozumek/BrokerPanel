<?php

class Core_Form_Element_VaryingType extends Zend_Form_Element {
    
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formText';
    
    /**
     * Array of options for multi-item
     * @var array
     */
    public $options = array();
    
    /**
     *
     * @var string
     */
    protected $_defaultType = 'string';
    
    /**
     *
     * @var string
     */
    protected $_defaultHelperType = 'formText';
    
    /**
     *
     * @var array
     */
    protected $_typeMap = array(
        'bool' => array(
            'type' => 'formSelect',
            'options' => array(
                    '0' => 0,
                    '1' => 1,
                ),              
            ),
        'string'  => array(
            'type' => 'formText',
            'options' => array(),            
        )
    );
    
    /**
     * 
     * @param string $type
     * @return \Core_Form_Element_VaryingType
     */
    public function setDefaultType($type){
        $this->_defaultType = $type;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getDefaultType(){
        return $this->_defaultType;
    }
    
    /**
     * 
     * @return string
     */
    public function getDefaultHelperType(){
        return $this->_defaultHelperType;
    }
    
    /**
     * 
     * @param string $type
     * @return \Core_Form_Element_VaryingType
     */
    public function setDefaultHelperType($type){
        $this->_defaultHelperType = $type;
        return $this;
    }


    /**
     * 
     * @param string $type
     * @return \Core_Form_Element_VaryingType
     */
    public function setType($type){
        if(key_exists($type, $this->_typeMap)){
            $this->helper = Core_Array::get($this->_typeMap[$type], 'type', $this->_defaultHelperType);
            $this->options = Core_Array::get($this->_typeMap[$type], 'options', array());
        }
        
        return $this;
    }
    
}
