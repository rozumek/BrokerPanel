<?php

class Core_Form_Element_Submit extends Zend_Form_Element_Submit{
    
    /**
     * Default view helper to use
     * @var string
     */
    public $helper = 'formButtonInnerHtml';   
    
    /**
     * Should we disable loading the default decorators?
     * @var bool
     */
    
    protected $_disableLoadDefaultDecorators = true;
    
    public function init() {
        $this->setAttribs(array(
            'class' => 'red', 
            'type' => 'submit'
        ));   
        
        $this->setDecorators(array(
            'ViewHelper',
            'Errors',
            array('PrepareElements', array(
                'tag' => 'div',
                'class' => 'right'
            )),
            array('HtmlTag', array(
                'tag' => 'div',
                'class' => 'right',            
            )),            
            array ( 
                'decorator' => array ('TableRow' => 'HtmlTag'), 
                'options' => array ('tag' => 'div', 'class' => 'row')
            ),
            array('Description', array(
                'tag' => 'p', 
                'class' => 'description'
            )),
        ));
    }
    
}
