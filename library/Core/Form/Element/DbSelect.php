<?php

class Core_Form_Element_DbSelect extends Core_Form_Element_Select{
    
    /**
     *
     * @var Zend_Db_Adapter_Abstract 
     */
    protected static $_defaultDbAdapter = null;
    
    /**
     *
     * @var Zend_Db_Adapter_Abstract 
     */
    protected $_db = null;
    
    /**
     *
     * @var type 
     */
    protected $_table = null;
    
    /**
     *
     * @var type 
     */
    protected $_valueField = null;
    
    /**
     *
     * @var type 
     */
    protected $_labelField = null;
    
    /**
     *
     * @var string 
     */
    protected $_whereConditionMap = array();

    /**
     *
     * @var array
     */
    protected $_data = array();
    
    /**
     *
     * @var array 
     */
    protected $_rawData = array();
    
    /**
     *
     * @var array 
     */
    protected $_additionalColumns = array();
    
    /**
     *
     * @var type 
     */
    protected $_ordering = null;
    
    /**
     *
     * @var type 
     */
    protected $_isHierarchy = false;
    
    /**
     *
     * @var string
     */
    protected $_hierarchyParentField = '';
    
    /**
     *
     * @var bool
     */
    protected $_addSelectOption = true;
    
    /**
     *
     * @var bool
     */
    private $_rendered = false;


