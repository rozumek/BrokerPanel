<?php


abstract class Core_Log_Plugin_AccessLog_Abstract extends Zend_Controller_Plugin_Abstract {
 
    /**
     *
     * @var Core_Auth_IdentitySupportInterface 
     */
    protected $_auth = null;
    
    /**
     * 
     * @param Core_Auth_IdentitySupportInterface $auth
     */
    public function __construct(Core_Auth_IdentitySupportInterface $auth) {
        $this->_auth = $auth;
    }
    
    /**
     * 
     * @return void
     */
    public function dispatchLoopShutdown() {
        $request = $this->getRequest();
        $response = $this->getResponse();        
        
        $data = array(
            'ip' => $request->getClientIp(),
            'user' => $this->_getUserOrGuest(),
            'method' => $request->getMethod(),
            'request' => $request->getRequestUri(),
            'response' => $response->getHttpResponseCode(),
            'data' => $this->_getData(),
        );
        
        $this->_getLogger()->log('ACCESS', Core_Log::ACCESS, $data);
    }
    
    /**
     * 
     * @return Zend_Log
     */
    protected function _getLogger() {
        return Zend_Registry::get('log');
    }
    
    /**
     * 
     * @return string|int
     */
    abstract protected function _getUserOrGuest();
    
    /**
     * 
     * @return array
     */
    abstract protected function _getData();


    
    
}
