<?php

/**
 * Description of Jsonp
 *
 * @author Marcinek
 */
class Core_Controller_Action_Helper_Jsonp extends Zend_Controller_Action_Helper_Abstract{
    
    /**
     * Suppress exit when sendJson() called
     * @var boolean
     */
    public $suppressExit = false;

    
    /**
     * 
     * @param Core_Ajax_Response|array|object|stdClass $data
     * * @param string $callback
     * @param type $keepLayouts
     */
    public function encodeJsonp($data, $callback='jsonpCallback', $keepLayouts=false){
        if($data instanceof Core_Ajax_Response){
            $data = $data->toJsonString();
        }else{
            $jsonHelper = new Zend_View_Helper_Json();
            $data = $jsonHelper->json($data, $keepLayouts);
        }
        
        if (!$keepLayouts) {
            Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
        }
        
        return $callback.'('.$data.')';
    }
    
    
    /**
     * 
     * @param Core_Ajax_Response|array|object|stdClass $data
     * @param string $callback
     * @param bool $keepLayouts
     * @return string
     */
    public function sendJsonp($data, $callback='jsonpCallback', $keepLayouts=false){
        $data = $this->encodeJsonp($data, $callback, $keepLayouts);
        $response = $this->getResponse();
        $response->setBody($data);

        if (!$this->suppressExit) {
            $response->sendResponse();
            exit;
        }

        return $data;
    }
    
    /**
     * 
     * @param Core_Ajax_Response|array|object|stdClass $data
     * @param string $callback
     * @param bool $sendNow
     * @param bool $keepLayouts
     * @return string
     */
    public function direct($data, $callback='jsonpCallback', $sendNow = true, $keepLayouts = false){
        if ($sendNow) {
            return $this->sendJsop($data, $callback, $keepLayouts);
        }
        return $this->encodeJsonp($data, $keepLayouts);
    }
}
