<?php

class Application_Model_Languages extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface
{
       
    /**
     *
     * @var int 
     */
    protected $_defaultActiveState = 0;
    
    /**
     * 
     */
    public function __construct() {
        $this->_dbTable = new Application_Model_DbTable_Languages();
    }
    
    
    /**
     * 
     * @param type $id
     * @return Application_Model_DbTable_Row_Language | null
     */
    public function getLanguage($id){
        return $this->get($id);
    }
    
    /**
     * 
     * @param string $name
     * @return Application_Model_DbTable_Row_Language | null
     */
    public function getLanguageByShortCode($name, $returnEmpty=false){
        return $this->getFirstWhere('shortcode=?', $name, $returnEmpty);
    }

    /**
     * 
     * @param int|string $id
     * @return bool
     */
    public function languageExists($id){
        if((int) $id > 0){
            return $this->exists($id);
        }else if(is_string($id)){
            return (bool) $this->getLanguageByShortCode($id);
        }
        
        return false;
    }    
    
    /**
     * 
     * @param type $id
     */
    public function deleteLanguage($id){
        return $this->delete($id);
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    public function changeLanguageState($id){
        $lang = $this->getLanguage($id);
        if($lang){            
            $lang->setActive(!$lang->getActive());
            return $this->save($lang);
        }
        
        return false;
    }
    
    /**
     * 
     * @param bool $state
     * @param int $id
     * @return boolean
     */
    public function setLanguageState($state, $id){
        $lang = $this->getLanguage($id);
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
        $lang = $this->getEmptyRow();
        
        if($lang){   
            $data = (array)$data;
            $lang->setLanguage($data['language']);
            $lang->setShortCode($data['shortcode']);
            $lang->setCode($data['code']);
            $lang->setDescription($data['description']);
            $lang->setActive(Core_Array::get($data, 'active', $this->_defaultActiveState));
            
            return $this->save($lang);            
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
        $lang = $this->get($id);
        
        if($lang instanceof Application_Model_DbTable_Row_Language){
            $lang->setLanguage($data['language']);
            $lang->setShortCode($data['shortcode']);
            $lang->setCode($data['code']);
            $lang->setDescription($data['description']);
            $lang->setActive(Core_Array::get($data, 'active', $this->_defaultActiveState));
            
            return $this->save($lang);
        }
        
        return false;
    }
    
    /**
     * 
     * @return \Core_Model_Db_Table_Rowset
     */
    public function getLanguages(){
        return $this->getRowset();
    }
    
    /**
     * 
     * @return array
     */
    public function getLanguagesShortCodes(){
        $list = $this->getRowset();        
        if($list->count() > 0){
            $shortcodes = array();            
            foreach($list as $lang){
                if($lang instanceof Application_Model_DbTable_Row_Language){
                    $shortcodes[] = $lang->getShortCode ();
                }
            }
            return $shortcodes;
        }
        
        return array();
    }
    
    /**
     * 
     * @return Application_Model_DbTable_Row_Language
     */
    public function getDefaultLanguage(){
        return $this->getFirstWhere('defaultlang=?', 1);
    }
    
    /**
     * 
     * @return string|null
     */
    public function getDefaultLanguageShortCode($default='pl'){
        $lang = $this->getDefaultLanguage();
        
        if($lang instanceof Application_Model_DbTable_Row_Language){
            return $lang->getShortCode();
        }
        
        return $default;
    }

    /**
     * 
     * @param int $id
     */
    public function changeState($id) {
        $language = $this->getLanguage($id);
        if($language instanceof Application_Model_DbTable_Row_Language){            
            $language->setActive(!$language->getActive());
            return $this->save($language);
        }
        
        return false;
    }

    /**
     * 
     * @param array $data
     * @return bool
     */
    public function createLanguage($data) {
        return $this->create($data);
    }

    /**
     * 
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateLanguage($data, $id) {
        return $this->update($data, $id);
    }
}

