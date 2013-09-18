<?php

class Core_Controller_Plugin_Banner extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var Zend_View 
     */
    private $_view = null;        
    
    /**
     *
     * @var Application_Model_Banners
     */
    private $_bannersModel = null;
    
    /**
     *
     * @var Application_Model_Routes
     */
    private $_routesModel = null;
    
    /**
     *
     * @var string
     */
    private $_bannerLayout = null;
    
    /**
     *
     * @var string
     */
    private $_bannerScriptPath = null;

    /**
     * 
     * @param Zend_View $view
     */
    public function __construct(Zend_View $view, $bannerScriptPath, $bannerLayout) 
    {
        $this->_view = $view;
        $this->_bannersModel = new Application_Model_Banners();
        $this->_routesModel = new Application_Model_Routes;
        $this->_bannerScriptPath = $bannerScriptPath;
        $this->_bannerLayout = $bannerLayout;
    }
    
    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {                  
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        $route = $this->_routesModel->getRouteByRouteName(Core_Router_Helper::assembleRoute($module, $controller, $action));
        
        if($route instanceof Application_Model_DbTable_Row_Route){
            $banner = $this->_bannersModel->getRouteBanner($route->getId());
            
            if($banner instanceof Application_Model_DbTable_Row_Banner){
                $bannerImage = $banner->getImage();
                
                if(!empty($bannerImage)){
                    $bannerOutput = $this->_getView()
                        ->setScriptPath($this->_bannerScriptPath)
                        ->assign('image', $banner->getImage())
                        ->assign('title', $banner->getTitle())
                        ->assign('height', (int)$banner->getHeight())
                        ->assign('width', (int)$banner->getWidth())
                        ->render($this->_bannerLayout);
                    
                    if(!empty($bannerOutput)){
                        $this->_getView()
                            ->getHelper('Placeholder')
                            ->placeholder('banner')
                            ->set($bannerOutput);
                    }

                }
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