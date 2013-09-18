<?php

abstract class Cms_Controller_Action extends Core_Controller_Action{
    
    /**
     * States for CMS Entities interactions
     */
    const NO_ACTION_STATUS = 0;
    const ENTITY_MODEL_ERROR = 1;
    const ENTITY_NOT_EXIST = 2;
    const ENTITY_SAVE_SUCCESS = 3;
    const ENTITY_CREATION_SUCCESS = 4;
    const ENTITY_SAVE_FAILED = 5;
    const ENTITY_CREATION_FAILED = 6;
    const ENTITY_DELETE_SUCCESS = 7;
    const ENTITY_DELETE_FAILED = 8;
    
    /**
     * Constants for table
     */
    const DEFAULT_LIMIT = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_SORT = null;
    
    /**
     *
     * @var Core_Model_Abstract
     */
    protected $_actionModel = null;
    
    /**
     *
     * @var Core_Form
     */
    protected $_actionForm = null;
    
    /**
     *
     * @var string 
     */
    protected $_moduleForRoutes = null;
    
    /**
     *
     * @var string 
     */
    protected $_controllerForRoutes = null;
    
    /**
     *
     * @var type 
     */
    protected $_messagesPrefix = 'Entity';

    /**
     *
     * @var array
     */
    protected $_globalParamsForRoute = array();
    
    /**
     *
     * @var array
     */
    protected $_defaultFilters = array();
    
    /**
     * Must be implemented in derived classes
     * 
     * @throws InitMethodNotImplementedException
     */
    public function init(){
        throw new Cms_Controller_Action_InitMethodNotImplementedException;
    }

    
    /**
     * =======================CMS Controller Common Action======================
     */
    
    /**
     * Standart CMS indexAction
     */
    public function indexAction(){  
        $page = (int)$this->_getParam('page', $this->_getValueFromState($this->_getStateNamespace().'.page', self::DEFAULT_PAGE));
        $limit = (int)$this->_getParam('limit', $this->_getValueFromState($this->_getStateNamespace().'.limit', self::DEFAULT_LIMIT)); 
        $sort = $this->_getParam('sort', $this->_getValueFromState($this->_getStateNamespace().'.sort', self::DEFAULT_SORT));
        $filters = array_merge($this->_defaultFilters, $this->_getFilterFormValues());
        
        $this->view->paginator = $this->_processPaginatorList($filters, $page, $limit, $sort);
        $form = $this->_getFilterableForm();
        $form->setValues($filters);
        $this->view->form = $form;
        
        $this->_storeValueInState($this->_getStateNamespace().'.limit', (int)$limit);
        $this->_storeValueInState($this->_getStateNamespace().'.page', (int)$page);
        $this->_storeValueInState($this->_getStateNamespace().'.sort', (int)$sort);
        
        $this->_initIndexActionDashboard();
    }
    
    /**
     * Standard CMS add Action
     */
    public function addAction(){
        //setting edit template for add action
        $this->_helper->viewRenderer('edit');
        
        $this->editAction();
        
        $this->_getDashboard()
                ->clearButtons();
        
        $this->_initAddActionDashboard();
    }
    
    /**
     * Standard CMS delete Action
     */
    public function deleteAction(){
        Zend_Layout::getMvcInstance()->disableLayout();  
        $ids = $this->_getArrayParam('id', array()); 
        
        if(!empty($ids)){
            foreach($ids as $id){
                switch($this->_processDeleteItem($id)){
                    case self::ENTITY_DELETE_SUCCESS:
                        $this->_addMessagetoQueue(sprintf(_T('DELETED'), _T($this->_getMessagesPrefix())), Core_Log::SUCCESS);
                        break;

                    case self::ENTITY_DELETE_FAILED:
                        $this->_addMessagetoQueue(sprintf(_T('CANT_BE_DELETED'), _T($this->_getMessagesPrefix())), Core_Log::SUCCESS);
                        break;

                    case self::ENTITY_NOT_EXIST:
                        $this->_addMessagetoQueue(sprintf(_T('DOES_NOT_EXIST'), _T($this->_getMessagesPrefix())), Core_Log::ERR);
                        break;
                 }
            }        
        }else{
            $this->_addMessagetoQueue(sprintf(_T('NO_ACTION'), _T($this->_getMessagesPrefix())), Core_Log::WARN);
        }
        
        $this->_gotoIndex();
    }
    
