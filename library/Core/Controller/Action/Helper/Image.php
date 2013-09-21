<?php

class Core_Controller_Action_Helper_Image extends Zend_Controller_Action_Helper_Abstract
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
        $fileInfo = pathinfo($filename);
        $type = strtolower($fileInfo['extension']);

        $response = Zend_Controller_Front::getInstance()
                ->getResponse();
        
        $response->setRawHeader("Content-Type: image/{$type}")
                ->setRawHeader("Expires: 0")
                ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
                ->setRawHeader("Pragma: public")
                ->setRawHeader("Content-Length: " . filesize($filename))
                ->setRawHeader('Content-Disposition: inline; filename="' . $fileInfo['basename'] . '"')
                ->sendHeaders();
        
        if ($type == 'png'){
            $image = imagecreatefrompng($filename);  
            imagepng($image);
            imagedestroy($image); 
        } else if($type == 'jpg' || $type == 'jpeg'){
            $image = imagecreatefromjpeg($filename);  
            imagejpeg($image);
            imagedestroy($image); 
        }
        
        if (!$this->suppressExit) {
            $response->sendResponse();
            exit;
        }
    }
}
