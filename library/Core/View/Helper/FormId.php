<?php

class Core_View_Helper_FormId extends Core_View_Helper_Abstract
{
    /**
     *
     * @var string 
     */
    protected $_format = '%05.d';
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @param string $attribs
     * @return string
     */
    public function formId($name, $value = null, $attribs = null)
    {
        $value = sprintf($this->_format, $value);
        return $this->_invokeHelper('text', $name, $value, $attribs);
    }
}
