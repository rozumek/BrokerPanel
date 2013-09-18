<?php

class Application_Model_Routes extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface{
    
    /**
     * 
     */
    public function __construct() {
        $this->_dbTable = new Application_Model_DbTable_Routes();
    }
    
    /**
     * 
     * @param int $id
     * @return Application_Model_DbTable_Row_Route
     */
    public function getRoute($id){
        return $this->get($id);
    }
    
    /**
     * 
     * @param string $routename
     * @return Application_Model_DbTable_Row_Route
     */
    public function getRouteByRouteName($routename){
        return $this->getFirstWhere('routename=?', $routename);
    }
    
    /**
     * 
     * @param string $module
     * @param string $controller
     * @param string $action
     * @param array $params Not yet implemented
     * @return \Application_Model_Db_Table_Rowset
     */
    public function getRoutesBy($module, $controller, $action){
        $rowset = $this->getWhere(array(
            'module=?', 'controller=?', 'action=?'
        ), array(
           $module, $controller, $action 
        ), 'ordering ASC');
        
        return $rowset;
    }


    /**
     * 
     * @param type $id
     * @return boolean
     */
    public function routeExists($id){
        if((int) $id > 0){
            return $this->exists($id);
        }
        
        return false;
    }
    
    /**
     * 
     * @param int $id
     * @return bool
     */
    public function deleteRoute($id){
        return $this->delete($id);
    }
    
    /**
     * 
     * @param type $lang
     * @param type $application
     * @return \Application_Model_Db_Table_Rowset
     */
    public function getRoutes(){
        return $this->getRowset();
    }
    
    /**
     * 
     * @param string $lang
     * @param string $application
     * @return array
     */
    public function getRoutesForRouterRewrite(){
        $routes = $this->getRoutes();
        $routesForRouterRewrite = array();
        
        foreach ($routes as $route){
            if($route instanceof Core_Controller_Router_Route_Row_Interface){
                $routesForRouterRewrite[$route->getRouteName()] = $route->toRouterRoute();
            }
        }
        
        return $routesForRouterRewrite;
    }

    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function createRoute(array $data){
        return $this->create($data);
    }
    
    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function create($data){
        $route = $this->getEmptyRow();
        
        if($route instanceof Application_Model_DbTable_Row_Route){   
            $data = (array)$data;
            
            $route->setRouteName($data['routename']);
            $route->setRoute($data['route']);
            $route->setType($data['type']);
            $route->setModule($data['module']);
            $route->setController($data['controller']);
            $route->setAction($data['action']);
            $route->setOrdering($data['ordering']);
            $route->setParams(Core_Array::get($data, 'params', array()));
            $route->setRegexp(Core_Array::get($data, 'regexp', array()));
            
            return $this->save($route);            
        }
        
        return false;
    }
    
    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateResource(array $data, $id){
        return $this->update($data, $id);
    }
    
    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id){
        $route = $this->getRoute($id);
        
        if($route instanceof Application_Model_DbTable_Row_Route){
            $data = (array)$data;
            
            $route->setRouteName($data['routename']);
            $route->setRoute($data['route']);
            $route->setType($data['type']);
            $route->setModule($data['module']);
            $route->setController($data['controller']);
            $route->setAction($data['action']);
            $route->setOrdering($data['ordering']);
            $route->setParams(Core_Array::get($data, 'params', array()));
            $route->setRegexp(Core_Array::get($data, 'regexp', array()));
            
            return $this->save($route);
        }
        
        return false;
    }

    public function changeState($id) {
        //does nothing
    }
    
}
