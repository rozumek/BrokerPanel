<?php

class Core_Validate_PhoneNumber extends Zend_Validate_Abstract{
       
    const UNALLOWED_CHARS = 'unallowedChars';
    const LENGTH = 'numLength';

    /**
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::UNALLOWED_CHARS => "Phone %value% number contains nonnumerical characters",
        self::LENGTH => "Phone %value% number should be %length% character long",
    );
    
    /**
     *
     * @var array
     */
    protected $_messageVariables = array(
        'length' => '_phoneNumberLength'
    );
    
    /**
     *
     * @var int
     */
    protected $_phoneNumberLength = 9;

    /**
     * 
     * @param int $phoneNumberLength
     */
    public function __construct($phoneNumberLength=null) {
        if(!empty($phoneNumberLength)) {
            $this->_phoneNumberLength = (int) $phoneNumberLength;
        }
    }
    
    /**
     * 
     * @param string $value
     * @return boolean
     */
    public function isValid($value) {
        $ret = true;
        $value = trim($value);
        
        if(strval(intval($value)) !== $value) {
            $ret = false;
            $this->_error(self::UNALLOWED_CHARS, $value);
        }
        
        if((int)$this->_phoneNumberLength > 0) {
            if(strlen(strval($value)) != $this->_phoneNumberLength) {
                $ret = false;
                $this->_error(self::LENGTH, $value);
            }
        }
        
        return $ret;
    }
    
}
