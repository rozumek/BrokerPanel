<?php

class Cms_Form_Element_MenuItem extends Core_Form_Element_DbSelect{
    
     /**
     *
     * @var type 
     */
    protected $_table = 'menu_items';
    
    /**
     *
     * @var type 
     */
    protected $_valueField = 'id';
    
    /**
     *
     * @var type 
     */
    protected $_labelField = 'name';
    
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
    
    /**
     *
     * @var array
     */
    protected $_ordering = array('menu', 'ordering');
    
    /**
     * 
     * @param array $options
     * @return \Cms_Form_Element_MenuItem
     */
    public function setOptions(array $options) {
        parent::setOptions($options);
        
        if(isset($options['menu']) && $options['menu'] > 0){
            $this->_whereConditionMap = array(
                'cond' => array('menu=?'),
                'values' => array($options['menu'])
            );
        }
        
        return $this;
    }    
    
}
