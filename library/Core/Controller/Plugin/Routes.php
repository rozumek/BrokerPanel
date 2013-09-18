<?php


class Core_Controller_Plugin_Routes extends Zend_Controller_Plugin_Abstract{
    
    /**
     *
     * @var array
     */
    protected $_config = array();
    
    /**
     *
     * @var Zend_Cache_Core 
     */
    protected $_cache = null;
    
    /**
     *
     * @var Application_Model_Routes 
     */
    protected $_routesModel = null;
    
    /**
     * 
     * @param array $routerConfig
     * @param Zend_Cache_Core $routerCache
     */
    public function __construct($config=array(), $cache=null) 
    {
        $this->_routesModel = new Application_Model_Routes();
        $this->_config = $config;
        
        if($cache instanceof Zend_Cache_Core){
            $this->_cache = $cache;
        }
    }
    
    public function routeStartup(\Zend_Controller_Request_Abstract $request) 
    {        
        $router = Zend_Controller_Front::getInstance()
                ->getRouter(); 

        if($this->_isRouterEnabled()){
            $routes = null;
            
            if(!$this->_isCacheEnabled() || ($routes = $this->_cache->load($this->_getCacheId())) === false){
                $routes = $this->_getRoutes();
            }
            if($this->_isCacheEnabled()){
                $this->_cache->save($routes);
            }
            
            $router->addRoutes($routes);
            
            if($this->_disableDefaultRoutes()){
                $router->removeDefaultRoutes();
            }
        }                
    }
    
    /**
     * 
     * @return bool
     */
    protected function _isCacheEnabled()
    {
        return (int)Core_Array::get($this->_config['cache'], 'enable',0) === 1 && $this->_cache instanceof Zend_Cache_Core;
    }
    
    /**
     * 
     * @param string $sufix
     * @return string
     */
    protected function _getCacheId($sufix='')
    {
        if($sufix !== ''){
            $sufix = '_'.$sufix;
        }
        
        return APPLICATION
                .'_'.Core_Array::get($this->_config['cache'], 'id', 'routes')
                .'_'.$sufix;
    }
    
    /**
     * 
     * @return bool
     */
    protected function _disableDefaultRoutes()
    {
        return (int)Core_Array::get($this->_config, 'disableDefaultRoutes', 0) === 1 && $this->_isRouterEnabled();
    }
    
    
    /**
     * 
     * @return bool
     */
    protected function _isRouterEnabled()
    {
        return (int)Core_Array::get($this->_config, 'enable', 0) === 1;
    }
    
    
    /**
     * 
     * @param string $lang
     * @param string $application
     * @return array
     */
    protected function _getRoutes()
    {               
        return $this->_routesModel->getRoutesForRouterRewrite();
    }
}