    /**
     * 
     * @param type $spec
     * @param type $options
     */
    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);                
        
        $this->_processDbList();        
        $this->_setValidators();
    }
    
    /**
     * 
     * @return \Core_Form_Element_DbSelect
     */
    protected function _setValidators(){
        $this->addValidator(new Zend_Validate_Db_RecordExists(array(
            'table' => $this->_table,
            'field' => $this->_valueField
        )));
        
        return $this;
    }


    /**
     * 
     * @param array $options
     * @return \Core_Form_Element_DbSelect
     */
    public function setOptions(array $options) {
        parent::setOptions($options);
        
        if(isset($options['table'])){
            $this->_table = $options['table'];
        }        
        if(isset($options['label'])){
            $this->_labelField = $options['label'];
        }        
        if(isset($options['value'])){
            $this->_valueField = $options['value'];
        }        
        if(isset($options['dbAdapter'])){
            $this->_db = $options['dbAdapter'];
        }
        if(isset($options['where']) && isset($options['where']['cond']) && isset($options['where']['values'])  ){
            $this->_whereConditionMap = $options['where'];
        }
        
       return $this;
    }
    
    /**
     * 
     * @param \Zend_View_Interface $view
     * @return string
     */
    public function render(\Zend_View_Interface $view = null) {
        $this->_processDbList();        
        return parent::render($view);        
    }

    /**
     * 
     * @param type $state
     * @return \Core_Form_Element_DbSelect
     */
    public function setHierarchy($state){
        $this->_isHierarchy = $state;
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function isHierarchy(){
        return $this->_isHierarchy === true && in_array($this->_hierarchyParentField, $this->_additionalColumns);
    }

    /**
     * 
     * @return \Core_Form_Element_DbSelect
     * @throws Core_Form_Element_DbSelect_Exception
     */
    protected function _initDbAdapter(){        
        if(self::$_defaultDbAdapter === null && $this->_db === null){
            self::$_defaultDbAdapter = Zend_Db_Table::getDefaultAdapter();            
        }
        
        if($this->_db === null){
            $this->_db = self::$_defaultDbAdapter;
        }
             
        if(!$this->_db instanceof Zend_Db_Adapter_Abstract){
            throw new Core_Form_Element_DbSelect_Exception;
        }
        
        return $this;
    }

        /**
     * 
     * @param Zend_Db_Adapter_Abstract $db
     */
    public static function setDefaultDbAdapter(Zend_Db_Adapter_Abstract $db){
        self::$_defaultDbAdapter = $db;
    }
    
    /**
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    public static function getDefautlDbAdapter(){
        return self::$_db;
    }
    
    /**
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    public function _getDbAdapter(){
        return $this->_db;
    }
    
    /**
     * 
     * @param Zend_Db_Adapter_Abstract $db
     * @return \Core_Form_Element_DbSelect
     */
    public function setDbAdapter($db){
        $this->_db = $db;
        return $this;
    }


    /**
     * 
     * @param string $table
     * @return \Core_Form_Element_DbSelect
     */
    public function setTable($table){
        $this->_table = $table;        
        return $this;
    }
    
    /**
     * 
     * @param string $field
     * @return \Core_Form_Element_DbSelect
     */
    public function setField($field){
        $this->_field = $field;        
        return $this;
    }

    /**
     * 
     * @return \Core_Form_Element_DbSelect
     */
    protected function _processDbList(){
        if(!$this->_rendered){
            if($this->_table !== null && $this->_valueField !== null && $this->_labelField !== null){
                $this->_initDbAdapter();

                if($this->_addSelectOption === true){
                    $this->addMultiOption('', 'SELECT');
                }
                $this->addMultiOptions($this->_getDbOptions());
                $this->_rendered = true;
            }
        }
        
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    protected function _getDbRawOptions(){
        if(is_array($this->_labelField)){
            $columns = array_merge($this->_labelField, array($this->_valueField));
        }else{
            $columns = array($this->_labelField, $this->_valueField);
        }
        
        if(is_array($this->_additionalColumns)){
            $columns = array_merge($columns, $this->_additionalColumns);
        }

        $query = $this->_getDbAdapter()
                ->select()
                ->from($this->_table, $columns);

        if(!empty($this->_whereConditionMap) && isset($this->_whereConditionMap['cond']) && $this->_whereConditionMap['values']){
            $this->_whereConditionMap['cond'] = (array)$this->_whereConditionMap['cond'];
            $this->_whereConditionMap['values'] = (array)$this->_whereConditionMap['values'];

            foreach($this->_whereConditionMap['cond'] as $index => $cond){
                if(isset($this->_whereConditionMap['values'][$index])){
                    $query->where($cond, $this->_whereConditionMap['values'][$index]);
                }else{
                    $query->where($cond);
                }
            }
        }
        
        if($this->_ordering !== null){
            $query->order($this->_ordering);
        }

        $this->_rawData = $this->_getDbAdapter()
                ->fetchAll($query);

        return $this->_rawData;
    }
    
    /**
     * 
     * @return array
     */
    protected function _fomatOptionsForOutput(){
        if($this->isHierarchy()){
            $data = array();
            Core_Hierarchy_Helper::setIdKey($this->_valueField);
            Core_Hierarchy_Helper::setNameKey($this->_labelField);
            Core_Hierarchy_Helper::setParentKey($this->_hierarchyParentField);
            Core_Hierarchy_Helper::toHierarchy($this->_rawData, null, $data, null, 0);   
            
            if(!empty($data)){
                $this->_rawData = $data;
                $this->_labelField = 'h'.$this->_labelField;
            }
        }
        
        if(!empty($this->_labelField) && !empty($this->_valueField)){
            foreach($this->_rawData as $row){
                if(is_array($this->_labelField)){
                    $key = array();
                    foreach ($this->_labelField as $vf){
                        $key[] = $row[$vf];
                    }     
                    $key = implode('-' ,$key);
                    $this->_data[$row[$this->_valueField]] = $key;
                }else{
                    $this->_data[$row[$this->_valueField]] = $row[$this->_labelField];
                }
            }
        }
        
        return $this->_data;
    }

    /**
     * 
     * @return \Core_Form_Element_DbSelect
     */
    protected function _getDbOptions(){        
        if(empty($this->_data)){   
            $this->_rawData = $this->_getDbRawOptions();
            $this->_data = $this->_fomatOptionsForOutput();            
        }
        
        return $this->_data;
    }
    
}
