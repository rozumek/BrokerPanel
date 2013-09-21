<?php

class Core_Validate_Url extends Zend_Validate_Abstract {

    const INVALID_URL = 'invalidUrl';

    /**
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_URL => "'%value%' is not a valid URL.",
    );
    
    /**
     *
     * @var string
     */
    protected $_urlPrefix = 'http://www.';

    /**
     * 
     * @param string $value
     * @return boolean
     */
    public function isValid($value) {        
        $this->_setValue((string) $value);

        if (!Zend_Uri::check($this->_value) && !Zend_Uri::check($this->_urlPrefix.$this->_value)) {
            $this->_error(self::INVALID_URL);
            return false;
        }
        return true;
    }

}

