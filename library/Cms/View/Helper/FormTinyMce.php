<?php

class Cms_View_Helper_FormTinyMce extends Core_View_Helper_Abstract
{
    /**
     *
     * @var string
     */
    protected $_baseClass = 'timymce';
    
    /**
     *
     * @var bool
     */
    protected static $_scriptsAdded = false;
    
    /**
     *
     * @var array
     */
    protected $_jsScripts = array(
        '/js/tiny_mce/tiny_mce.js',
        '/js/common/helpers/formTinyMce.js'
    );
    
    /**
     *
     * @var array 
     */
    protected $_defaultAttribs = array(
        'rows' => 15,
        'cols' => 80,
        'style' => 'width:80%'
    );
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @param array $attribs
     * @return type
     */
    public function formTinyMce($name, $value = null, $attribs = null)
    {
        $this->addJsScripts();
        $this->_mergeAttribs($attribs);
        
        if(isset($attribs['class'])){
            $attribs['class'] .= 'tinymce '.$attribs['class'];
        }else{
            $attribs['class'] = 'tinymce';
        }                
        
        return $this->_invokeHelper('textarea', $name, $value, $attribs);
    }
    
    /**
     * 
     * @return \Cms_View_Helper_FormTinyMce
     */
    public function addJsScripts()
    {
        if(self::$_scriptsAdded === false){
            self::$_scriptsAdded = true;
            
            foreach($this->_jsScripts as $script){
                $this->view->headScript()
                    ->appendFile($script);
            }
        }
        return $this;
    }
}
