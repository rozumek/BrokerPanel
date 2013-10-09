<?php

class Core_Validate_Db_RecordExists extends Zend_Validate_Db_RecordExists
{
    /**
     *
     * @var array
     */
    protected $_allowedValues = array();
    
    /**
     * 
     * @param array $options
     */
    public function __construct($options) {
        if(isset($options['allowedValues'])){
            if(is_array($options['allowedValues'])){
                $this->_allowedValues = $options['allowedValues'];
            }else{
                $this->_allowedValues = (array)$options['allowedValues'];
            }
        }
        parent::__construct($options);
    }
    
    /**
     * 
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $ret = parent::isValid($value);        
        return $ret || in_array($value, $this->_allowedValues);
    }
}
