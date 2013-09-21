<?php

class Core_Validate_Password extends Zend_Validate_Abstract {

    const NO_DIGITS = 'noDigits';
    const NO_UPPERCASE = 'noUpperCase';
    const NO_LOWERCASE = 'noLoweCase';
    const TOO_SHORT = 'tooShort';
    const NO_SPECIAL = 'noSpecial';

    /**
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NO_DIGITS => "password must contain digits",
        self::NO_UPPERCASE => "password must contain uppercase letters",
        self::NO_LOWERCASE => "password must contain lowercase letters",
        self::TOO_SHORT => "password is too short, mininum: %min% letters",
        self::NO_SPECIAL => "password must contain special charates (!, @ #, etc)"
    );
    
    /**
     *
     * @var array
     */
    protected $_messageVariables = array(
        'min' => '_minlength'
    );
    
    /**
     *
     * @var string
     */
    protected $_minlength = 8;

    /**
     *
     * @var bool
     */
    protected $_hasLowerCase = true;
    
    /**
     *
     * @var bool
     */
    protected $_hasUpperCase = true;
    
    /**
     *
     * @var bool
     */
    protected $_hasDigits = true;
    
    /**
     *
     * @var bool
     */
    protected $_hasSpecial = true;
    
    /**
     * 
     * @param type $options
     */
    public function __construct($options=array()) {
        $this->setOptions($options);
    }
    
    /**
     * 
     * @param array $array
     * @return \Core_Validate_Password
     */
    public function setOptions(array $array) {
        if(isset($array['minlength'])) {
            $this->_minlength = $array['minlength'];
        }
        if(isset($array['hasLowerCase'])) {
            $this->_hasLowerCase = $array['hasLowerCase'];
        }
        if(isset($array['hasUpperCase'])) {
            $this->_hasUpperCase = $array['hasUpperCase'];
        }
        if(isset($array['hasDigits'])) {
            $this->_hasDigits = $array['hasDigits'];
        }
        if(isset($array['hasSpecial'])) {
            $this->_hasSpecial = $array['hasSpecial'];
        }
        
        return $this;
    }
    
    /**
     * 
     * @param string $value
     * @return boolean
     */
    public function isValid($value) {  
        $ret = true;
        $this->_setValue($value);

        if((int)$this->_minlength > 0) {
            if(strlen($value) < $this->_minlength) {
                $this->_error(self::TOO_SHORT);   
                $ret = false;
            }
        }
        
        if($this->_hasDigits) {
            if(!preg_match('/[0-9]/',$value)){
                $this->_error(self::NO_DIGITS);   
                $ret = false;
            }
        }
        
        if($this->_hasLowerCase) {
            if(!preg_match('/[a-z]/',$value)){
                $this->_error(self::NO_LOWERCASE);
                $ret = false;
            }
        }
        
        if($this->_hasUpperCase) {
            if(!preg_match('/[A-Z]/',$value)){
                $this->_error(self::NO_UPPERCASE); 
                $ret = false;
            }
        }     
        
        if($this->_hasSpecial) {
            if(!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/',$value)){
                $this->_error(self::NO_SPECIAL); 
                $ret = false;
            }
        }                
        
        return $ret;
    }

}

