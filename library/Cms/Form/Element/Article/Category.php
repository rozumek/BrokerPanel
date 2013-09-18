<?php

class Cms_Form_Element_Article_Category extends Core_Form_Element_DbSelect{
    
    /**
     *
     * @var type 
     */
    protected $_table = 'categories';
    
    /**
     *
     * @var type 
     */
    protected $_valueField = 'id';
    
    /**
     *
     * @var type 
     */
    protected $_labelField = 'title';
    
    /**
     *
     * @var array 
     */
    protected $_additionalColumns = array('parent');  
    
    /**
     *
     * @var string
     */
    protected $_hierarchyParentField = 'parent';
    
    /**
     *
     * @var type 
     */
    protected $_isHierarchy = true;    
    
}
