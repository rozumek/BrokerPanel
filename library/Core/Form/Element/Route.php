<?php

class Core_Form_Element_Route extends Core_Form_Element_DbSelect{
     
    /**
     *
     * @var string 
     */
    protected $_table = 'routes';
    
    /**
     *
     * @var string 
     */
    protected $_valueField = 'id';
    
    /**
     *
     * @var string 
     */
    protected $_labelField = 'route';
        
    /**
     *
     * @var string
     */
    protected $_ordering = 'route';
    
}
