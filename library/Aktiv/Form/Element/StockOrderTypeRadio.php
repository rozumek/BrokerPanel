<?php

class Aktiv_Form_Element_StockOrderTypeRadio extends Zend_Form_Element_Radio
{
    /**
     *
     * @var string
     */
    public $_separator = " ";
    
    /**
     * 
     */
    public function init() {     
        $options = Aktiv_Dictionary_StockOrderTypes::getInstance()->getList();
        $this->addMultiOptions($options);
    }
}
