<?php

class Cms_Form_Element_Menu extends Core_Form_Element_DbSelect{
    
     /**
     *
     * @var type 
     */
    protected $_table = 'menus';
    
    /**
     *
     * @var type 
     */
    protected $_valueField = 'id';
    
    /**
     *
     * @var type 
     */
    protected $_labelField = 'label';
    
}
