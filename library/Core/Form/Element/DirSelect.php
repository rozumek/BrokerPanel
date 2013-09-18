<?php

class Core_Form_Element_DirSelect extends Core_Form_Element_Select{
    
    /**
     * For displaying in browser
     */
    const WEB_PATH = 1;
    
    /**
     * Normal file path
     */
    const FILE_PATH = 2;
    
    /**
     *
     * @var string
     */
    protected $_baseDir = PUBLIC_DIR;

    /**
     *
     * @var string
     */
    protected $_directory = null;
    
    /**
     *
     * @var bool
     */
    protected $_addSelectOption = true;
    
    /**
     *
     * @var array
     */
    protected $_data = array();

    /**
     *
     * @var int
     */
    protected $_type = 1;
    
    /**
     * 
     * @param type $spec
     * @param type $options
     */
    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);                
        
        $this->_processDirList();        
        $this->_setValidators();
    }
    
    /**
     * 
     * @return \Core_Form_Element_DirSelect
     */
    protected function _setValidators(){
        
        return $this;
    }


    /**
     * 
     * @param array $options
     * @return \Core_Form_Element_DirSelect
     */
    public function setOptions(array $options) {
        parent::setOptions($options);
               
        if(!empty($options['directory'])){
            $this->_directory = $options['directory'];
        }
        
        if(!empty($options['baseDir'])){
            $this->_baseDir = $options['baseDir'];
        }
        
        if(!empty($options['type'])){
            $this->_type = $options['type'];
        }
        
        return $this;
    }
    
    /**
     * 
     * @param \Zend_View_Interface $view
     * @return string
     */
    public function render(\Zend_View_Interface $view = null) {
        $this->_processDirList();        
        return parent::render($view);        
    }

   
    /**
     * 
     * @return \Core_Form_Element_DirSelect
     */
    protected function _processDirList(){
        if($this->_directory !== null){
            if($this->_addSelectOption === true){
                $this->addMultiOption('', 'SELECT');
            }
            
            $this->addMultiOptions($this->_getDirOptions());
        }
        
        return $this;
    }   
    
    /**
     * 
     * @return array
     */
    protected function _getDirOptions(){
        if(empty($this->_data)){
            $iterator = new DirectoryIterator($this->_baseDir.$this->_directory);            

            foreach ($iterator as $file){
                if($file->isFile() && !$file->isDot()){
                    $fileName = $file->getBasename();
                    
                    if($this->_type == self::FILE_PATH){
                        $filePath = realpath($file->getPathname());
                    }else if($this->_type == self::WEB_PATH){
                        $filePath = $this->_directory.'/'.$fileName;
                    }else{
                        break;
                    }
                            
                    $this->_data[$filePath] = $fileName;
                }
            }
            
        }
        
        return $this->_data;
    }
    
}
