<?php

class Core_Form_Element_Acl_Type extends Core_Form_Element_Select{
    
   public function init() {
        $this->addValidator(new Zend_Validate_InArray(array('allow','deny')))                
                ->addMultiOption('allow', 'Allow')
                ->addMultiOption('deny', 'Deny');                
    }
    
}
