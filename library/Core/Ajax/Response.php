<?php


class Core_Ajax_Response {
    
    /**
     *
     * @var int 
     */
    public $status = 0;
    
    /**
     *
     * @var array 
     */
    public $errors = array();
    
    /**
     *
     * @var array 
     */
    public $messages = array();
    
    /**
     * 
     * @return string
     */
    public function toJsonString(){
        return Zend_Json::encode($this);
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->toJsonString();
    }
}
