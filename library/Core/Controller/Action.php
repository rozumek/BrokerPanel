<?php

/**
 * 
 * @property Core_User_Identity $identity
 * @property array $state
 */
class Core_Controller_Action extends Zend_Controller_Action{    
    
    /**
     *
     * @var Zend_Session_Namespace
     */
    protected $_session = null;
    
    /**
     *
     * @var Application_Model_Auth 
     */
    protected $_auth = null;
    
    /**
     *
     * @var Core_Application_MessagesQueue 
     */
    protected $_messagesQueue = null;

    /**
     *
     * @var Zend_Mail 
     */
    protected $_mailer = null;
       
    
    /**
     *
     * @var type 
     */
    protected $_sessionSaveMap = array(
        'identity',
        'state',
    );
    
    /**
     * 
     */
    public function __construct(\Zend_Controller_Request_Abstract $request, \Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) 
    {
        $this->_session = new Zend_Session_Namespace();
        $this->_auth = new Application_Model_Auth(Zend_Auth::getInstance());
        
        parent::__construct($request, $response, $invokeArgs);
        
        $this->view->identity = $this->_getIdentity();
    } 
    
    /** 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) 
    {
        if(in_array($name, $this->_sessionSaveMap)){
            $this->_session->{$name} = $value;
        }else{
            $this->{$name} = $value;
        }
    }
    
    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name) 
    {
        if(in_array($name, $this->_sessionSaveMap)){
            return $this->_session->{$name};
        }else{
            return $this->{$name};
        }
    }
    
    /**
     * 
     * @param string $name
     */
    public function __unset($name) 
    {
        if(in_array($name, $this->_sessionSaveMap)){
            unset($this->_session->{$name});
        }else{
            unset($this->{$name});
        }
    }
    
    /**
     * 
     * @param string $name
     * @return bool
     */
    public function __isset($name) 
    {
        if(in_array($name, $this->_sessionSaveMap)){
            return isset($this->_session->{$name});
        }else{
            return isset($this->{$name});
        }
    }
    
    /**
     * 
     * @return string
     */
    protected function _getCurrentProtocol()
    {
        return Core_Uri_Helper::getCurrentProtocol();
    }
    
    /**
     * 
     * @return string
     */
    protected function _getBaseUrl()
    {
        return $this->_getCurrentProtocol().'://'.Core_Uri_Helper::getBaseUrl();
    }
    
    /**
     * 
     * @return Zend_Config
     */
    protected function _getAppConfig()
    {
        return $this->getInvokeArg('bootstrap')
                ->getResource('appConfig');
    }
    
    /**
     * 
     * @return Zend_Log|bool
     */
    protected function _getLogger()
    {
        $logger = $this->getInvokeArg('bootstrap')
                ->getResource('log');        

        return $logger;
    }
    
     /**
     * 
     * @param type $message
     * @param type $priority
     * @param type $data
     * @throws Zend_Exception
     */
    protected function _log($message, $priority, $data=null, $serialize=true)
    {        
        if(!is_array($data)){
            $data = array('data' => $data);
        }
        
        $data = array_merge($data, array(
            'server' => $_SERVER,
            'post' => $_POST,
            'get' => $_GET
        ));
        
        if($serialize === true){
            $data = serialize($data);
        }
        
        $this->_getLogger()->log($message, $priority, $data);
    }
    
    /**
     * 
     * @param type $data
     * @param type $message
     */
    protected function _debug($message, $data)
    {
        $this->_getLogger()->_log($message, Core_Log::DEBUG, $data, true);
    }
    
     /**
     * 
     * @param string $message
     * @param int $type
     * @return void
     */
    protected function _addMessagetoQueue($message, $type = Core_Log::INFO)
    {
        try{
            Core_Application_MessagesQueue::getInstance()->addMessageToQueue($message, $type);
        }catch(Zend_Exception $e){
            $this->_log($e->getMessage(), Core_Log::CRIT, $e);
        }
    }
    
    /**
     * 
     * @param int $type
     * @return array
     */
    protected function _getMessageQueue($type = null)
    {
        try{
            return Core_Application_MessagesQueue::getInstance()->getMessagesQueue($type);
        }catch(Zend_Exception $e){
            $this->_log($e->getMessage(), Core_Log::CRIT, $e);
        }
        return array();
    }
    
    /**
     * @return void
     */
    protected function _flushMessageQueue()
    {
        try{
            Core_Application_MessagesQueue::getInstance()->flushMessagesQueue();  
        }catch(Zend_Exception $e){
            $this->_log($e->getMessage(), Core_Log::CRIT, $e);
        }
    }
    
