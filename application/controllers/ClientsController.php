<?php

class ClientsController extends Cms_Controller_Action
{
    
    public function init()
    {
        $this->_actionModel = new Application_Model_Clients();
        $this->_actionForm = new Application_Form_Client($this->_getIdentity()->getRole());
        
        $this->_setModuleForRoutes('default');
        $this->_setControllereForRoutes('users');
        $this->_setMessagesPrefix('CLIENT');
        
        $this->_setTitle('CLIENTS');                
    }
          
    public function addAction() 
    {
        parent::addAction();
        $this->_setTitle('ADD_CLIENT');
    }
    
    public function editAction() 
    {        
        parent::editAction();
        $this->_setTitle('EDIT_CLIENT');
    }
    
    public function viewAction() 
    {               
        parent::viewAction();
        $this->_setTitle('VIEW_CLIENT');
    }
    
}







