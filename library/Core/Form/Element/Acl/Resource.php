<?php

class Core_Form_Element_Acl_Resource extends Core_Form_Element_DbSelect{
    
    /**
     *
     * @var type 
     */
    protected $_table = 'resources';
    
    /**
     *
     * @var string 
     */
    protected $_valueField = 'id';
    
    /**
     *
     * @var array 
     */
    protected $_labelField = array('lang', 'application', 'module', 'controller');
    
    /**
     *
     * @var string 
     */
    protected $_ordering = 'application ASC';
    
    
    /**
     * 
     */
    public function init() {
        $this->addFilter(new Zend_Filter_Int());
    }

}
