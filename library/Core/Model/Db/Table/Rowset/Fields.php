<?php

class Core_Model_Db_Table_Rowset_Fields extends Core_Model_Db_Table_Rowset_Abstract{
    
     /**
     *
     * @var bool
     */
    protected $_translateEnable = false;
    
    /**
     *
     * @var array
     */
    protected $_translateColumns = array(
        'label',
        'options'
    );
    
    /**
     *
     * @var string
     */
    protected $_currentLanguage = null;
    
    /**
     *
     * @var array
     */
    protected $_translateValues = array();
    
    /**
     * 
     * @param bool $enable
     * @return \Core_Model_DbTable_Row_Field
     */
    public function setTranslationEnabled($enable){
        $this->_translateEnable = (bool)$enable;
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function translationEnabled(){
        return $this->_translateEnable === true;
    }


    /**
     * 
     * @return array
     */
    public function getTranslationColumns(){
        return $this->_translateColumns;
    }
    
    /**
     * 
     * @param string $language
     * @return \Core_Model_DbTable_Row_Field
     */
    public function setCurrentLanguage($language){
        $this->_currentLanguage = $language;
        return $this;
    }
    
    /**
     * 
     * @param string $language
     * @return \Core_Model_Db_Table_Rowset_Fields
     */
    public function translateFields($language){
        if($language !== null && $this->count() > 0){
            $this->setTranslationEnabled(true);  
            $this->setCurrentLanguage($language);
            $ids = $this->getFieldsIds();
            
            $select = $this->getDbAdapter()
                    ->select()
                    ->from('fields_translations')
                    ->where('language=?', $language)
                    ->where('id IN (?)', implode(',', $ids));
            
            $fieldsTranslations = $this->getDbAdapter()
                    ->fetchAll($select)
                    ;
            
            foreach ($fieldsTranslations as $tranlation){
                if(!isset($this->_translateValues[$language])){
                    $this->_translateValues[$language] = array();
                }
                
                if(!isset($this->_translateValues[$language][$tranlation['id']])){
                    $this->_translateValues[$language][$tranlation['id']] = array();
                }
                
                foreach($this->getTranslationColumns() as $column){
                    if(isset($tranlation[$column])){
                        $this->_translateValues[$language][$tranlation['id']][$column] = $tranlation[$column];
                    }
                }    
            }
        }
        
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function toArray() {
        $array = parent::toArray();
        
        if($this->translationEnabled() && $this->_currentLanguage !== null && !empty($this->_translateValues[$this->_currentLanguage])){
            foreach($array as $key => $field){
                foreach($this->getTranslationColumns() as $column){
                    if(isset($this->_translateValues[$this->_currentLanguage][$field['id']][$column])){
                        $field[$column] = $this->_translateValues[$this->_currentLanguage][$field['id']][$column];
                        $array[$key] = $field;
                    }
                }
            }
        }
        
        return $array;
    }
    
    /**
     * 
     * @return array
     */
    public function getFieldsIds(){
        $ids = array();
        
        foreach ($this as $field){
            if($field instanceof Core_Model_DbTable_Row_Field){
                $ids[] = $field->getId();
            }
        }
        
        return $ids;
    }
    
}