    /**
     * Standart CMS edit action
     */
    public function editAction(){                
        $status = $this->_processRequestSaveItem();
        switch($status){
            case self::ENTITY_NOT_EXIST:
                $this->_addMessagetoQueue(sprintf(_T('DOES_NOT_EXIST'), _T($this->_getMessagesPrefix())), Core_Log::WARN);
                break;
            
            case self::ENTITY_SAVE_SUCCESS: case self::ENTITY_CREATION_SUCCESS:
                $this->_addMessagetoQueue(sprintf(_T('SAVED'), _T($this->_getMessagesPrefix())), Core_Log::SUCCESS);
                break;
            
            case self::ENTITY_SAVE_FAILED: case self::ENTITY_CREATION_FAILED:
                $this->_addMessagetoQueue(sprintf(_T('SAVE_FAILED'), _T($this->_getMessagesPrefix())), Core_Log::ERR);
                break;
        }
        
        if($status !== self::NO_ACTION_STATUS){
            $this->_gotoIndex();
        }
                
        $this->_initEditActionDashboard();
    }
    
    /**
     * Standart CMS view action
     */
    public function viewAction(){
        $id = (int)$this->_getParam('id', 0);
        
        if(!$this->_actionModel->exists($id)){
            $this->_addMessagetoQueue(sprintf(_T('DOES_NOT_EXIST'), _T($this->_getMessagesPrefix())), Core_Log::WARN);
            $this->_gotoIndex();
        }
        
        $item = $this->_actionModel->get($id);
        
        if($item instanceof Core_Model_Db_Table_Row_Abstract){
            $data = array();
            $item = $item->toArray();
            $form = $this->_actionForm;            
            $form->setEditForm()
                    ->setReadOnly()
                    ->setValues($item);

            foreach($form->getElements() as $element){
                if($element instanceof Zend_Form_Element){
                    $data[$element->getName()] = array(
                        'value' => $element->getValue(),
                        'name' => $element->getName(),
                        'label' => $element->getLabel()
                    );
                }
            }        

            $this->view->itemData = $data;
            $this->view->form = $form;
        }
        
        $this->view->id = $id;
    }
    
    /**
     * Standart CMS change state action
     */
    public function changestateAction(){
        $ids = $this->_getArrayParam('id', array());         
        $changed = array();
        $notChanged = array();
        
        foreach($ids as $id){
            if(!$this->_processChangeState($id)){
                $notChanged[] = $id;
            }else{
                $changed[] = $id;
            }
        }
        
        if(!empty($changed)){
            $this->_addMessagetoQueue(sprintf(_T('STATUS_CHANGED'), implode(',', $notChanged)), Core_Log::SUCCESS);
        }
        
        if(!empty($notChanged)){
            $this->_addMessagetoQueue(sprintf(_T('ERROR_STATUS_CHANGED'), implode(',', $notChanged)), Core_Log::ERR);
        }
        
        $this->_gotoIndex();
    }
    
    public function orderupAction(){
        $this->_stdOrderChange('Up');
    }
    
    public function orderdownAction(){
        $this->_stdOrderChange('Down');
    }
    
    /**
     * =======================CMS Controller Helper methods=====================
     */
    
