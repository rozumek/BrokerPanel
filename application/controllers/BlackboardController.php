<?php

class BlackboardController extends Cms_Controller_Action
{

    public function init()
    {
        $this->_actionModel = new Application_Model_Blackboard();
        $this->_actionForm = new Application_Form_Blackboard($this->_getIdentity()->getRole());

        $this->_setModuleForRoutes('default');
        $this->_setControllereForRoutes('blackboard');
        $this->_setMessagesPrefix('BLACKBOARD');

        $this->_setTitle('BLACKBOARD');
    }

    public function addAction()
    {
        parent::addAction();
        $this->_setTitle('ADD_BLACKBOARD');
    }

    public function editAction()
    {
        parent::editAction();
        $this->_setTitle('EDIT_BLACKBOARD');
    }

}







