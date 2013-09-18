<?php

class Core_Controller_Action_Helper_Csv extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Suppress exit when sendJson() called
     * @var boolean
     */
    public $suppressExit = false;
    
    /**
     * 
     * @param string $filename
     */
    public function direct($filename)
    {
        $response = Zend_Controller_Front::getInstance()
                ->getResponse();
        
        $response->setRawHeader("Content-Type: application/csv; charset=UTF-8")
                ->setRawHeader("Content-Disposition: attachment; filename={$filename}")
                ->setRawHeader("Content-Transfer-Encoding: binary")
                ->setRawHeader("Expires: 0")
                ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
                ->setRawHeader("Pragma: public")
                ->setRawHeader("Content-Length: " . filesize($filename))
                ->sendResponse();
                
        readfile($filename);
                
        if (!$this->suppressExit) {
            $response->sendResponse();
            exit;
        }
                
    }
}
