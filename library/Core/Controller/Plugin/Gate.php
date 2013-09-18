<?php

class Core_Controller_Plugin_Gate extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var Zend_View 
     */
    private $_view = null;        
    
    /**
     *
     * @var Zend_Session_Namespace 
     */
    private $_session = null;
    
    /**
     *
     * @var string
     */
    private $_gateLayout = null;
    
    /**
     *
     * @var string
     */
    private $_gateScriptPath = null;
    
    /**
     * 
     * @param Zend_View $view
     */
    public function __construct(Zend_View $view, $gateLayout, $gateScript='gate.phtml') 
    {
        $this->_view = $view;
        $this->_session = new Zend_Session_Namespace();
        $this->_gateLayout = $gateLayout;
        $this->_gateScriptPath = $gateScript;
    }
    
    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if(!isset($this->_session->gateAccepted) && $this->_session->gateAccepted !== true){            
            $this->_getView()
                    ->setScriptPath($this->_gateLayout)
                    ->assign('showGate', true)  
                    ->headScript()->appendFile('/js/gate.js');
             
            $output = $this->_getView()->render($this->_gateScriptPath);
            
            if(!empty($output)){
                //render output
                $this->_getView()
                        ->getHelper('Placeholder')
                        ->placeholder('gate')
                        ->set($output);
                
            }
        }
    }
    
    /**
     * 
     * @return Zend_View
     */
    protected function _getView(){
        return $this->_view;
    }
    
}