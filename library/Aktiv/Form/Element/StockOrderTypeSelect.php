<?php

class Aktiv_Form_Element_StockOrderTypeSelect extends Zend_Form_Element_Select
{
    
    /**
     * 
     */
    public function init() {     
        $options = Aktiv_Dictionary_StockOrderTypes::getInstance()->getList();
        
        $this->addMultiOption('', 'SELECT');
        $this->addMultiOptions($options);
    }
}
