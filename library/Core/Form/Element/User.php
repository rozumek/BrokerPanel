<?php

class Core_Form_Element_User extends Core_Form_Element_DbSelect
{
    
     /**
     *
     * @var sting 
     */
    protected $_table = 'users';
    
    /**
     *
     * @var sting 
     */
    protected $_valueField = 'id';
    
    /**
     *
     * @var sting 
     */
    protected $_labelField = 'name';        

}
