<?php

class Application_Model_Domains extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface
{
       
    /**
     *
     * @var int 
     */
    protected $_defaultActiveState = 0;
    
    /**
     *
     * @var string
     */
    protected $_defaultLayout = 'layout';
    
    /**
     * 
     */
    public function __construct() {
        $this->_dbTable = new Application_Model_DbTable_Domains();
    }
    
    /**
     * 
     * @param string $layout
     * @return \Core_Model_Domains
     */
    public function setDefaultLayout($layout){
        $this->_defaultLayout = $layout;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getDefaultLayout(){
        return $this->_defaultLayout;
    }

    /**
     * 
     * @param type $id
     * @return Application_Model_DbTable_Row_Domain | null
     */
    public function getDomain($id){
        return $this->get($id);
    }
    
    /**
     * 
     * @param string $name
     * @return Application_Model_DbTable_Row_Domain | null
     */
    public function getDomainByName($name, $returnEmpty=false){
        return $this->getFirstWhere('domain=?', $name, $returnEmpty);
    }

    /**
     * 
     * @param int|string $id
     * @return string
     */
    public function getDomainLayout($id){         
        if((int) $id > 0){
            $domain = $this->getFirstWhere(array('id=?', 'active=?'), array((int)$id, 1));
        }else if(is_string($id)){
            $domain = $this->getFirstWhere(array('domain=?', 'active=?'), array($id, 1));
        }
        
        if($domain instanceof Application_Model_DbTable_Row_Domain){
            return $domain->getLayout();
        }
        
        return $this->getDefaultLayout();
    }

    /**
     * 
     * @param string $domainName
     * @return boolean
     */
    public function isDomainActive($domainName){
        $domain = $this->getDomainByName($domainName);
        
        if($domain instanceof Application_Model_DbTable_Row_Domain){
            return $domain->isActive();
        }
        
        return false;
    }


        /**
     * 
     * @param int|string $id
     * @return bool
     */
    public function domainExists($id){
        if((int) $id > 0){
            return $this->exists($id);
        }else if(is_string($id)){
            return (bool)$this->getDomainByName($id);
        }
        
        return false;
    }    
    
    /**
     * 
     * @param type $id
     */
    public function deleteDomain($id){
        return $this->delete($id);
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    public function changeDomainState($id){
        return $this->changeState($id);
    }
    
    /**
     * 
     * @param bool $state
     * @param int $id
     * @return boolean
     */
    public function setDomainState($state, $id){
        $lang = $this->getDomain($id);
        if($lang){
            $lang->setActive((int)$state);
            return $this->save($lang);           
        }
        
        return false;
    }
    
    /**
     * 
     * @param array|object $data
     * @return boolean
     */
    public function create($data){
        $domain = $this->getEmptyRow();
        
        if($domain instanceof Application_Model_DbTable_Row_Domain){   
            $data = (array)$data;
            $domain->setDomain($data['domain']);
            $domain->setLang($data['lang']);
            $domain->setDescription($data['description']);
            $domain->setActive(Core_Array::get($data, 'active', $this->_defaultActiveState));
            $domain->setLayout(Core_Array::get($data, 'layout', $this->_defaultLayout));
            
            return $this->save($domain);            
        }
        
        return false;
    }
    
     /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id){
        $domain = $this->get($id);
        
        if($domain instanceof Application_Model_DbTable_Row_Domain){
            $data = (array)$data;
            $domain->setDomain($data['domain']);
            $domain->setLang($data['lang']);
            $domain->setDescription($data['description']);
            $domain->setActive(Core_Array::get($data, 'active', $this->_defaultActiveState));
            $domain->setLayout(Core_Array::get($data, 'layout', $this->_defaultLayout));
            
            return $this->save($domain);
        }
        
        return false;
    }
    
    /**
     * 
     * @return \Core_Model_Db_Table_Rowset
     */
    public function getDomains(){
        return $this->getRowset();
    }
    
    /**
     * 
     * @return array
     */
    public function getLanguageDomainMap($active=1){
        $domains = $this->getWhere(array('active=?'), array($active));
        
        if($domains->count() > 0){
            $languageDomainMap = array();
            foreach($domains as $domain){
                if($domain instanceof Application_Model_DbTable_Row_Domain){
                    $languageDomainMap[$domain->getDomain()] = $domain->getLang();
                }
            }
            
            return $languageDomainMap;
        }
        
        return array();
    }

    /**
     * 
     * @param int $id
     * @return boolean
     */
    public function changeState($id) {
        $domain = $this->getDomain($id);
        
        if($domain instanceof Application_Model_DbTable_Row_Domain){            
            $domain->setActive(!$domain->getActive());
            return $this->save($domain);
        }
        
        return false;
    }

    /**
     * 
     * @param array $data     
     * @return boolean
     */
    public function createDomain($data) {
        return $this->create($data);
    }

    /**
     * 
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateDomain($data, $id) {
        return  $this->update($data, $id);
    }
}