    /**
     * 
     * @param string $route
     * @param array $params
     */
    protected function _gotoRoute($params=array(), $route='default', $reset=true)
    {
        $this->_helper->redirector->gotoRoute($params, $route, $reset);
    }
    
    /**
     * 
     * @return void
     */
    protected function _goto404Page()
    {
        $this->_gotoRoute(
            array(
                'controller' => 'error',
                'action' => 'error'
            )
        );
    }
    
    /**
     * 
     * @return void
     */
    protected function _set404ErrorPage()
    {
        $this->getResponse()->clearBody();
        $this->getResponse()->clearHeaders();
        $this->getResponse()->setHttpResponseCode(404);
        
        $this->getRequest()
                ->setControllerName('error')
                ->setActionName('error');
    }
    
    /**
     * 
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function _getValueFromState($name, $default=null)
    {
        if(!empty($this->state[$name])){
            return $this->state[$name];
        }
        
        return $default;
    }
    
    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    protected function _storeValueInState($name, $value)
    {
        if(!is_array($this->state)){
            $this->state = array();
        }
        
        $state = $this->state;
        $state[$name] = $value;
        $this->state = $state;
    }
    
    /**
     * 
     * @param string $paramName
     * @param mixed $default
     * @param string $separator
     * @return mixed
     */
    protected function _getArrayParam($paramName, $default=null, $separator=',')
    {
        $param = $this->_getParam($paramName, null);
        
        if($param === null){
            return $default;
        }
        
        $idArray = explode($separator, $param);
        
        if(count($idArray) > 1){
            return $idArray;
        }
        
        return (array)$param;
    }
    
    /**
     * 
     * @return \Core_User_Identity
     */
    protected function _getIdentity()
    {
        if(!($this->identity instanceof Core_User_Identity)){            
            return new Core_User_Identity();
        }
        
        return $this->identity;
    }
    
    /**
     * 
     * @return bool
     */
    protected function _hasIdentity()
    {
        return (isset($this->identity) && $this->identity instanceof Core_User_Identity);
    }
    
    /**
     * @return void
     */
    protected function _clearIdentity(){
        unset($this->identity);        
    }
    
    /**
     * @return void
     */
    protected function _setIdentity($newIdentity){
        if(!($this->identity instanceof Core_User_Identity) && $this->_auth->isLoggedIn()){
            $this->_auth->setIdentity($newIdentity);   
            $this->identity = $this->_auth->getIdenity();
        }
    }
    
    /**
     * 
     * @return Zend_Mail
     */
    protected function _getMailer(){
        if(!$this->_mailer){
            $this->_mailer = new Zend_Mail();
            
        }
        
        return $this->_mailer;
    }
    
     /**
     * 
     * @param array|string $reciepients
     * @param string $subject
     * @param string $html
     * @param string $text
     * @param array $attachements
     * @return boolean
     */
    protected function _sendMail($reciepients, $subject, $reply, $html, $text='', $attachements=null){
        $mailer = $this->_getMailer();
        
        try{
            $mailer->setSubject($subject);            
            $mailer->setBodyHtml($html);
            $mailer->setBodyText($text);
            
            if(is_array($reciepients)){
                foreach($reciepients as $name => $mail){
                    if(is_string($name)){
                        $mailer->addTo($mail, $name);
                    }else{
                        $mailer->addTo($mail);
                    }
                }
            }else if(is_string($reciepients)){
                $mailer->addTo($reciepients);
            }
            
            if(is_array($reply)){
                foreach ($reply as $name => $email){
                    if(is_string($name)){
                        $mailer->setReplyTo($email, $name);
                    }else{
                        $mailer->setReplyTo($email);
                    }
                }
            }else if(is_string($reply)){
                $mailer->setReplyTo($reply);
            }

            if($attachements){
                //@todo implement attachements
            }

            $mailer->send();
            
            return true;
        }catch(Zend_Exception $e){
            $this->_log($e->getMessage(), Core_Log::WARN, array(
                'mailer' => $mailer,
                'exception' => $e
            ));            
        }
        
        return false;
    }
    
    protected function _getMailerDefaultFrom()
    {
        $reciepients = array();
        $defaultFrom = $this->_getMailer()
                ->getDefaultFrom();
        
        if(isset($defaultFrom['email']) && isset($defaultFrom['name'])){
            $reciepients = array(
                $defaultFrom['name'] => $defaultFrom['email']
            );
        }

        return $reciepients;
    }
    
        /**
     * 
     * @param string $title
     */
    protected function _setTitle($title){
        $title = _T($title);
        $this->view->headTitle($title.' / ');
        $this->view->placeholder('title')
                ->set($title);
    }
    
}
