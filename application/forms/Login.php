<?php

class Application_Form_Login extends Core_User_Form
{

   public function init()
    {
        $this->initFormSettings();                               
        
        $this->addUsername(true)
            ->addPassword()
            ->addSubmit();      
    }
}

