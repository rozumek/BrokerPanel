<?php

class Application_Form_AccountRequest extends Core_User_Form
{
    
    public function init()
    {
        $this->initFormSettings();
                
        $this->addEmail(true, true)            
            ->addSubmit();            
    }

}

