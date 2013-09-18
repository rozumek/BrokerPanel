<?php

class Application_Model_Resources extends Core_Model_Db_Abstract implements Core_Acl_Processor_Resources_Interface, Cms_Controller_Processor_Interface
{
    
    /**
     * 
     */
    public function __construct() 
    {
        $this->_dbTable = new Application_Model_DbTable_Resources();
    }
    
    /**
     * 
     * @param int $id
     * @return Application_Model_DbTable_Row_Resource
     */
    public function getResource($id)
    {
        return $this->get($id);
    }
    
    /**
     * 
     * @param string $module
     * @param string $controller
     * @return Application_Model_DbTable_Row_Resource
     */
    public function getResourceByParams($module, $controller)
    {
        return $this->getFirstWhere(
                array(                    
                    'module=?',
                    'controller=?',                                        
                ), 
                array(
                    $module,
                    $controller,
                )
            );
    }
    
    /**
     * 
     * @param string $idOrApplication
     * @param string $module
     * @param string $controller
     * @param string $priviledge
     * @param string $lang
     * @return boolean
     */
    public function resourceExists($idOrModule=null, $controller=null)
    {
        if((int) $idOrModule > 0){
            return $this->exists($idOrModule);
        }else if(is_string($idOrModule) && is_string($controller)){
            return (bool) $this->getResourceByParams($idOrModule, $controller);
        }else{
            return false;
        }
        
        return false;
    }
    
    /**
     * 
     * @param int $id
     * @return bool
     */
    public function deleteResource($id)
    {
        return $this->delete($id);
    }
    
    /**
     * 
     * @param type $lang
     * @param type $application
     * @return \Application_Model_Db_Table_Rowset
     */
    public function getResources()
    {
        return $this->getRowset();
    }

    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function createResource(array $data)
    {
        return $this->create($data);
    }
    
    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function create($data)
    {
        $resouce = $this->getEmptyRow();
        
        if($resouce instanceof Application_Model_DbTable_Row_Resource){   
            $data = (array)$data;
            
            $resouce->setModule($data['module'])
                    ->setController($data['controller'])
                    ->setDescription($data['description']);
            
            return $this->save($resouce);            
        }
        
        return false;
    }
    
    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateResource(array $data, $id)
    {
        return $this->update($data, $id);
    }
    
    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $resouce = $this->getResource($id);
        
        if($resouce instanceof Application_Model_DbTable_Row_Resource){
            $data = (array)$data;
            
            $resouce->setModule($data['module'])
                    ->setController($data['controller'])
                    ->setDescription($data['description']);
            
            return $this->save($resouce);
        }
        
        return false;
    }

    /**
     * Does nothing
     * 
     * @param type $id
     * @throws Core_Exception
     */
    public function changeState($id) 
    {
        throw new Core_Exception('Method "changeState" does nothing in Application_Model_Resource');
    }
    
}
