<?php

class Core_Validate_Nip extends Zend_Validate_Abstract 
{
    /**
     * Validation failure message key for when the value is not of valid length
     *
     * @var string
     */

    const LENGTH = 'numLength';

    /**
     * Validation failure message key for when the value fails the mod checksum
     *
     * @var string
     */
    const CHECKSUM = 'numChecksum';

    /**
     * 
     */
    const INVALID_CHAR = 'invalidChar';
    
    /**
     * Digits filter for input
     *
     * @var Zend_Filter_Digits
     */
    protected static $_filter = null;

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_CHAR => "Niedozwolone znaki w NIP",
        self::LENGTH => "Prosimy o podanie prawidłowego numeru NIP",
        self::CHECKSUM => "Prosimy o podanie prawidłowego numeru NIP"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value contains a valid Eividencial namber message
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($value) 
    {

        $this->_setValue($value);

        if (null === self::$_filter) {
            self::$_filter = new Zend_Filter_Digits();
        }

        if (!preg_match('/^[0-9\ \-]+$/', $value)) {
            $this->_error(self::INVALID_CHAR, $value);
            return false;
        }

        $valueFiltered = self::$_filter->filter($value);

        $length = strlen($valueFiltered);

        if ($length != 10) {
            $this->_error(self::LENGTH);
            return false;
        }

        $mod = 11;
        $sum = 0;
        $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $digits = array();

        preg_match_all("/\d/", $valueFiltered, $digits);

        $valueFilteredArray = $digits[0];

        foreach ($valueFilteredArray as $digit) {
            $weight = current($weights);
            $sum += $digit * $weight;
            next($weights);
        }

        if ($sum % $mod != $valueFilteredArray[$length - 1]) {
            $this->_error(self::CHECKSUM, $valueFiltered);
            return false;
        }

        return true;
    }

}