<?php

class AccountRequestsController extends Cms_Controller_Action
{
    
    public function init()
    {
        $this->_actionModel = new Application_Model_AccountRequests();
        $this->_actionForm = new Application_Form_AccountRequest();
        
        $this->_setModuleForRoutes('default');
        $this->_setControllereForRoutes('account-requests');
        $this->_setMessagesPrefix('ACCOUNT_REQUESTS');
        
        $this->_setTitle('ACCOUNT_REQUESTS');                
    }              
    
}







