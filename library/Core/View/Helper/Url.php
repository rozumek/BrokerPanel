<?php

class Core_View_Helper_Url extends Zend_View_Helper_Url
{
    /**
     * 
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will use the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true) 
    {
        if($name === null){
            $request = Zend_Controller_Front::getInstance()->getRequest();
            
            $module = Core_Array::get($urlOptions, 'module', $request->getParam('module'));
            $controller = Core_Array::get($urlOptions, 'controller', $request->getParam('controller'));
            $action = Core_Array::get($urlOptions, 'action', $request->getParam('action'));
            
            $name = Core_Router_Helper::assembleRoute($module, $controller, $action);
        }
        
        return parent::url($urlOptions, $name, $reset, $encode);
    }
}
