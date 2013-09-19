<?php

class UsersController extends Cms_Controller_Action
{
    
    public function init()
    {
        $this->_actionModel = new Application_Model_Users();
        $this->_actionForm = new Application_Form_User($this->_getIdentity()->getRole());
        
        $this->_setModuleForRoutes('default');
        $this->_setControllereForRoutes('users');
        $this->_setMessagesPrefix('USER');
        
        $this->_setTitle('USERS');                
    }
          
    public function addAction() 
    {
        if(($email = $this->_getParam('email', null)) !== null){
            $this->_actionForm->getElement('email')
                    ->setValue($email);
        }
        
        parent::addAction();
        $this->_setTitle('ADD_USER');
    }
    
    public function editAction() 
    {        
        parent::editAction();
        $this->_setTitle('EDIT_USER');
    }
    
    public function viewAction() 
    {               
        parent::viewAction();
        $this->_setTitle('VIEW_USER');
    }
    
    public function loginidentityAction()
    {
        $userId = (int)$this->_getParam('id', 0);
        
        if($userId > 0){
            $user = $this->_actionModel->getUser($userId);
            
            if($user instanceof Application_Model_DbTable_Row_User){
                if($user->isActive()){                    
                    $oldIdentity = $this->_getIdentity();
                    $newIdentity = array_merge($user->toArray(), array('rolename' =>$user->getRole()->getName()));

                    //set new identity
                    $this->_auth->setIdentity($newIdentity);
                    $this->identity = $this->_auth->getIdenity();

                    //backup old identity
                    $this->_getIdentity()->setBackUpIdentity($oldIdentity);

                    $this->_addMessagetoQueue(sprintf('LOGGED_AS', $this->_getIdentity()->getUserName()));
                    $this->_gotoIndex();
                }
            }
        }
        
        $this->_gotoIndex();
    }
}







