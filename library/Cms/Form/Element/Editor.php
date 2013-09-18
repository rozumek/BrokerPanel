<?php

class Cms_Form_Element_Editor extends Zend_Form_Element_Textarea
{
    
    /**
     *
     * @var string
     */
    public $helper = 'formTinyMce';
    
    /**
     *
     * @var array
     */
    protected $_allowedEditors = array(
        'wysiwyg',
        'tinyMce'
    );
    
    /**
     *
     * @var array
     */
    protected $_defaultEditor = 'tinyMce';
    
    /**
     * 
     * @param string $spec
     * @param array $options
     */
    public function __construct($spec, $options = null) 
    {
        if(isset($options['editor'])){
            if(in_array($options['editor'], $this->_allowedEditors)){
                $this->helper = 'form'.ucfirst($options['editor']);
            }
        }
        
        parent::__construct($spec, $options);
    }
    
}
