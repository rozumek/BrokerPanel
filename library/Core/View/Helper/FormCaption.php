<?php

class Core_View_Helper_FormCaption extends Core_View_Helper_Abstract{        
    
    /**
     *
     * @var string
     */
    protected $_captionWrapper = 'strong';
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @param array $attribs
     * @return string
     */
    public function formCaption($name, $value = null, array $attribs = array()){       
       $length = Core_Array::get($attribs, 'captionLength', null);
       $captionValue = $value;
       
       if($length !== null && (int)$length > 0){
           $captionValue = substr($value, 0, $length);
       }
       
       $html = $this->_prepareCaption($name, $captionValue, $attribs);
       
       if((bool)Core_Array::get($attribs, 'captionOnly', false) === false){
            $html .= $this->_invokeHelper('hidden', $name, $value, $attribs);
       }
       
       return $html;
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @param array $attribs
     * @return 
     */
    protected function _prepareCaption($name, $value, array $attribs=array()){
        $id = Core_Array::get($attribs, 'id', 'id-'.$name);
        $class = 'caption-'.$name.' '.Core_Array::get($attribs, 'class', '');
        
        return '<'.$this->_captionWrapper.' id="'.$id.'" class="'.$class.'" >'
                    .$value
                .'</'.$this->_captionWrapper.'>';
    }
    
    /**
     * 
     * @param type $wrapper
     * @return \Core_View_Helper_FormCaption
     */
    public function setCaptionWrapper($wrapper){
        $this->_captionWrapper = $wrapper;
        return $this;
    }
   
}
