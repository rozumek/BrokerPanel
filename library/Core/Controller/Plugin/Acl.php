<?php

class Core_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var Zend_Acl 
     */
    private $_acl = null;
    
    /**
     *
     * @var Zend_Auth 
     */
    private $_auth = null;    
    
    public function __construct() 
    {
        $this->_acl = Core_Acl::getInstance();
        $this->_auth = Zend_Auth::getInstance();  
    }
    
    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $role = $this->_acl->getDefaultRoleId();
        if($this->_auth->hasIdentity()) {            
            $role = $this->_auth->getIdentity()->rolename;
        }
        
        // set default ACL and role statically
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->_acl);
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($role);        
        
        // prepare resource
        $resourceId = Core_Acl_Helper::assembleToResourceId(
            $request->getModuleName(), 
            $request->getControllerName()
        );
        
        if($this->_acl->has($resourceId)){
            if (!$this->_acl->isAllowed($role, $resourceId, $request->getActionName())) {
                if ($role == $this->_acl->getDefaultRoleId()) {
                    $request->setModuleName('default')
                           ->setControllerName('index')
                           ->setActionName('login');                    
                } else {//info for no access
                    $request->setModuleName('default')
                            ->setControllerName('error')
                            ->setActionName('denied');
                }
            }
        }else{
            $request->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('error');
        }
    }
    
}