    /**
     * 
     * @return array
     */
    protected function _getGlobalParamsForRoute(){
        return  $this->_globalParamsForRoute;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     */
    protected function _addGlobalParamForRoute($key, $value){
        $this->_globalParamsForRoute[$key] = $value;
    }
    
    /**
     * 
     * @param array $params
     */
    protected function _setGlobalParamsForRoute($params){
        $this->_globalParamsForRoute = $params;
    }
    
    /**
     * 
     * @param string $key
     */
    protected function _unsetGlobalParamsForRoute($key){
        unset($this->_globalParamsForRoute[$key]);
    }
    
    /**
     * 
     * @param array $params
     */
    protected function _mergeGlobalParamsForRoute($params){
        $this->_globalParamsForRoute = array_merge($this->_globalParamsForRoute, $params);
    }


    /**
     * 
     * @param string $module
     */
    protected function _setModuleForRoutes($module){
        $this->_moduleForRoutes = $module;                
    }
    
    /**
     * 
     * @return string
     */
    protected function _getModuleForRoutes(){
        if($this->_moduleForRoutes === null){
            throw new Cms_Controller_Action_NoModuleForRoutesException;
        }
        return $this->_moduleForRoutes;
    }
    
    /**
     * 
     * @param string $module
     */
    protected function _setControllereForRoutes($controller){
        $this->_controllerForRoutes = $controller;                
    }
    
    /**
     * 
     * @return string
     */
    protected function _getControllerForRoutes(){
        if($this->_moduleForRoutes === null){
            throw new Cms_Controller_Action_NoControllerForRoutesException;
        }
        return $this->_controllerForRoutes;
    }
    
    /**
     * 
     * @param string $prefix
     */
    protected function _setMessagesPrefix($prefix){
        $this->_messagesPrefix = $prefix;
    }
    
    /**
     * 
     * @return string
     */
    protected function _getMessagesPrefix(){
        return $this->_messagesPrefix;
    }

    /**
     * 
     * @return Core_Form
     */
    protected function _getFilterableForm(){
        $filterForm = clone($this->_actionForm);
        $filterForm->setFilterableForm();
        return $filterForm;
    }

    /**
     *      
     * @return array
     */
    protected function _getFilterFormValues(){
        $filterForm = $this->_getFilterableForm();        
        $params = $this->_getAllParams();  
        $formData = array();
        
        if($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
        }
        
        $formData = array_merge($params, $formData);
        $filterForm->isValid($formData);
        $filters = $filterForm->getValidValues($formData);            
        
        return $filters;
    }
    
    /**
     * 
     * @param int $page
     * @param limit $limit
     * @param string $sort
     * @return Zend_Paginator
     */
    protected function _processPaginatorList($filters=array(), $page=1, $limit=10, $sort=null){                
        $paginator = $this->_actionModel->getPaginatorList($filters, $sort)
                ->setCurrentPageNumber($page)
                ->setItemCountPerPage($limit);
        
        return $paginator;          
    }    

    /**
     * 
     * @return int
     */
    protected function _processRequestSaveItem(){
        $id = (int)$this->_getParam('id', 0);  
        $form  = $this->_actionForm;
        $status = self::NO_ACTION_STATUS;        
        
        if($this->_actionModel instanceof Cms_Controller_Processor_Interface){
            if($id > 0){
                if(!$this->_actionModel->exists($id)){
                    $status = self::ENTITY_NOT_EXIST;
                }
                
                $item = $this->_actionModel->get($id);                  
                if($item instanceof Core_Model_Db_Table_Row_Abstract){
                    $item = $item->toArray();
                }
                
                $form->setEditForm()->setValues($item);
                if($this->getRequest()->isPost()){
                    $formData = $this->getRequest()->getPost();
                    if ($form->isValid($formData)){
                        $formData = array_merge($formData, $form->getValues());
                        
                        if($this->_actionModel->update($formData, $id)){
                            $this->_log('CMSEnitySaveSuccess', Core_Log::ACCESS, array(
                                'id' => $id,
                                'model' => get_class($this->_actionModel)
                            ));
                            
                            $status = self::ENTITY_SAVE_SUCCESS;
                        }else{
                            $messages = $this->_actionModel->getLastMessage();
                            $exception = $this->_actionModel->getLastException();
                            
                            $this->_addMessagetoQueue($messages, Core_Log::ERR);
                            $this->_log($messages, Core_Log::ERR, $exception);
                            $this->_log('CMSEnitySaveFailed', Core_Log::ACCESS, array(
                                'id' => $id,
                                'model' => get_class($this->_actionModel)
                            ));
                            
                            $status = self::ENTITY_SAVE_FAILED;
                        }
                    }
                }
                
                $this->view->itemData = $item;
                $this->view->id = $id;
            }else{            
                if($this->getRequest()->isPost()){
                    $formData = $this->getRequest()->getPost();
                    if ($form->isValid($formData)){        
                        $formData = array_merge($formData, $form->getValues());
                        
                        if($this->_actionModel->create($formData)){
                            $this->_log('CMSEnityCreationSuccess', Core_Log::ACCESS, array(
                                'form' => $form,
                                'model' => get_class($this->_actionModel)
                            ));
                            
                            $status = self::ENTITY_CREATION_SUCCESS;
                        }else{
                            $messages = $this->_actionModel->getLastMessage();
                            $exception = $this->_actionModel->getLastException();
                            
                            $this->_addMessagetoQueue($messages, Core_Log::ERR);
                            $this->_log($messages, Core_Log::ERR, $exception);
                            $this->_log('CMSEnitySaveFailed', Core_Log::ACCESS, array(
                                'id' => $id,
                                'model' => get_class($this->_actionModel)
                            ));
                            
                            $status = self::ENTITY_CREATION_FAILED;
                        }
                    }
                }
            }   
        }else{
            $this->_log(
                    get_class($this->_actionModel).' must be instance of Cms_Controller_Procesor_Interface',                     
                    Core_Log::ERR,
                    array(
                        'actionModel' => $this->_actionModel,
                        'this' => $this
                    )
                );
            $status = self::ENTITY_MODEL_ERROR;
            $this->_addMessagetoQueue('SYSTEM ERROR: 00001', Core_Log::ERR);
        }
        
        $this->view->form = $form;
        
        return $status;
    }
    
    /**
     * 
     * @param int $id
     * @return int
     */
    protected function _processDeleteItem($id){        
        $status = self::NO_ACTION_STATUS;
        if($this->_actionModel instanceof Cms_Controller_Processor_Interface){
            if($id > 0 && $this->_actionModel->exists($id)){
                if($this->_actionModel->delete($id)){                
                    $this->_log('CMSEnityDeleteSuccess', Core_Log::ACCESS, array(
                        'id' => $id,
                        'model' => get_class($this->_actionModel)
                    ));  

                    $status = self::ENTITY_DELETE_SUCCESS;
                }else{  
                    $messages = $this->_actionModel->getLastMessage();
                    $exception = $this->_actionModel->getLastException();
                    
                    $this->_addMessagetoQueue($messages, Core_Log::ERR);
                    $this->_log($messages, Core_Log::ERR, $exception);
                    $this->_log('CMSEnityDeleteFailed', Core_Log::ACCESS, array(
                            'model' => get_class($this->_actionModel),
                            'id' => $id
                        ));

                    $status = self::ENTITY_DELETE_FAILED;
                }
            }else{
                $status = self::ENTITY_NOT_EXIST;
            }
        }else{
            $this->_log(
                    get_class($this->_actionModel).' must be instance of Cms_Controller_Procesor_Interface',                     
                    Core_Log::ERR,
                    array(
                        'actionModel' => $this->_actionModel,
                        'this' => $this
                    )
                );
            $status = self::ENTITY_MODEL_ERROR;
            $this->_addMessagetoQueue('SYSTEM ERROR: 00002', Core_Log::ERR);
        }
        
        return $status;                
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    protected function _processChangeState($id){     
        if($this->_actionModel instanceof Cms_Controller_Processor_Interface){
            if($id > 0 && $this->_actionModel->exists($id)){   
                if($this->_actionModel->changeState($id)){
                    $this->_log('CMSEnityChangeStateSuccess', Core_Log::ACCESS, array(
                        'id' => $id,
                        'model' => get_class($this->_actionModel)
                    ));

                }else{
                    $messages = $this->_actionModel->getLastMessage();
                    $exception = $this->_actionModel->getLastException();
                    
                    $this->_addMessagetoQueue($messages, Core_Log::ERR);
                    $this->_log($messages, Core_Log::ERR, $exception);
                    $this->_log('CMSEnityChangeStateFailed', Core_Log::ACCESS, array(
                            'model' => get_class($this->_actionModel),
                            'id' => $id
                        ));

                    return false;
                }  
            }
        }else{
            $this->_log(
                    get_class($this->_actionModel).' must be instance of Cms_Controller_Procesor_Interface',                     
                    Core_Log::ERR,
                    array(
                        'actionModel' => $this->_actionModel,
                        'this' => $this
                    )
                );            
            $this->_addMessagetoQueue('SYSTEM ERROR: 00003', Core_Log::WARN);
            
            return false;
        }
        
       return true;
    }
    
    /**
     * 
     * @param int $id
     * @param string $type
     * @return boolean
     */
    protected function _processOrderChange($id, $type='Up'){ 
        if($this->_actionModel instanceof Cms_Controller_Ordering_Interface){
            $type = ucfirst($type);              
            if(($type == 'Up' || $type == 'Down') && $id > 0 && $this->_actionModel->exists($id)){            
                if($this->_actionModel->{'order'.$type}($id)){
                    $this->_actionModel->reorder($id);
                    $this->_log('CMSEnityOrderUpSuccess', Core_Log::ACCESS, array(
                        'id' => $id,
                        'model' => get_class($this->_actionModel)
                    ));                
                }else{
                    $messages = $this->_actionModel->getLastMessage();
                    $exception = $this->_actionModel->getLastException();
                    
                    $this->_addMessagetoQueue($messages, Core_Log::ERR);
                    $this->_log($messages, Core_Log::ERR, $exception);
                    $this->_log('CMSEnityOrderUpFailed', Core_Log::ACCESS, array(
                            'model' => get_class($this->_actionModel),
                            'id' => $id
                        ));

                    return false;
                }
            }
        }else{
            $this->_log(
                    get_class($this->_actionModel).' must be instance of Cms_Controller_Ordering_Interface',                     
                    Core_Log::ERR,
                    array(
                        'actionModel' => $this->_actionModel,
                        'this' => $this
                    )
                );            
            $this->_addMessagetoQueue('SYSTEM ERROR: 00004', Core_Log::WARN);
        }
        
        return true;
    }
    
    /**
     * 
     * @param string $type
     */
    protected function _stdOrderChange($type){
        $id = (int)$this->_getParam('id', 0); 
        
        if(!$this->_processOrderChange($id, $type)){
            $this->_addMessagetoQueue('SYSTEM ERROR: 00005', Core_Log::ERR);
        }
        
        $this->_gotoIndex();
    }
    
    /**
     * 
     * @param string $name
     * @return Cms_Dashboard
     */
    protected function _getDashboard($name=null){
        return Cms_Dashboard::getIstance($name);
    }
    
    /**
     * To be implemented in derived classes
     * 
     * @return void
     */
    protected function _initIndexActionDashboard(){ }
    
    /**
     * To be implemented in derived classes
     * 
     * @return void
     */
    protected function _initAddActionDashboard(){ }
    
    /**
     * To be implemented in derived classes
     * 
     * @return void
     */
    protected function _initEditActionDashboard(){ }
    
    /**
     * 
     * @return string
     */
    protected function _getStateNamespace(){
        return $this->_moduleForRoutes.'.'.$this->_controllerForRoutes;
    }
    
    /**
     * @return void
     */
    protected function _gotoIndex()
    {
        $routename = null;
        
        if((bool)$this->_getAppConfig()->get('router')->get('enable', false) === true){
            $routename = Core_Router_Helper::assembleRoute(
                    $this->_getModuleForRoutes(), 
                    $this->_getControllerForRoutes(), 
                    'index'
                );
        }
        
        $this->_gotoRoute(array_merge(array(
                'module' => $this->_getModuleForRoutes(),
                'controller' => $this->_getControllerForRoutes(),
                'action' => 'index',                
            ), $this->_getGlobalParamsForRoute()), $routename);
    }
}
