<?php

class Core_Form_Element_Active extends Core_Form_Element_Select{
        
    public function init() {
        $this->addValidator(new Zend_Validate_InArray(array(0,1)))
                ->addMultiOption('', 'SELECT')
                ->addMultiOption('0', 'No')
                ->addMultiOption('1', 'Yes');                
    }

}
