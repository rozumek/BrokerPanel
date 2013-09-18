<?php

class Core_View_Helper_FormPaginationLimit extends Core_View_Helper_Abstract
{
    /**
     *
     * @var string 
     */
    protected $_options = array(
        '' => ' - ',
        5 => 5,
        10 => 10,
        20 => 20,
        50 => 50,
        100 => 100,
    );
    
    /**
     *
     * @var array
     */
    protected $_attribs = array(
        'onchange' => 'if(this.value !== \'\'){$(this).parent(\'form\').submit();}'
    );
    
    /**
     *      
     * @param string $value
     * @param array $attribs
     * @return string
     */
    public function formPaginationLimit($value = null, $firstElement=null, $attribs = array())
    {
        if($firstElement !== null){
            $this->_options[''] = $firstElement;
        }
        
        $attribs = array_merge($this->_attribs, (array)$attribs);
        
        return $this->_invokeHelper('select', 'limit', $value, $this->_attribs, $this->_options);
    }
}
