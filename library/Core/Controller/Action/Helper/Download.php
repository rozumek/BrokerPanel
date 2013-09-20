<?php

class Core_Controller_Action_Helper_Download extends Zend_Controller_Action_Helper_Abstract {

    /**
     * Suppress exit when sendJson() called
     * @var boolean
     */
    public $suppressExit = false;

    /**
     *
     * @param string $filename
     */
    public function direct($filename) {
        $response = Zend_Controller_Front::getInstance()
                ->getResponse();

        $response->setRawHeader("Content-Type: application/octet-stream")
                ->setRawHeader("Content-Description: File Transfer")
                ->setRawHeader("Content-Disposition: attachment; filename=" . basename($filename))
                ->setRawHeader("Content-Transfer-Encoding: binary")
                ->setRawHeader("Expires: 0")
                ->setRawHeader("Cache-Control: must-revalidate")
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
