<?php

class Aktiv_Form_Element_LimitValueType extends Zend_Form_Element_Radio
{
    /**
     *
     * @var string
     */
    public $_separator = ' ';
    
    /**
     * 
     */
    public function init() {     
        $options = Aktiv_Dictionary_LimitValueTypes::getInstance()->getList();
        $this->addMultiOptions($options);
    }
}
