<?php

class Core_View_Helper_Version extends Zend_View_Helper_Abstract {

    /**
     *
     * @param string $default
     * @return string
     */
    public function version($default='') {
        if (Zend_Registry::getInstance()->offsetExists('version')) {
            return Zend_Registry::get('version');
        }

        return $default;
    }

    /**
     * Aliast of version
     *
     * @param string $default
     * @return string
     */
    public function getVersion($default='') {
        return $this->version($default);
    }

}
