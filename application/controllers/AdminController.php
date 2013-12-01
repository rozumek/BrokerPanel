<?php

class AdminController extends Cms_Controller_Action {


    public function init() {
        $this->_usersModel = new Application_Model_Users();
        $this->_stockOrderModel = new Application_Model_StockOrders();

        $this->_setModuleForRoutes('default');
        $this->_setControllereForRoutes('index');
        $this->_setMessagesPrefix('Index');
    }

    public function indexAction() {
        $this->_setTitle('ADMINISTRATION');

        $this->view->modules = array(
            'blackboard' => array(
                'route' => 'default-blackboard-index',
                'label' => 'BLACKBOARD',
            )
        );
    }

}