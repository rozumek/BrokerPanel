<?php

class Core_Controller_Plugin_Layout extends Zend_Layout_Controller_Plugin_Layout
{

    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if($request->getModuleName() == 'default'){
            $layoutPath = APPLICATION_PATH . '/layouts/scripts';
        }else{
            $layoutPath = APPLICATION_PATH . '/modules/' . $request->getModuleName() .'/layouts/scripts';
        }
        
        $this->getLayout()
             ->setLayoutPath($layoutPath);
    }
    
}