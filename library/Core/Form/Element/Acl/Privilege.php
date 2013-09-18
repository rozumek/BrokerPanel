<?php

class Core_Form_Element_Acl_Privilege extends Core_Form_Element_DbSelect{
    
    /**
     *
     * @var type 
     */
    protected $_table = 'acl';
    
    /**
     *
     * @var type 
     */
    protected $_valueField = 'priviledge';
    
    /**
     *
     * @var type 
     */
    protected $_labelField = 'priviledge';        

    /**
     *
     * @var type 
     */
    protected $_ordering = 'priviledge ASC';
    
}
