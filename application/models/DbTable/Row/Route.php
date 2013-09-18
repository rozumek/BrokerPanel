<?php

class Application_Model_DbTable_Row_Route extends Core_Model_Db_Table_Row_Abstract implements Core_Controller_Router_Route_Row_Interface{
    
    /**
     *
     * @var string
     */
    protected $_routeClassBase = 'Zend_Controller_Router_Route';

    /**
     *
     * @var array
     */
    protected $_allowedTypes = array(
        'regex',
        'hostname',
        'static'
    );

    /**
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 
     * @param string $id
     * @return Application_Model_DbTable_Row_Route
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getRouteName()
    {
        return $this->routename;
    }
    
    /**
     * 
     * @param string $routename
     * @return \Application_Model_DbTable_Row_ROute
     */
    public function setRouteName($routename)
    {
        $this->routename = $routename;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * 
     * @param string $route
     * @return \Application_Model_DbTable_Row_ROute
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * 
     * @param string $type
     * @return \Application_Model_DbTable_Row_ROute
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }
    
    /**
     * 
     * @param string $module
     * @return \Application_Model_DbTable_Row_Resources
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }
    
    /**
     * 
     * @param string $controller
     * @return \Application_Model_DbTable_Row_Resources\
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * 
     * @param string $action
     * @return \Application_Model_DbTable_Row_ROute
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getParams(){
        return (array)Zend_Json::decode($this->params);
    }
    
    /**
     * 
     * @param array $params
     * @return \Application_Model_DbTable_Row_ROute
     */
    public function setParams(array $params){
        $this->params = json_encode((array)$params);
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getRegexp(){
        return (array)json_decode($this->regexp);
    }
    
    /**
     * 
     * @param array $regexp
     * @return \Application_Model_DbTable_Row_ROute
     */
    public function setRegexp(array $regexp){
        $this->regexp = Zend_Json::encode((array)$regexp);
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getAssembledParams(){
        $params = array_merge(
            array(
                'module' => $this->getModule(),
                'controller' => $this->getController(),
                'action' => $this->getAction()
            ), 
            $this->getParams()
        );

        return $params;
    }
    
    /**
     * 
     * @return string
     */
    public function getReverse()
    {
        return $this->reverse;
    }
    
    /**
     * 
     * @param string $reverse
     * @return \Application_Model_DbTable_Row_Route
     */
    public function setReverse($reverse)
    {
        $this->reverse = $reverse;
        return $this;
    }
    
    /**
     * @param bool $name $langEnabled
     * @return Zend_Controller_Router_Route_Abstract
     */
    public function toRouterRoute(){
        $routeClass = $this->_routeClassBase;
        $type = $this->getType();        
        if(in_array($type, $this->_allowedTypes)){
            $routeClass .= '_'.ucfirst($type);
        }
        
        $regex = $this->getRegexp();
        
        switch($type){            
            case 'regex': 
                $route = new $routeClass(
                    $this->getRoute(),
                    $this->getAssembledParams(),
                    $regex,
                    $this->getReverse()
                );
                break;
            
            default: case 'hostname': case 'static':
                $route = new $routeClass(
                    $this->getRoute(),
                    $this->getAssembledParams(),
                    $regex
                );
                break;
        }
        
        return $route;
        
    }
    
}
