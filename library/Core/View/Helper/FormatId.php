<?php

class Core_View_Helper_FormatId extends Core_View_Helper_Abstract
{
    /**
     *
     * @var string 
     */
    protected $_format = '%05d';
    
    /**
     * 
     * @param string $value
     * @return string
     */
    public function formatId($value)
    {
        return  sprintf($this->_format, $value);        
    }
}
