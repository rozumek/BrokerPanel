<?php

class IndexController extends Cms_Controller_Action {

    /**
     *
     * @var Application_Model_Users
     */
    protected $_usersModel = null;

    /**
     *
     * @var Application_Model_StockOrders
     */
    protected $_stockOrderModel = null;

    /**
     *
     * @var Application_Model_Blackboard
     */
    protected $_blackboardModel = null;

    public function init() {
        $this->_usersModel = new Application_Model_Users();
        $this->_stockOrderModel = new Application_Model_StockOrders();
        $this->_blackboardModel = new Application_Model_Blackboard();

        $this->_setModuleForRoutes('default');
        $this->_setControllereForRoutes('index');
        $this->_setMessagesPrefix('Index');
    }

    public function indexAction() {
        $this->view->userName = $this->_getIdentity()->getName();

        $this->view->brokerOfAMonth = $this->_stockOrderModel->getBrokerOfAMonth();
        $this->view->brokerOfAWeek = $this->_stockOrderModel->getBrokerOfAWeek();
        $this->view->brokerOfADay = $this->_stockOrderModel->getBrokerOfADay();
        $this->view->brokerOfAYear = $this->_stockOrderModel->getBrokerOfAYear();

        if($this->_blackboardModel->getActiveBlackboard()->count() > 0) {
            $this->view->blackboard = $this->_blackboardModel->getActiveBlackboard()->current()->toArray();
        }
    }

    public function logoutAction() {
        //logout identity and restore backup identity
        if ($this->_getIdentity()->isIdentityBackedUp()) {
            $logoutIdentity = $this->_getIdentity();

            $backupIdentity = $this->_getIdentity()
                    ->getBackUpIdentity();

            $restoredIdentity = $this->_getIdentity()
                    ->getBackUpIdentity()
                    ->toArray();

            //restore identity
            $this->_auth->setIdentity($restoredIdentity);
            $this->identity = $this->_auth->getIdentity();

            $this->_addMessagetoQueue(sprintf(_T('LOGIN_IDENTITY_MESSAGE'), $logoutIdentity->getUserName(), $backupIdentity->getUserName()));

            $this->_gotoRoute(array(
                'module' => 'default',
                'controller' => 'users',
                'action' => 'index',
                'id' => $logoutIdentity->getId()
            ));
        } else {
            $this->_auth->logout();
            $this->_clearIdentity();

            $this->_redirect('/');
        }
    }

    public function loginAction() {
        if ($this->_auth->isLoggedIn()) {
            $this->_gotoIndex();
        }

        Zend_Layout::getMvcInstance()->setLayout('login');
        $form = new Application_Form_Login();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                try {
                    if ($this->_auth->authenticate($formData['username'], $formData['password'])) {
                        $user = $this->_usersModel->getUser($this->_auth->getIdentity()->getId());
                        $newIdentity = array_merge($user->toArray(), array('rolename' => $user->getRole()->getName()));

                        $this->_setIdentity($newIdentity);
                        $this->_gotoIndex();
                    } else {
                        $this->_addMessagetoQueue(_T('LOGIN_NOT_AUTENTICATED'), Core_Log::WARN);
                    }
                } catch (Zend_Exception $e) {
                    $this->_addMessagetoQueue(_T('ERROR_OCCURED'), Core_Log::NOTICE);
                    $this->_log('LoginError', Core_Log::ERR, array(
                        'message' => $e->getMessage(),
                        'exception' => $e,
                        'formData' => $formData
                    ));
                }
            } else {
                $this->_addMessagetoQueue(_T('FORM_NOT_VALID'), Core_Log::NOTICE);
            }
        }

        $this->view->form = $form;
    }

    public function accountrequestAction() {
        Zend_Layout::getMvcInstance()->setLayout('login');
        $form = new Application_Form_AccountRequest();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $accountRequestsModel = new Application_Model_AccountRequests();

                if ($accountRequestsModel->create($form->getValues())) {
                    $this->_addMessagetoQueue(_T('ACCOUNT_REQUEST_POSTED'), Core_Log::SUCCESS);
                    $this->_gotoRoute(array(
                        'controller' => 'index',
                        'action' => 'login'
                    ));
                } else {
                    $this->_addMessagetoQueue(_T('ERROR_OCCURED'), Core_Log::ERR);
                    $this->_log('AccountRequestPostError', Core_Log::ERR, array(
                        'message' => $accountRequestsModel->getLastMessage(),
                        'exception' => $accountRequestsModel->getLastException(),
                        'formData' => $formData
                    ));
                }
            } else {
                $this->_addMessagetoQueue(_T('FORM_NOT_VALID'), Core_Log::NOTICE);
            }
        }

        $this->view->form = $form;
    }

    public function changelogAction() {
        $this->_setTitle('CHANGELOG');

        $changeLogFileName = APPLICATION_PATH.'/../changelog.txt';
        $changeLog = '';

        if (file_exists($changeLogFileName)){
            $changeLog = file_get_contents($changeLogFileName);
        }

        $this->view->changeLog = $changeLog;
    }

}